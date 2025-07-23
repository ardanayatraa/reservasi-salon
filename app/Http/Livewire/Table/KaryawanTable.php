<?php

namespace App\Http\Livewire\Table;

use App\Models\Karyawan as KaryawanModel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Illuminate\Support\Facades\Log;

class KaryawanTable extends LivewireDatatable
{
        public function builder()
        {
            return KaryawanModel::query()
                ->leftJoin('shifts', 'shifts.id_shift', '=', 'karyawans.id_shift')
                ->select('karyawans.*', 'shifts.nama_shift as shift_nama_shift');
        }


    public function columns()
    {
        return [
            Column::name('id_karyawan')
                ->label('ID')
                ->defaultSort('desc'),

            Column::name('nama_lengkap')
                ->label('Nama Lengkap')
                ->searchable(),

            Column::name('email')
                ->label('Email')
                ->searchable(),

            Column::name('no_telepon')
                ->label('No. Telepon'),

            Column::name('shift.nama_shift')
                ->label('Shift')
                ->searchable(),

            Column::callback(['id_karyawan', 'availability_status'], function ($id, $status) {
                return view('components.availability-toggle', [
                    'id' => $id,
                    'status' => $status,
                ]);
            })->label('Status Ketersediaan'),

            Column::callback(['id_karyawan'], function ($id) {
                return view('components.actions', [
                    'route' => 'karyawan',
                    'id'    => $id,
                ]);
            })->label('Aksi'),
        ];
    }

    public function toggleAvailability($id)
    {
        try {
            $karyawan = KaryawanModel::findOrFail($id);
            $newStatus = $karyawan->availability_status === 'available' ? 'unavailable' : 'available';
            $karyawan->update(['availability_status' => $newStatus]);

            $this->emit('refreshLivewireDatatable');

            // Log the status change
            Log::info("Karyawan #{$id} availability status changed to {$newStatus}");
        } catch (\Exception $e) {
            Log::error("Error toggling karyawan availability: {$e->getMessage()}");
        }
    }
}
