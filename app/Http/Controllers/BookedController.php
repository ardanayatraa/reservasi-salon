<?php

namespace App\Http\Controllers;

use App\Models\Booked;
use App\Models\Pemesanan;
use App\Models\Perawatan;
use Illuminate\Http\Request;

class BookedController extends Controller
{
    public function index()
    {
        $bookeds = Booked::with(['pemesanan', 'perawatan'])->get();
        return view('booked.index', compact('bookeds'));
    }

    public function create()
    {
        $pemesanan = Pemesanan::all();
        $perawatan = Perawatan::all();
        return view('booked.create', compact('pemesanan', 'perawatan'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'id_pemesanan'   => 'required|exists:pemesanans,id_pemesanan',
            'id_perawatan'   => 'required|exists:perawatans,id_perawatan',
            'tanggal_booked' => 'required|date',
            'waktu'          => 'required|string',
        ]);

        Booked::create($v);

        return redirect()->route('booked.index')
                         ->with('success', 'Booked berhasil ditambahkan');
    }

    public function show(Booked $booked)
    {
        $booked->load(['pemesanan', 'perawatan']);
        return view('booked.show', compact('booked'));
    }

    public function edit(Booked $booked)
    {
        $pemesanan = Pemesanan::all();
        $perawatan = Perawatan::all();
        return view('booked.edit', compact('booked', 'pemesanan', 'perawatan'));
    }

    public function update(Request $request, Booked $booked)
    {
        $v = $request->validate([
            'id_pemesanan'   => 'required|exists:pemesanans,id_pemesanan',
            'id_perawatan'   => 'required|exists:perawatans,id_perawatan',
            'tanggal_booked' => 'required|date',
            'waktu'          => 'required|string',
        ]);

        $booked->update($v);

        return redirect()->route('booked.index')
                         ->with('success', 'Booked berhasil diupdate');
    }

    public function destroy(Booked $booked)
    {
        $booked->delete();
        return redirect()->route('booked.index')
                         ->with('success', 'Booked berhasil dihapus');
    }
}
