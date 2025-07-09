<?php

namespace App\Http\Livewire\Table;

use App\Models\Perawatan as PerawatanModel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

class PerawatanTable extends LivewireDatatable
{
    public function builder()
    {
        return PerawatanModel::query();
    }

    public function columns()
    {
        return [

            Column::callback(['foto'], function ($foto) {
    return view('components.image', ['foto' => $foto]);
        })->label('Foto')->unsortable(),

            Column::name('nama_perawatan')
                ->label('Nama Perawatan')
                ->searchable(),

            Column::name('deskripsi')
                ->label('Deskripsi'),

            Column::name('waktu')
                ->label('Waktu'),

            Column::name('harga')
                ->label('Harga')
                ->searchable(),

            Column::callback(['id_perawatan'], function ($id) {
                return view('components.actions', [
                    'route' => 'perawatan',
                    'id'    => $id,
                ]);
            })->label('Aksi'),
        ];
    }
}
