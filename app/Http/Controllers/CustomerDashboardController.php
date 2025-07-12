<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Review;
use App\Models\BookingLog;
use App\Models\EmailNotification;
use App\Models\Perawatan;
use App\Models\Shift;
use App\Mail\BookingNotificationMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\Booked;
use App\Models\Karyawan;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CustomerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pelanggan');
    }

    public function index(Request $request)
{
    $user = Auth::guard('pelanggan')->user();

    // Statistik dashboard
    $totalBookings = Pemesanan::where('id_pelanggan', $user->id_pelanggan)->count();
    $upcomingBookings = Pemesanan::where('id_pelanggan', $user->id_pelanggan)
        ->where('tanggal_pemesanan', '>=', now()->toDateString())
        ->whereIn('status_pemesanan', ['confirmed', 'pending'])
        ->count();
    $completedBookings = Pemesanan::where('id_pelanggan', $user->id_pelanggan)
        ->where('status_pemesanan', 'completed')
        ->count();
    $totalSpent = Pemesanan::where('id_pelanggan', $user->id_pelanggan)
        ->where('status_pemesanan', 'completed')
        ->sum('total');

    // Booking mendatang
    $upcomingBookingsList = Pemesanan::with(['bookeds.perawatan', 'karyawan'])
        ->where('id_pelanggan', $user->id_pelanggan)
        ->where('tanggal_pemesanan', '>=', now()->toDateString())
        ->whereIn('status_pemesanan', ['confirmed', 'pending'])
        ->orderBy('tanggal_pemesanan')
        ->orderBy('waktu')
        ->limit(3)
        ->get();

    // Booking terbaru
    $recentBookings = Pemesanan::with(['bookeds.perawatan', 'karyawan'])
        ->where('id_pelanggan', $user->id_pelanggan)
        ->orderByDesc('tanggal_pemesanan')
        ->orderByDesc('waktu')
        ->limit(5)
        ->get();

    // Booking yang dapat direview
    $reviewableBookings = Pemesanan::with(['bookeds.perawatan', 'karyawan'])
        ->where('id_pelanggan', $user->id_pelanggan)
        ->where('status_pemesanan', 'completed')
        ->whereDoesntHave('review')
        ->limit(3)
        ->get();

    // Handle payment status messages dari redirect
    $alertType = null;
    $alertMessage = null;

    if ($request->has('status') && $request->has('message')) {
        $status = $request->get('status');
        $message = $request->get('message');

        switch ($status) {
            case 'success':
                $alertType = 'success';
                $alertMessage = $message;
                break;
            case 'pending':
                $alertType = 'warning';
                $alertMessage = $message;
                break;
            case 'error':
                $alertType = 'danger';
                $alertMessage = $message;
                break;
        }
    }

    return view('customer.dashboard', compact(
        'user',
        'totalBookings',
        'completedBookings',
        'upcomingBookings',
        'recentBookings',
        'reviewableBookings',
        'totalSpent',
        'upcomingBookingsList',
        'alertType',
        'alertMessage'
    ));
}



/**
 * Update payment status via internal API
 */
