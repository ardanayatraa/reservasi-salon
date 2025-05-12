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
        $pemesanan = Pemesanan::with(['pelanggan', 'perawatan'])->get();
        return view('pemesanan.index', compact('pemesanan'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $perawatans = Perawatan::all();
        return view('pemesanan.create', compact('pelanggans', 'perawatans'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_user'           => 'required|exists:users,id',
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

        Pemesanan::create($v);

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
        return view('pemesanan.edit', compact('pemesanan', 'pelanggans', 'perawatans'));
    }

    public function update(Request $request, Pemesanan $pemesanan)
    {
        $v = $request->validate([
            'id_user'           => 'required|exists:users,id',
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

        $pemesanan->update($v);

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
