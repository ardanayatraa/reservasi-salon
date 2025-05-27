<?php

namespace App\Http\Controllers;

use App\Models\Booked;
use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use App\Models\Perawatan;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
         * Tampilkan landing page dengan daftar Perawatan.
         */
        public function index(Request $request)
        {

            // Ambil tanggal yang dipilih dari query string, default hari ini
            $selectedDate = $request->query('date', now()->toDateString());

            // 1. Layanan / perawatan
            $services = Perawatan::all();

            // 2. Ambil semua shift lengkap dengan karyawannya
            $shifts = Shift::with('karyawans')->get();

            // 3. Generate dan filter time slots
            $timeSlots = [];
            $today     = now()->toDateString();

            foreach ($shifts as $shift) {
                $allSlots = $this->generateTimeSlots($shift->start_time, $shift->end_time, 15);

                $filtered = collect($allSlots)->filter(function ($slot) use ($today, $selectedDate) {
                    // Jika tanggal yang dipilih bukan hari ini, tampilkan semua slot
                    if ($selectedDate !== $today) {
                        return true;
                    }
                    // Jika hari ini: buang slot yang sudah lewat
                    $slotTime = Carbon::createFromFormat('H:i', $slot)
                                    ->setDate(now()->year, now()->month, now()->day);
                    return $slotTime->greaterThanOrEqualTo(now());
                })->values()->all();

                $timeSlots[$shift->nama_shift] = $filtered;
            }

            // 4. User pelanggan (jika sudah login)
            $user = Auth::guard('pelanggan')->user();

            // 5. Riwayat berdasarkan query email
            $email     = $request->query('email');
            $histories = null;

            if ($email) {
                $pel = Pelanggan::where('email', $email)->first();

                if ($pel) {
                    $histories = Pemesanan::with(['bookeds.perawatan'])
                        ->where('id_pelanggan', $pel->id_pelanggan)
                        ->orderByDesc('tanggal_pemesanan')
                        ->paginate(10)
                        ->appends(['email' => $email, 'date' => $selectedDate]);
                } else {
                    $histories = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
                }
            }

            // 6. Return view dengan compact termasuk selectedDate
            return view('landing-page', compact(
                'services',
                'shifts',
                'timeSlots',
                'user',
                'email',
                'histories',
                'selectedDate'
            ));
        }


    /**
     * Generate time slots dengan interval tertentu
     */
    private function generateTimeSlots($startTime, $endTime, $intervalMinutes = 15)
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
     * Cek ketersediaan karyawan untuk waktu tertentu
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:1', // dalam menit
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
                    $totalDuration += $booked->perawatan->waktu ?? 60; // default 60 menit jika tidak ada
                } else {
                    $totalDuration += 60; // default jika perawatan tidak ditemukan
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

    /**
     * Proses booking dengan alokasi karyawan otomatis
     */
    public function bookService(Request $request)
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
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada karyawan yang tersedia untuk waktu tersebut.'
                ], 400);
            }
            return redirect()->back()->withErrors(['booking' => 'Tidak ada karyawan yang tersedia.']);
        }

        // 4) Pilih karyawan pertama yang tersedia (bisa dibuat lebih pintar)
        $selectedEmployee = $availableEmployees[0];

        // 5) Buat header pemesanan
        $pemesanan = Pemesanan::create([
            'id_user' => $user->id_pelanggan,
            'id_pelanggan' => $user->id_pelanggan,
            'id_karyawan' => $selectedEmployee['id'], // Assign karyawan
            'tanggal_pemesanan' => $request->booking_date,
            'waktu' => $startTime . ':00', // Tambah detik untuk konsistensi
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
                'waktu' => $startTime . ':00', // Tambah detik untuk konsistensi
            ]);
        }

        // 7) Buat pembayaran
        $pembayaran = Pembayaran::create([
            'id_pemesanan' => $pemesanan->id_pemesanan,
            'total_harga' => $totalPrice,
            'metode_pembayaran' => $request->payment_method,
            'status_pembayaran' => 'unpaid',
            'snap_token' => '',
            'notifikasi' => 'Menunggu pembayaran',
        ]);

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
                'order_id' => $pembayaran->id_pembayaran,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $user->nama_lengkap,
                'email' => $user->email,
                'phone' => $user->no_telepon,
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $pembayaran->update(['snap_token' => $snapToken]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'snap_token' => $snapToken,
                    'order_id' => $pembayaran->id_pembayaran,
                    'assigned_employee' => $selectedEmployee['name']
                ]);
            }

            return redirect()->away(\Midtrans\Snap::getSnapUrl($snapToken));

        } catch (\Exception $e) {
            Log::error("Midtrans Error: {$e->getMessage()}");
            $msg = 'Gagal memproses pembayaran: ' . $e->getMessage();

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 500);
            }

            return redirect()->back()->withErrors(['payment' => $msg]);
        }
    }

    public function finish(Request $request)
    {

        $orderId = $request->query('order_id');
        $status = $request->query('status');

        Log::info("Frontend callback: order_id=$orderId, status=$status");

        $pembayaran = Pembayaran::find($orderId);
        if (!$pembayaran) {
            return redirect('/')->with('error', 'Pembayaran tidak ditemukan.');
        }

        if ($status === 'success') {
            $pembayaran->update([
                'status_pembayaran' => 'paid',
                   'tanggal_pembayaran' => now(),
                'notifikasi' => 'Pembayaran sukses',
            ]);
            $pembayaran->pemesanan->update([
                'status_pemesanan' => 'confirmed',
                'status_pembayaran' => 'paid',
            ]);
            $message = 'Pembayaran berhasil. Terima kasih!';
        } elseif ($status === 'pending') {
            $pembayaran->update([
                'status_pembayaran' => 'pending',
                'notifikasi' => 'Pembayaran menunggu konfirmasi',
            ]);
            $message = 'Pembayaran masih pending.';
        } else {
            $pembayaran->update([
                'status_pembayaran' => 'unpaid',
                'notifikasi' => 'Pembayaran gagal atau dibatalkan',
            ]);
            $message = 'Pembayaran gagal atau dibatalkan.';
        }


        return redirect('/')->with('status_message', $message);
    }

    /**
     * Debug method untuk cek availability
     */
    public function debugAvailability(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $startTime = $request->start_time ?? '09:00';
        $duration = $request->duration ?? 60;

        $endTime = Carbon::parse($startTime)->addMinutes($duration)->format('H:i');

        // Debug info
        $debug = [
            'input' => [
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'duration' => $duration
            ],
            'shifts' => [],
            'employees' => [],
            'bookings' => []
        ];

        // Konversi ke format dengan detik untuk query database
        $startTimeWithSeconds = $startTime . ':00';
        $endTimeWithSeconds = $endTime . ':00';

        // Cek shifts
        $shifts = Shift::where('start_time', '<=', $startTimeWithSeconds)
                      ->where('end_time', '>=', $endTimeWithSeconds)
                      ->with('karyawans')
                      ->get();

        foreach ($shifts as $shift) {
            $shiftData = [
                'id' => $shift->id_shift,
                'name' => $shift->nama_shift,
                'start_time' => $shift->start_time,
                'end_time' => $shift->end_time,
                'employees' => []
            ];

            foreach ($shift->karyawans as $karyawan) {
                $bookings = Pemesanan::with('bookeds.perawatan')
                                   ->where('id_karyawan', $karyawan->id_karyawan)
                                   ->where('tanggal_pemesanan', $date)
                                   ->whereIn('status_pemesanan', ['confirmed', 'pending'])
                                   ->get();

                $hasConflict = $this->checkEmployeeConflict($karyawan->id_karyawan, $date, $startTime, $endTime);

                $employeeData = [
                    'id' => $karyawan->id_karyawan,
                    'name' => $karyawan->nama_lengkap,
                    'has_conflict' => $hasConflict,
                    'bookings_count' => $bookings->count(),
                    'bookings' => $bookings->map(function($booking) {
                        $totalDuration = $booking->bookeds->sum(function($booked) {
                            return $booked->perawatan->waktu ?? 60;
                        });

                        return [
                            'id' => $booking->id_pemesanan,
                            'time' => $booking->waktu,
                            'duration' => $totalDuration,
                            'end_time' => Carbon::parse($booking->waktu)->addMinutes($totalDuration)->format('H:i'),
                            'status' => $booking->status_pemesanan
                        ];
                    })
                ];

                $shiftData['employees'][] = $employeeData;
            }

            $debug['shifts'][] = $shiftData;
        }

        $availableEmployees = $this->findAvailableEmployees($date, $startTime, $endTime);
        $debug['available_employees'] = $availableEmployees;

        return response()->json($debug);
    }
}