public function updatePaymentStatus(Request $request)
{
    $request->validate([
        'order_id' => 'required',
        'transaction_status' => 'required|in:settlement,pending,cancel,expire,failure'
    ]);

    $orderId = $request->order_id;
    $status = $request->transaction_status;

    $pembayaran = Pembayaran::find($orderId);

    if (!$pembayaran) {
        return response()->json([
            'success' => false,
            'message' => 'Pembayaran tidak ditemukan'
        ], 404);
    }

    try {
        DB::beginTransaction();

        if ($status === 'settlement') {
            // Payment success
            $pembayaran->update([
                'status_pembayaran' => 'paid',
                'tanggal_pembayaran' => now(),
                'notifikasi' => 'Pembayaran berhasil'
            ]);

            $pembayaran->pemesanan->update([
                'status_pemesanan' => 'confirmed',
                'status_pembayaran' => 'paid'
            ]);

            $message = 'Pembayaran berhasil! Booking Anda telah dikonfirmasi.';
            $alertType = 'success';

        } elseif ($status === 'pending') {
            $pembayaran->update([
                'status_pembayaran' => 'pending',
                'notifikasi' => 'Pembayaran sedang diproses'
            ]);

            $message = 'Pembayaran sedang diproses.';
            $alertType = 'warning';

        } else {
            // cancel, expire, failure
            $pembayaran->update([
                'status_pembayaran' => 'failed',
                'notifikasi' => 'Pembayaran gagal atau dibatalkan'
            ]);

            $message = 'Pembayaran gagal atau dibatalkan.';
            $alertType = 'error';
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => $message,
            'alert_type' => $alertType
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        Log::error('Payment update error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat memproses pembayaran'
        ], 500);
    }
}

        /**
     * Store booking dari customer dashboard
     */
    public function storeBooking(Request $request)
    {
        $user = Auth::guard('pelanggan')->user();

        // 1) Validasi
        $request->validate([
            'services' => 'required|array|min:1',
            'services.*.id' => 'required',
            'services.*.name' => 'required|string',
            'services.*.price' => 'required|numeric|min:0',
            'services.*.duration' => 'required|numeric|min:1',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'payment_method' => 'required|in:midtrans',
            'total_price' => 'required|numeric|min:0',
        ]);

        try {
            // 2) Siapkan data
            $services = $request->services;
            $totalPrice = $request->total_price;
            $totalDuration = collect($services)->sum(fn($s) => $s['duration']);
            $startTime = $request->booking_time;
            $endTime = Carbon::parse($startTime)->addMinutes($totalDuration)->format('H:i');

            // 3) Cari karyawan yang tersedia
            $availableEmployees = $this->findAvailableEmployees(
                $request->booking_date,
                $startTime,
                $endTime
            );

            if (empty($availableEmployees)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada karyawan yang tersedia untuk waktu tersebut.'
                ], 400);
            }

            // 4) Pilih karyawan pertama yang tersedia
            $selectedEmployee = $availableEmployees[0];

            // 5) Buat header pemesanan
            $pemesanan = Pemesanan::create([
                'id_user' => $user->id_pelanggan,
                'id_pelanggan' => $user->id_pelanggan,
                'id_karyawan' => $selectedEmployee['id'],
                'tanggal_pemesanan' => $request->booking_date,
                'waktu' => $startTime . ':00',
                'jumlah_perawatan' => count($services),
                'total' => $totalPrice,
                'sub_total' => $totalPrice,
                'metode_pembayaran' => $request->payment_method,
                'status_pemesanan' => 'pending',
                'status_pembayaran' => 'unpaid',
                'token' => '',
            ]);

            // 6) Buat detail bookeds
            foreach ($services as $svc) {
                Booked::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'id_perawatan' => $svc['id'],
                    'tanggal_booked' => $request->booking_date,
                    'waktu' => $startTime . ':00',
                ]);
            }

            // 7) Buat pembayaran dulu tanpa order_id
            $pembayaran = Pembayaran::create([
                'id_pemesanan' => $pemesanan->id_pemesanan,
                'total_harga' => $totalPrice,
                'metode_pembayaran' => $request->payment_method,
                'status_pembayaran' => 'unpaid',
                'snap_token' => '',
                'notifikasi' => 'Menunggu pembayaran',
            ]);

            // Generate order_id setelah pembayaran dibuat (pakai ID pembayaran)
            $orderId = $pembayaran->id_pembayaran . '-' . time();

            // Update pembayaran dengan order_id
            $pembayaran->update(['order_id' => $orderId]);

            // 8) Siapkan item_details untuk Midtrans
            $itemDetails = collect($services)->map(fn($s) => [
                'id' => $s['id'],
                'price' => $s['price'],
                'quantity' => 1,
                'name' => $s['name'],
            ])->values()->all();

            // 9) Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // 10) Generate Snap Token
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $user->nama_lengkap,
                    'email' => $user->email,
                    'phone' => $user->no_telepon,
                ],
                'item_details' => $itemDetails,
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $pembayaran->update(['snap_token' => $snapToken]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId, // Return order_id Midtrans
                'payment_id' => $pembayaran->id_pembayaran, // ID pembayaran internal
                'assigned_employee' => $selectedEmployee['name']
            ]);

        } catch (\Exception $e) {
            Log::error("Booking Error: {$e->getMessage()}");

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses booking: ' . $e->getMessage()
            ], 500);
        }
    }


    public function bookingHistory(Request $request)
    {
        $user = Auth::guard('pelanggan')->user();

        // Gunakan relasi review() singular untuk compatibility
        $query = Pemesanan::with(['bookeds.perawatan', 'karyawan', 'review'])
            ->where('id_pelanggan', $user->id_pelanggan);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status_pemesanan', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->where('tanggal_pemesanan', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('tanggal_pemesanan', '<=', $request->date_to);
        }

        $bookings = $query->orderByDesc('tanggal_pemesanan')
            ->orderByDesc('waktu')
            ->paginate(10);

        return view('customer.booking-history', compact('bookings'));
    }

    // public function createBooking()
    // {
    //     $services = Perawatan::where('is_active', true)->get(); // Gunakan is_active bukan status

    //     // Check if Shift model exists, otherwise skip
    //     $shifts = collect();
    //     $timeSlots = [];

    //     if (class_exists('\App\Models\Shift')) {
    //         $shifts = Shift::with('karyawans')->get();
    //         // Generate time slots
    //         foreach ($shifts as $shift) {
    //             $timeSlots[$shift->nama_shift] = $this->generateTimeSlots(
    //                 $shift->start_time,
    //                 $shift->end_time,
    //                 30
    //             );
    //         }
    //     } else {
    //         // Default time slots jika model Shift tidak ada
    //         $timeSlots['Default'] = $this->generateTimeSlots('09:00', '17:00', 30);
    //     }

    //     return view('customer.create-booking', compact('services', 'shifts', 'timeSlots'));
    // }



    public function cancelBooking(Request $request, $id)
    {
        $user = Auth::guard('pelanggan')->user();
        $pemesanan = Pemesanan::where('id_pelanggan', $user->id_pelanggan)
            ->where('id_pemesanan', $id)
            ->firstOrFail();

        // Validasi H-1
        $bookingDate = Carbon::parse($pemesanan->tanggal_pemesanan);
        $now = Carbon::now();

        if ($bookingDate->diffInDays($now) < 1 || $bookingDate->isPast()) {
            return back()->withErrors(['error' => 'Pembatalan hanya bisa dilakukan maksimal H-1']);
        }

        if (!in_array($pemesanan->status_pemesanan, ['confirmed', 'pending'])) {
            return back()->withErrors(['error' => 'Booking tidak dapat dibatalkan']);
        }

        $request->validate([
            'reason' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            // Update status pemesanan
            $pemesanan->update([
                'status_pemesanan' => 'cancelled',
                'alasan_pembatalan' => $request->reason,
                'cancelled_at' => now(),
                'cancelled_by' => 'customer'
            ]);

            // Log pembatalan - hanya jika tabel booking_logs ada
            if (Schema::hasTable('booking_logs')) {
                BookingLog::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'action_type' => 'cancel',
                    'reason' => $request->reason,
                    'refund_amount' => $pemesanan->total,
                    'refund_status' => 'pending'
                ]);
            }

            // Proses refund jika sudah dibayar
            if ($pemesanan->status_pembayaran === 'paid') {
                $this->processRefund($pemesanan);
            }

            // Kirim email notifikasi
            $this->sendCancellationEmail($pemesanan, $request->reason);

            DB::commit();

            return back()->with('success', 'Booking berhasil dibatalkan. Refund akan diproses dalam 3-5 hari kerja.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal membatalkan booking: ' . $e->getMessage()]);
        }
    }

    public function rescheduleBooking(Request $request, $id)
    {
        $user = Auth::guard('pelanggan')->user();
        $pemesanan = Pemesanan::where('id_pelanggan', $user->id_pelanggan)
            ->where('id_pemesanan', $id)
            ->firstOrFail();

        // Validasi H-1
        $bookingDate = Carbon::parse($pemesanan->tanggal_pemesanan);
        $now = Carbon::now();

        if ($bookingDate->diffInDays($now) < 1 || $bookingDate->isPast()) {
            return back()->withErrors(['error' => 'Reschedule hanya bisa dilakukan maksimal H-1']);
        }

        if ($pemesanan->reschedule_count >= 1) {
            return back()->withErrors(['error' => 'Maksimal 1x reschedule per booking']);
        }

        if ($pemesanan->status_pemesanan !== 'confirmed') {
            return back()->withErrors(['error' => 'Booking tidak dapat di-reschedule']);
        }

        $request->validate([
            'new_date' => 'required|date|after:today',
            'new_time' => 'required|date_format:H:i',
        ]);

        DB::beginTransaction();
        try {
            // Simpan data lama untuk log
            $oldDate = $pemesanan->tanggal_pemesanan;
            $oldTime = $pemesanan->waktu;

            // Update pemesanan
            $pemesanan->update([
                'tanggal_pemesanan' => $request->new_date,
                'waktu' => $request->new_time . ':00',
                'reschedule_count' => $pemesanan->reschedule_count + 1
            ]);

            // Update detail booking
            $pemesanan->bookeds()->update([
                'tanggal_booked' => $request->new_date,
                'waktu' => $request->new_time . ':00'
            ]);

            // Log reschedule - hanya jika tabel booking_logs ada
            if (Schema::hasTable('booking_logs')) {
                BookingLog::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'action_type' => 'reschedule',
                    'old_date' => $oldDate,
                    'old_time' => $oldTime,
                    'new_date' => $request->new_date,
                    'new_time' => $request->new_time . ':00'
                ]);
            }

            // Kirim email notifikasi
            $this->sendRescheduleEmail($pemesanan, $oldDate, $oldTime);

            DB::commit();

            return back()->with('success', 'Booking berhasil di-reschedule. Email konfirmasi telah dikirim.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Gagal reschedule booking: ' . $e->getMessage()]);
        }
    }

