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
            $validated['foto'] = $request->file('foto')->store('perawatan', 'public');
        }

        Perawatan::create($validated);

        return redirect()->route('perawatan.index')
                         ->with('success', 'Perawatan berhasil ditambahkan');
    }

    public function show($id)
    {
        $perawatan = Perawatan::findOrFail($id);
        return view('perawatan.show', compact('perawatan'));
    }

    public function edit($id)
    {
        $perawatan = Perawatan::findOrFail($id);
        return view('perawatan.edit', compact('perawatan'));
    }

    public function update(Request $request, $id)
{
    $perawatan = Perawatan::findOrFail($id);

    // Update field non-foto langsung dari request
    $perawatan->nama_perawatan = $request->nama_perawatan;
    $perawatan->deskripsi = $request->deskripsi;
    $perawatan->waktu = $request->waktu;
    $perawatan->harga = $request->harga;

    // Handle foto jika ada
    if ($request->hasFile('foto')) {
        // Simpan path foto lama
        $fotoLama = $perawatan->foto;

        // Upload foto baru
        $fotoPath = $request->file('foto')->store('perawatan', 'public');
        $perawatan->foto = $fotoPath;

        // Hapus foto lama setelah berhasil upload
        if ($fotoLama && Storage::disk('public')->exists($fotoLama)) {
            Storage::disk('public')->delete($fotoLama);
        }
    }
    // Jika tidak ada file foto, field foto tidak diubah

    // Simpan semua perubahan
    $perawatan->save();

    return redirect()->route('perawatan.index')
                     ->with('success', 'Perawatan berhasil diupdate');
}

    public function destroy($id)
    {
        $perawatan = Perawatan::findOrFail($id);

        if ($perawatan->foto && Storage::disk('public')->exists($perawatan->foto)) {
            Storage::disk('public')->delete($perawatan->foto);
        }

        $perawatan->delete();

        return redirect()->route('perawatan.index')
                         ->with('success', 'Perawatan berhasil dihapus');
    }
}
