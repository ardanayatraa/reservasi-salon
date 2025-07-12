<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Reschedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RescheduleController extends Controller
{
    public function show($pemesananId)
    {
        $pemesanan = Pemesanan::with(['bookeds.perawatan', 'reschedules'])
                             ->where('id_pemesanan', $pemesananId)
                             ->where('id_pelanggan', Auth::id())
                             ->firstOrFail();

        // Cek apakah bisa reschedule
        $canReschedule = $this->canReschedule($pemesanan);

        if (!$canReschedule['allowed']) {
            return redirect()->back()->with('error', $canReschedule['reason']);
        }

        // Data untuk form reschedule (shifts, time slots, etc.)
        $shifts = \App\Models\Shift::with('karyawans')->get();
        $timeSlots = $this->generateTimeSlots($shifts);

        return view('reschedule.form', compact('pemesanan', 'shifts', 'timeSlots'));
    }

    public function store(Request $request, $pemesananId)
    {
        $request->validate([
            'new_date' => 'required|date|after:today',
            'new_time' => 'required|date_format:H:i',
            'alasan_reschedule' => 'nullable|string|max:500'
        ]);

        $pemesanan = Pemesanan::where('id_pemesanan', $pemesananId)
                             ->where('id_pelanggan', Auth::id())
                             ->firstOrFail();

        $canReschedule = $this->canReschedule($pemesanan);

        if (!$canReschedule['allowed']) {
            return redirect()->back()->with('error', $canReschedule['reason']);
        }

        // Cek ketersediaan waktu baru
        $isAvailable = $this->checkTimeAvailability(
            $request->new_date,
            $request->new_time,
            $pemesanan->bookeds->sum(fn($b) => $b->perawatan->waktu ?? 60)
        );

        if (!$isAvailable) {
            return redirect()->back()->with('error', 'Waktu yang dipilih tidak tersedia.');
        }

        // Hitung biaya tambahan jika ada
        $additionalCost = $this->calculateRescheduleCost($pemesanan, $request->new_date, $request->new_time);

        // Simpan data reschedule
        $reschedule = Reschedule::create([
            'id_pemesanan' => $pemesananId,
            'id_pelanggan' => Auth::id(),
            'tanggal_lama' => $pemesanan->tanggal_pemesanan,
            'waktu_lama' => $pemesanan->waktu,
            'tanggal_baru' => $request->new_date,
            'waktu_baru' => $request->new_time . ':00',
            'biaya_tambahan' => $additionalCost,
            'alasan' => $request->alasan_reschedule,
            'status' => $additionalCost > 0 ? 'pending_payment' : 'confirmed'
        ]);

        if ($additionalCost > 0) {
            // Redirect ke pembayaran tambahan
            return $this->processAdditionalPayment($reschedule);
        } else {
            // Update pemesanan langsung
            $this->applyReschedule($reschedule);
            return redirect()->route('pelanggan.dashboard')
                            ->with('success', 'Reschedule berhasil dikonfirmasi.');
        }
    }

    private function canReschedule($pemesanan)
    {
        // Cek jumlah reschedule yang sudah dilakukan
        $rescheduleCount = $pemesanan->reschedules()->where('status', 'confirmed')->count();

        if ($rescheduleCount >= 1) {
            return [
                'allowed' => false,
                'reason' => 'Maksimal 1x reschedule per booking.'
            ];
        }

        // Cek waktu (H-1)
        $bookingDate = Carbon::parse($pemesanan->tanggal_pemesanan);
        $now = Carbon::now();

        if ($bookingDate->isToday() || $bookingDate->isPast()) {
            return [
                'allowed' => false,
                'reason' => 'Tidak bisa reschedule di hari H atau yang sudah lewat.'
            ];
        }

        if ($bookingDate->diffInDays($now) < 1) {
            return [
                'allowed' => false,
                'reason' => 'Reschedule hanya bisa dilakukan maksimal H-1.'
            ];
        }

        // Cek status
        if (in_array($pemesanan->status_pemesanan, ['completed', 'in_progress', 'cancelled'])) {
            return [
                'allowed' => false,
                'reason' => 'Tidak bisa reschedule booking dengan status ini.'
            ];
        }

        return ['allowed' => true, 'reason' => ''];
    }

    private function calculateRescheduleCost($pemesanan, $newDate, $newTime)
    {
        // Implementasi kalkulasi biaya tambahan
        // Contoh: biaya tambahan jika reschedule ke weekend atau jam premium

        $newDateTime = Carbon::parse($newDate . ' ' . $newTime);
        $additionalCost = 0;

        // Biaya tambahan weekend (Sabtu-Minggu)
        if ($newDateTime->isWeekend()) {
            $additionalCost += 50000; // Rp 50,000
        }

        // Biaya tambahan jam premium (17:00-20:00)
        $hour = $newDateTime->hour;
        if ($hour >= 17 && $hour < 20) {
            $additionalCost += 25000; // Rp 25,000
        }

        return $additionalCost;
    }

    private function processAdditionalPayment($reschedule)
    {
        // Setup Midtrans untuk pembayaran tambahan
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'RESCHEDULE-' . $reschedule->id_reschedule . '-' . time(),
                'gross_amount' => $reschedule->biaya_tambahan,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->nama_lengkap,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->no_telepon,
            ],
            'item_details' => [[
                'id' => 'reschedule-fee',
                'price' => $reschedule->biaya_tambahan,
                'quantity' => 1,
                'name' => 'Biaya Reschedule Booking #' . $reschedule->id_pemesanan,
            ]],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $reschedule->update(['snap_token' => $snapToken]);

            return redirect()->away(\Midtrans\Snap::getSnapUrl($snapToken));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    private function applyReschedule($reschedule)
    {
        // Update pemesanan dengan jadwal baru
        $reschedule->pemesanan->update([
            'tanggal_pemesanan' => $reschedule->tanggal_baru,
            'waktu' => $reschedule->waktu_baru
        ]);

        // Update status reschedule
        $reschedule->update([
            'status' => 'confirmed',
            'confirmed_at' => now()
        ]);

        // Update booked records
        $reschedule->pemesanan->bookeds()->update([
            'tanggal_booked' => $reschedule->tanggal_baru,
            'waktu' => $reschedule->waktu_baru
        ]);
    }

    private function checkTimeAvailability($date, $time, $duration)
    {
        // Implementasi pengecekan ketersediaan waktu
        // Sama seperti di BookingController
        $bookingController = new \App\Http\Controllers\BookingController();
        $endTime = Carbon::parse($time)->addMinutes($duration)->format('H:i');

        $availableEmployees = $bookingController->findAvailableEmployees($date, $time, $endTime);

        return count($availableEmployees) > 0;
    }

    private function generateTimeSlots($shifts)
    {
        $timeSlots = [];
        foreach ($shifts as $shift) {
            $slots = [];
            $current = Carbon::parse($shift->start_time);
            $end = Carbon::parse($shift->end_time);

            while ($current->lt($end)) {
                $slots[] = $current->format('H:i');
                $current->addMinutes(30);
            }

            $timeSlots[$shift->nama_shift] = $slots;
        }
        return $timeSlots;
    }
}