public function submitReview(Request $request, $id)
{
    $user = Auth::guard('pelanggan')->user();
    $pemesanan = Pemesanan::where('id_pelanggan', $user->id_pelanggan)
        ->where('id_pemesanan', $id)
        ->where('status_pemesanan', 'completed')
        ->firstOrFail();

    // Cek apakah sudah ada review - gunakan relasi review() singular
    if ($pemesanan->review()->exists()) {
        return back()->withErrors(['error' => 'Anda sudah memberikan review untuk booking ini']);
    }

    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'komentar' => 'nullable|string|max:1000' // Sesuai dengan model (ubah dari review_text ke komentar)
    ]);

    // Prepare review data dengan kolom yang sesuai model
    $reviewData = [
        'id_pemesanan' => $pemesanan->id_pemesanan,
        'id_pelanggan' => $user->id_pelanggan,
        'rating' => $request->rating,
        'komentar' => $request->komentar, // Sesuai dengan model
        'tanggal_review' => now(), // Set tanggal review saat submit
        // status akan otomatis ter-set ke 'pending' dari model default
    ];

    Review::create($reviewData);

    return back()->with('success', 'Terima kasih atas review Anda!');
}

public function profile()
{
    $user = Auth::guard('pelanggan')->user();
    return view('customer.profile', compact('user'));
}

