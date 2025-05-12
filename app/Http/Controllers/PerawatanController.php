<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerawatanController extends Controller
{
    public function index()
    {
        $perawatans = Perawatan::all();
        return view('perawatan.index', compact('perawatans'));
    }

    public function create()
    {
        return view('perawatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perawatan' => 'required|string|max:255',
            'foto'           => 'nullable|image|max:2048',
            'deskripsi'      => 'nullable|string',
            'waktu'          => 'required|string|max:50',
            'harga'          => 'required|numeric',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')
                                         ->store('perawatan', 'public');
        }

        Perawatan::create($validated);

        return redirect()->route('perawatan.index')
                         ->with('success', 'Perawatan berhasil ditambahkan');
    }

    public function show(Perawatan $perawatan)
    {
        return view('perawatan.show', compact('perawatan'));
    }

    public function edit(Perawatan $perawatan)
    {
        return view('perawatan.edit', compact('perawatan'));
    }

    public function update(Request $request, Perawatan $perawatan)
    {
        $validated = $request->validate([
            'nama_perawatan' => 'required|string|max:255',
            'foto'           => 'nullable|image|max:2048',
            'deskripsi'      => 'nullable|string',
            'waktu'          => 'required|string|max:50',
            'harga'          => 'required|numeric',
        ]);

        if ($request->hasFile('foto')) {
            // hapus file lama jika ada
            if ($perawatan->foto) {
                Storage::disk('public')->delete($perawatan->foto);
            }
            $validated['foto'] = $request->file('foto')
                                         ->store('perawatan', 'public');
        }

        $perawatan->update($validated);

        return redirect()->route('perawatan.index')
                         ->with('success', 'Perawatan berhasil diupdate');
    }

    public function destroy(Perawatan $perawatan)
    {
        if ($perawatan->foto) {
            Storage::disk('public')->delete($perawatan->foto);
        }
        $perawatan->delete();

        return redirect()->route('perawatan.index')
                         ->with('success', 'Perawatan berhasil dihapus');
    }
}
