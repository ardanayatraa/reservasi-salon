<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::all();
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:pelanggans,username',
            'password'     => 'required|string|min:8|confirmed',
            'email'        => 'required|email|unique:pelanggans,email',
            'no_telepon'   => 'nullable|string|max:20',
            'alamat'       => 'nullable|string|max:255',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        Pelanggan::create($validated);

        return redirect()->route('pelanggan.index')
                         ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function show(Pelanggan $pelanggan)
    {
        return view('pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:255|unique:pelanggans,username,'.$pelanggan->id_pelanggan.',id_pelanggan',
            'email'        => 'required|email|unique:pelanggans,email,'.$pelanggan->id_pelanggan.',id_pelanggan',
            'no_telepon'   => 'nullable|string|max:20',
            'alamat'       => 'nullable|string|max:255',
        ];
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pelanggan->update($validated);

        return redirect()->route('pelanggan.index')
                         ->with('success', 'Pelanggan berhasil diupdate');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')
                         ->with('success', 'Pelanggan berhasil dihapus');
    }
}