public function updateProfile(Request $request)
{

    $user = Auth::guard('pelanggan')->user();

    $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'email' => 'required|email|unique:pelanggans,email,' . $user->id_pelanggan . ',id_pelanggan',
        'no_telepon' => 'required|string|max:20',
        'alamat' => 'nullable|string|max:500'
    ]);

    $user->update($request->only(['nama_lengkap', 'email', 'no_telepon', 'alamat']));

    return back()->with('success', 'Profil berhasil diperbarui');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $user = Auth::guard('pelanggan')->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password saat ini tidak benar']);
    }

    $user->update(['password' => Hash::make($request->new_password)]);

    return back()->with('success', 'Password berhasil diperbarui');
}

    // private function generateTimeSlots($startTime, $endTime, $intervalMinutes = 30)
    // {
    //     $slots = [];
    //     $current = Carbon::parse($startTime);
    //     $end = Carbon::parse($endTime);

    //     while ($current->lt($end)) {
    //         $slots[] = $current->format('H:i');
    //         $current->addMinutes($intervalMinutes);
    //     }

    //     return $slots;
    // }

private function processRefund($pemesanan, $fullRefund = true)
{
    $pembayaran = $pemesanan->pembayaran;

    if (!$pembayaran || !$pembayaran->order_id) {
        Log::error("No payment found for refund: " . $pemesanan->id_pemesanan);
        return false;
    }

    // Tentukan jumlah refund
    $refundAmount = $fullRefund ? $pembayaran->total_harga : ($pembayaran->total_harga * 0.8); // Contoh: 80% refund

    try {
        // Panggil Midtrans Refund API
        $midtransUrl = config('midtrans.is_production')
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';

        $response = Http::withBasicAuth(config('midtrans.server_key'), '')
                       ->post("{$midtransUrl}/v2/{$pembayaran->order_id}/refund", [
                           'refund_amount' => $refundAmount,
                           'reason' => $pemesanan->alasan_pembatalan ?? 'Customer cancellation'
                       ]);

        if ($response->successful()) {
            // Update status pembayaran
            $pembayaran->update([
                'status_pembayaran' => 'refunded',
                'notifikasi' => 'Refund berhasil diproses'
            ]);

            // Log refund jika tabel booking_logs ada
            if (Schema::hasTable('booking_logs')) {
                BookingLog::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'action_type' => 'refund',
                    'refund_amount' => $refundAmount,
                    'refund_status' => 'completed'
                ]);
            }

            Log::info("Refund successful for order: {$pembayaran->order_id}, amount: {$refundAmount}");
            return true;
        } else {
            Log::error("Midtrans refund failed: " . $response->body());

            // Update status jadi processing untuk manual handling
            $pembayaran->update([
                'status_pembayaran' => 'refund_pending',
                'notifikasi' => 'Refund sedang diproses manual'
            ]);

            return false;
        }
    } catch (\Exception $e) {
        Log::error("Refund error: " . $e->getMessage());

        // Set status untuk manual processing
        $pembayaran->update([
            'status_pembayaran' => 'refund_pending',
            'notifikasi' => 'Refund akan diproses manual'
        ]);

        return false;
    }
}

    private function sendBookingConfirmationEmail($pemesanan)
    {
        try {
            // Simpan log email hanya jika tabel ada
            if (Schema::hasTable('email_notifications')) {
                EmailNotification::create([
                    'id_pelanggan' => $pemesanan->id_pelanggan,
                    'email_type' => 'booking_confirmation',
                    'subject' => 'Konfirmasi Booking - Dewi Beauty Salon',
                    'body' => 'Booking confirmation details...',
                    'status' => 'sent'
                ]);
            }

            // Kirim email sebenarnya jika class Mail ada
            if (class_exists('\App\Mail\BookingNotificationMail')) {
                Mail::to($pemesanan->pelanggan->email)
                    ->send(new BookingNotificationMail($pemesanan, 'booking_confirmation'));
            }

        } catch (\Exception $e) {
            \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
        }
    }

    private function sendCancellationEmail($pemesanan, $reason)
    {
        try {
            // Simpan log email hanya jika tabel ada
            if (Schema::hasTable('email_notifications')) {
                EmailNotification::create([
                    'id_pelanggan' => $pemesanan->id_pelanggan,
                    'email_type' => 'cancellation',
                    'subject' => 'Pembatalan Booking - Dewi Beauty Salon',
                    'body' => "Booking Anda telah dibatalkan. Alasan: {$reason}",
                    'status' => 'sent'
                ]);
            }

            // Kirim email sebenarnya jika class Mail ada
            if (class_exists('\App\Mail\BookingNotificationMail')) {
                Mail::to($pemesanan->pelanggan->email)
                    ->send(new BookingNotificationMail($pemesanan, 'cancellation', ['reason' => $reason]));
            }

        } catch (\Exception $e) {
            \Log::error('Failed to send cancellation email: ' . $e->getMessage());
        }
    }

    private function sendRescheduleEmail($pemesanan, $oldDate, $oldTime)
    {
        try {
            // Simpan log email hanya jika tabel ada
            if (Schema::hasTable('email_notifications')) {
                EmailNotification::create([
                    'id_pelanggan' => $pemesanan->id_pelanggan,
                    'email_type' => 'reschedule',
                    'subject' => 'Perubahan Jadwal Booking - Dewi Beauty Salon',
                    'body' => "Jadwal booking Anda telah diubah dari {$oldDate} {$oldTime} ke {$pemesanan->tanggal_pemesanan} {$pemesanan->waktu}",
                    'status' => 'sent'
                ]);
            }

            // Kirim email sebenarnya jika class Mail ada
            if (class_exists('\App\Mail\BookingNotificationMail')) {
                Mail::to($pemesanan->pelanggan->email)
                    ->send(new BookingNotificationMail($pemesanan, 'reschedule', [
                        'old_date' => $oldDate,
                        'old_time' => $oldTime
                    ]));
            }

        } catch (\Exception $e) {
            \Log::error('Failed to send reschedule email: ' . $e->getMessage());
        }
    }

    /**
 * Tampilkan form create booking untuk customer
 */
