<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with('pemesanan')->get();
        return view('pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $pemesanan = Pemesanan::all();
        return view('pembayaran.create', compact('pemesanan'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_layanan'        => 'required|exists:perawatans,id_perawatan',
            'tanggal_pembayaran'=> 'required|date',
            'total_harga'       => 'required|numeric',
            'status_pembayaran' => 'required|string',
            'metode_pembayaran' => 'nullable|string',
            'snap_token'        => 'nullable|string',
            'notifikasi'        => 'nullable|string',
        ]);

        Pembayaran::create($v);

        return redirect()->route('pembayaran.index')
                         ->with('success', 'Pembayaran berhasil dibuat');
    }

    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load('pemesanan');
        return view('pembayaran.show', compact('pembayaran'));
    }

    public function edit(Pembayaran $pembayaran)
    {
        $pemesanan = Pemesanan::all();
        return view('pembayaran.edit', compact('pembayaran', 'pemesanan'));
    }

    public function update(Request $request, Pembayaran $pembayaran)
    {
        $v = $request->validate([
            'id_layanan'        => 'required|exists:perawatans,id_perawatan',
            'tanggal_pembayaran'=> 'required|date',
            'total_harga'       => 'required|numeric',
            'status_pembayaran' => 'required|string',
            'metode_pembayaran' => 'nullable|string',
            'snap_token'        => 'nullable|string',
            'notifikasi'        => 'nullable|string',
        ]);

        $pembayaran->update($v);

        return redirect()->route('pembayaran.index')
                         ->with('success', 'Pembayaran berhasil diupdate');
    }

    public function destroy(Pembayaran $pembayaran)
    {
        $pembayaran->delete();
        return redirect()->route('pembayaran.index')
                         ->with('success', 'Pembayaran berhasil dihapus');
    }
}
