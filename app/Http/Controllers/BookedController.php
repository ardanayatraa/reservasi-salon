<?php

namespace App\Http\Controllers;

use App\Models\Booked;
use App\Models\Pemesanan;
use App\Models\Perawatan;
use App\Models\Karyawan;
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
            'id_karyawan'    => 'nullable|exists:karyawans,id_karyawan',
        ]);

        // Check if employee is assigned and available
        if (isset($v['id_karyawan']) && $v['id_karyawan']) {
            $karyawan = Karyawan::find($v['id_karyawan']);

            // Check if the employee is available
            if ($karyawan && !$karyawan->isAvailable()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['id_karyawan' => 'Karyawan yang dipilih tidak tersedia. Silakan pilih karyawan lain.']);
            }

            // Set employee as unavailable
            if ($karyawan) {
                $karyawan->setUnavailable();
            }
        }

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
            'id_karyawan'    => 'nullable|exists:karyawans,id_karyawan',
        ]);

        // Check if employee is being changed
        $isEmployeeChanged = isset($v['id_karyawan']) && $booked->id_karyawan != $v['id_karyawan'];

        // If employee is being changed, check if the new employee is available
        if ($isEmployeeChanged && isset($v['id_karyawan']) && $v['id_karyawan']) {
            $karyawan = Karyawan::find($v['id_karyawan']);

            if ($karyawan && !$karyawan->isAvailable()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['id_karyawan' => 'Karyawan yang dipilih tidak tersedia. Silakan pilih karyawan lain.']);
            }
        }

        // Update the booking
        $booked->update($v);

        // If employee is changed, update availability status
        if ($isEmployeeChanged) {
            // If there was a previous employee, set them as available
            if ($booked->getOriginal('id_karyawan')) {
                $oldKaryawan = Karyawan::find($booked->getOriginal('id_karyawan'));
                if ($oldKaryawan) {
                    $oldKaryawan->setAvailable();
                }
            }

            // If there's a new employee, set them as unavailable
            if ($booked->id_karyawan) {
                $newKaryawan = Karyawan::find($booked->id_karyawan);
                if ($newKaryawan) {
                    $newKaryawan->setUnavailable();
                }
            }
        }

        return redirect()->route('booked.index')
                         ->with('success', 'Booked berhasil diupdate');
    }

    public function destroy(Booked $booked)
    {
        // If there's an employee assigned to this booking, set them as available
        if ($booked->id_karyawan) {
            $karyawan = Karyawan::find($booked->id_karyawan);
            if ($karyawan) {
                $karyawan->setAvailable();
            }
        }

        $booked->delete();
        return redirect()->route('booked.index')
                         ->with('success', 'Booked berhasil dihapus');
    }
}