public function createBooking(Request $request)
{
    // Ambil tanggal yang dipilih dari query string, default hari ini
    $selectedDate = $request->query('date', now()->format('Y-m-d'));

    // Layanan / perawatan
    $services = Perawatan::all();

    // Ambil semua shift lengkap dengan karyawannya
    $shifts = Shift::with('karyawans')->get();

    // Generate time slots
    $timeSlots = [];
    foreach ($shifts as $shift) {
        $allSlots = $this->generateTimeSlots($shift->start_time, $shift->end_time, 15);
        $timeSlots[$shift->nama_shift] = $allSlots;
    }

    return view('customer.booking.create', compact(
        'services',
        'shifts',
        'timeSlots',
        'selectedDate'
    ));
}



/**
 * Cek ketersediaan karyawan untuk waktu tertentu
 */
public function checkAvailability(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'start_time' => 'required|date_format:H:i',
        'duration' => 'required|integer|min:1',
    ]);

    $date = $request->date;
    $startTime = $request->start_time;
    $duration = $request->duration;

    // Hitung waktu selesai
    $endTime = Carbon::parse($startTime)->addMinutes($duration)->format('H:i');

    // Cari shift yang mencakup waktu ini
    $availableEmployees = $this->findAvailableEmployees($date, $startTime, $endTime);

    return response()->json([
        'available' => count($availableEmployees) > 0,
        'employees' => $availableEmployees,
        'slots_available' => count($availableEmployees),
        'debug' => [
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'duration' => $duration
        ]
    ]);
}

