<?php

namespace App\Http\Livewire\Table;

use App\Models\Karyawan as KaryawanModel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

class KaryawanTable extends LivewireDatatable
{
    public function builder()
    {
        return KaryawanModel::with('shift');
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

            Column::callback(['id_karyawan'], function ($id) {
                return view('components.actions', [
                    'route' => 'karyawan',
                    'id'    => $id,
                ]);
            })->label('Aksi'),
        ];
    }
}
