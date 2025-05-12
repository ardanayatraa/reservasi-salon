<?php

namespace App\Http\Livewire\Table;

use App\Models\Pelanggan as PelangganModel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

class PelangganTable extends LivewireDatatable
{
    public function builder()
    {
        return PelangganModel::query();
    }

    public function columns()
    {
        return [
            Column::name('nama_lengkap')
                ->label('Nama Lengkap')
                ->searchable(),

            Column::name('username')
                ->label('Username')
                ->searchable(),

            Column::name('email')
                ->label('Email')
                ->searchable(),

            Column::name('no_telepon')
                ->label('No. Telepon'),

            Column::callback(['id_pelanggan'], function ($id) {
                return view('components.actions', [
                    'route' => 'pelanggan',
                    'id'    => $id,
                ]);
            })->label('Aksi'),
        ];
    }
}
