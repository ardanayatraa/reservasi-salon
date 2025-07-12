<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use App\Models\CancelRefund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CancelRefundController extends Controller
{
    public function cancelBooking(Request $request, $pemesananId)
    {
        $request->validate([
            'alasan_pembatalan' => 'nullable|string|max:500'
        ]);

        $pemesanan = Pemesanan::with(['pembayaran'])
                             ->where('id_pemesanan', $pemesananId)
                             ->where('id_pelanggan', Auth::id())
                             ->firstOrFail();

        // Cek apakah bisa dibatalkan berdasarkan aturan bisnis
        $canCancel = $this->canCancelBooking($pemesanan);

        if (!$canCancel['allowed']) {
            return redirect()->back()->with('error', $canCancel['reason']);
        }

        // Update status pemesanan
        $pemesanan->update([
            'status_pemesanan' => 'cancelled',
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        // Jika sudah dibayar, proses refund
        if ($pemesanan->status_pembayaran === 'paid') {
            $this->processRefund($pemesanan);
        }

        return redirect()->route('pelanggan.dashboard')
                        ->with('success', 'Booking berhasil dibatalkan. Refund akan diproses dalam 1-3 hari kerja.');
    }

    public function adminCancelBooking(Request $request, $pemesananId)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500'
        ]);

        $pemesanan = Pemesanan::with(['pembayaran'])->findOrFail($pemesananId);

        // Admin bisa cancel kapan saja
        $pemesanan->update([
            'status_pemesanan' => 'cancelled_by_salon',
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        // Otomatis refund full jika sudah dibayar
        if ($pemesanan->status_pembayaran === 'paid') {
            $this->processRefund($pemesanan, true); // true = full refund
        }

        return redirect()->back()
                        ->with('success', 'Booking berhasil dibatalkan. Refund full akan diproses otomatis.');
    }

    private function canCancelBooking($pemesanan)
    {
        $bookingDate = Carbon::parse($pemesanan->tanggal_pemesanan);
        $now = Carbon::now();

        // Cek apakah sudah H atau sudah lewat
        if ($bookingDate->isToday() || $bookingDate->isPast()) {
            return [
                'allowed' => false,
                'reason' => 'Tidak bisa membatalkan booking di hari H atau yang sudah lewat.'
            ];
        }

        // Cek apakah masih H-1
        if ($bookingDate->diffInDays($now) < 1) {
            return [
                'allowed' => false,
                'reason' => 'Pembatalan hanya bisa dilakukan maksimal H-1 (1 hari sebelum layanan).'
            ];
        }

        // Cek status
        if (in_array($pemesanan->status_pemesanan, ['completed', 'in_progress'])) {
            return [
                'allowed' => false,
                'reason' => 'Tidak bisa membatalkan booking yang sudah berjalan atau selesai.'
            ];
        }

        return ['allowed' => true, 'reason' => ''];
    }

    private function processRefund($pemesanan, $fullRefund = false)
    {
        $pembayaran = $pemesanan->pembayaran;

        if (!$pembayaran || !$pembayaran->snap_token) {
            Log::error("No payment found for refund: " . $pemesanan->id_pemesanan);
            return false;
        }

        // Tentukan jumlah refund
        $refundAmount = $fullRefund ? $pembayaran->total_harga : $this->calculateRefundAmount($pemesanan);

        try {
            // Panggil Midtrans Refund API
            $response = Http::withBasicAuth(config('midtrans.server_key'), '')
                           ->post("https://api.sandbox.midtrans.com/v2/{$pembayaran->order_id}/refund", [
                               'refund_amount' => $refundAmount,
                               'reason' => $pemesanan->alasan_pembatalan ?? 'Customer cancellation'
                           ]);

            if ($response->successful()) {
                // Update status pembayaran
                $pembayaran->update([
                    'status_pembayaran' => 'refunded',
                    'refund_amount' => $refundAmount,
                    'refund_date' => now(),
                    'notifikasi' => 'Refund berhasil diproses'
                ]);

                // Catat di tabel cancel_refunds
                CancelRefund::create([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'id_pelanggan' => $pemesanan->id_pelanggan,
                    'type' => 'cancel',
                    'refund_amount' => $refundAmount,
                    'status' => 'processed',
                    'processed_at' => now(),
                    'alasan' => $pemesanan->alasan_pembatalan
                ]);

                return true;
            } else {
                Log::error("Midtrans refund failed: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Refund error: " . $e->getMessage());
            return false;
        }
    }

    private function calculateRefundAmount($pemesanan)
    {
        // Implementasi kalkulasi refund berdasarkan kebijakan
        // Misalnya: refund 100% jika H-2 atau lebih, 50% jika H-1
        $bookingDate = Carbon::parse($pemesanan->tanggal_pemesanan);
        $now = Carbon::now();
        $daysUntilBooking = $now->diffInDays($bookingDate);

        if ($daysUntilBooking >= 2) {
            return $pemesanan->total; // 100% refund
        } else {
            return $pemesanan->total * 0.5; // 50% refund
        }
    }
}
