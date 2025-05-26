<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Shift;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with('shift')->get();
        return view('karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        $shifts = Shift::all();
        return view('karyawan.create', compact('shifts'));
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'no_telepon'   => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
            'id_shift'     => 'required|exists:shifts,id_shift',
        ]);

        Karyawan::create($v);

        return redirect()->route('karyawan.index')
                         ->with('success', 'Karyawan berhasil dibuat');
    }

    public function show(Karyawan $karyawan)
    {
        $karyawan->load('shift');
        return view('karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $shifts = Shift::all();
        return view('karyawan.edit', compact('karyawan', 'shifts'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $v = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'no_telepon'   => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
            'id_shift'     => 'required|exists:shifts,id_shift',
        ]);

        $karyawan->update($v);

        return redirect()->route('karyawan.index')
                         ->with('success', 'Karyawan berhasil diupdate');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')
                         ->with('success', 'Karyawan berhasil dihapus');
    }
}