/**
 * Generate time slots dengan interval tertentu
 */
private function generateTimeSlots($startTime, $endTime, $intervalMinutes = 30)
{
    $slots = [];
    $current = Carbon::parse($startTime);
    $end = Carbon::parse($endTime);

    while ($current->lt($end)) {
        $slots[] = $current->format('H:i');
        $current->addMinutes($intervalMinutes);
    }

    return $slots;
}

/**
 * Cari karyawan yang tersedia untuk waktu tertentu
 */
private function findAvailableEmployees($date, $startTime, $endTime)
{
    // Konversi ke format dengan detik untuk query database
    $startTimeWithSeconds = $startTime . ':00';
    $endTimeWithSeconds = $endTime . ':00';

    // 1. Cari shift yang mencakup waktu yang diminta
    $shifts = Shift::where('start_time', '<=', $startTimeWithSeconds)
                  ->where('end_time', '>=', $endTimeWithSeconds)
                  ->with('karyawans')
                  ->get();

    $availableEmployees = [];

    foreach ($shifts as $shift) {
        foreach ($shift->karyawans as $karyawan) {
            // Cek apakah karyawan sudah ada booking yang bentrok
            $hasConflict = $this->checkEmployeeConflict($karyawan->id_karyawan, $date, $startTime, $endTime);

            if (!$hasConflict) {
                $availableEmployees[] = [
                    'id' => $karyawan->id_karyawan,
                    'name' => $karyawan->nama_lengkap,
                    'shift' => $shift->nama_shift
                ];
            }
        }
    }

    return $availableEmployees;
}

/**
 * Cek apakah karyawan memiliki konflik jadwal
 */
private function checkEmployeeConflict($karyawanId, $date, $startTime, $endTime)
{
    // Ambil semua pemesanan karyawan di tanggal tersebut yang sudah confirmed atau pending
    $existingBookings = Pemesanan::with('bookeds.perawatan')
                               ->where('id_karyawan', $karyawanId)
                               ->where('tanggal_pemesanan', $date)
                               ->whereIn('status_pemesanan', ['confirmed', 'pending'])
                               ->get();

    // Jika tidak ada booking sama sekali, berarti tidak ada konflik
    if ($existingBookings->isEmpty()) {
        return false;
    }

    foreach ($existingBookings as $booking) {
        // Hitung durasi total dari semua layanan dalam booking ini
        $totalDuration = 0;
        foreach ($booking->bookeds as $booked) {
            if ($booked->perawatan) {
                $totalDuration += $booked->perawatan->waktu ?? 60;
            } else {
                $totalDuration += 60;
            }
        }

        // Hitung waktu selesai booking yang ada
        $bookingStart = Carbon::parse($booking->waktu);
        $bookingEnd = $bookingStart->copy()->addMinutes($totalDuration);

        $newStart = Carbon::parse($startTime);
        $newEnd = Carbon::parse($endTime);

        // Cek apakah ada overlap
        if ($this->hasTimeOverlap($newStart, $newEnd, $bookingStart, $bookingEnd)) {
            return true; // Ada konflik
        }
    }

    return false; // Tidak ada konflik
}

/**
 * Cek apakah dua rentang waktu overlap
 */
private function hasTimeOverlap($start1, $end1, $start2, $end2)
{
    return $start1->lt($end2) && $end1->gt($start2);
}
}
