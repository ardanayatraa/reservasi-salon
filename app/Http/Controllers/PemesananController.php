<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Pelanggan;
use App\Models\Perawatan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanan = Pemesanan::with(['pelanggan'])->get();
        return view('pemesanan.index', compact('pemesanan'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $perawatans = Perawatan::all();
        return view('pemesanan.create', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([

            'id_pelanggan'      => 'required|exists:pelanggans,id_pelanggan',
            'id_perawatan'      => 'required|exists:perawatans,id_perawatan',
            'tanggal_pemesanan' => 'required|date',
            'waktu'             => 'required|string',
            'jumlah_perawatan'  => 'required|integer|min:1',
            'status_pemesanan'  => 'required|string',
            'metode_pembayaran' => 'nullable|string',
            'status_pembayaran' => 'nullable|string',
            'token'             => 'nullable|string',
        ]);

        // hitung subtotal & total
        $service = Perawatan::findOrFail($v['id_perawatan']);
        $sub = $service->harga * $v['jumlah_perawatan'];
        $v['sub_total'] = $sub;
        $v['total']     = $sub;

        // Set payment_deadline dan status
        $v['payment_deadline'] = now()->addMinutes(30);
        $v['status'] = 'menunggu pembayaran';

        // Create the reservation
        $pemesanan = Pemesanan::create($v);

        // If an employee is assigned, check availability and mark them as unavailable during the reservation time
        if (isset($v['id_karyawan']) && $v['id_karyawan']) {
            $karyawan = \App\Models\Karyawan::find($v['id_karyawan']);

            // Check if the employee is available
            if ($karyawan && !$karyawan->isAvailable()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['id_karyawan' => 'Karyawan yang dipilih tidak tersedia. Silakan pilih karyawan lain.']);
            }

            if ($karyawan) {
                $karyawan->setUnavailable();
            }
        }

        // Schedule a job to automatically cancel the reservation if payment is not made
        \App\Jobs\CancelUnpaidReservationJob::dispatch($pemesanan)
            ->delay($v['payment_deadline']);

        return redirect()->route('pemesanan.index')
                         ->with('success', 'Pemesanan berhasil dibuat');
    }

    public function show(Pemesanan $pemesanan)
    {
        $pemesanan->load(['pelanggan', 'perawatan']);
        return view('pemesanan.show', compact('pemesanan'));
    }

    public function edit(Pemesanan $pemesanan)
    {
        $pelanggans = Pelanggan::all();
        $perawatans = Perawatan::all();
        return view('pemesanan.edit', compact('pemesanan', 'pelanggans'));
    }

    public function update(Request $request, Pemesanan $pemesanan)
    {
        $v = $request->validate([

            'id_pelanggan'      => 'required|exists:pelanggans,id_pelanggan',
            'id_perawatan'      => 'required|exists:perawatans,id_perawatan',
            'tanggal_pemesanan' => 'required|date',
            'waktu'             => 'required|string',
            'jumlah_perawatan'  => 'required|integer|min:1',
            'status_pemesanan'  => 'required|string',
            'metode_pembayaran' => 'nullable|string',
            'status_pembayaran' => 'nullable|string',
            'token'             => 'nullable|string',
        ]);

        $service = Perawatan::findOrFail($v['id_perawatan']);
        $sub = $service->harga * $v['jumlah_perawatan'];
        $v['sub_total'] = $sub;
        $v['total']     = $sub;

        // Check if payment status is changing from pending to paid
        $isPaid = ($pemesanan->status_pembayaran === 'pending' && $v['status_pembayaran'] === 'paid');

        // Check if employee is being changed
        $isEmployeeChanged = isset($v['id_karyawan']) && $pemesanan->id_karyawan != $v['id_karyawan'];

        // If employee is being changed, check if the new employee is available
        if ($isEmployeeChanged && isset($v['id_karyawan']) && $v['id_karyawan']) {
            $karyawan = \App\Models\Karyawan::find($v['id_karyawan']);

            if ($karyawan && !$karyawan->isAvailable()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['id_karyawan' => 'Karyawan yang dipilih tidak tersedia. Silakan pilih karyawan lain.']);
            }
        }

        // Update the reservation
        $pemesanan->update($v);

        // If employee is changed, update availability status
        if ($isEmployeeChanged) {
            // If there was a previous employee, set them as available
            if ($pemesanan->getOriginal('id_karyawan')) {
                $oldKaryawan = \App\Models\Karyawan::find($pemesanan->getOriginal('id_karyawan'));
                if ($oldKaryawan) {
                    $oldKaryawan->setAvailable();
                }
            }

            // If there's a new employee, set them as unavailable
            if ($pemesanan->id_karyawan) {
                $newKaryawan = \App\Models\Karyawan::find($pemesanan->id_karyawan);
                if ($newKaryawan) {
                    $newKaryawan->setUnavailable();
                }
            }
        }

        // If payment status changed to paid, update employee status
        if ($isPaid && $pemesanan->id_karyawan) {
            // The reservation is now confirmed, so the employee remains unavailable
            // but we can cancel any scheduled cancellation job
            // This would be handled by the payment gateway callback in a real implementation
        }

        return redirect()->route('pemesanan.index')
                         ->with('success', 'Pemesanan berhasil diupdate');
    }

    public function destroy(Pemesanan $pemesanan)
    {
        $pemesanan->delete();
        return redirect()->route('pemesanan.index')
                         ->with('success', 'Pemesanan berhasil dihapus');
    }
}
