<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Tampilkan semua shift.
     */
    public function index()
    {
        $shifts = Shift::all();
        return view('shift.index', compact('shifts'));
    }

    /**
     * Form untuk membuat shift baru.
     */
    public function create()
    {
        return view('shift.create');
    }

    /**
     * Simpan shift baru ke database.
     */
    public function store(Request $request)
    {
        $v = $request->validate([
            'nama_shift' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
        ]);

        Shift::create($v);

        return redirect()
            ->route('shift.index')
            ->with('success', 'Shift berhasil dibuat');
    }

    /**
     * Form untuk mengedit shift yang sudah ada.
     */
    public function edit(Shift $shift)
    {
        return view('shift.edit', compact('shift'));
    }

    /**
     * Update data shift di database.
     */
    public function update(Request $request, Shift $shift)
    {
        $v = $request->validate([
            'nama_shift' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
        ]);

        $shift->update($v);

        return redirect()
            ->route('shift.index')
            ->with('success', 'Shift berhasil diupdate');
    }

    /**
     * Hapus shift dari database.
     */
    public function destroy(Shift $shift)
    {
        $shift->delete();

        return redirect()
            ->route('shift.index')
            ->with('success', 'Shift berhasil dihapus');
    }
}
