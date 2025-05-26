<?php

namespace App\Http\Livewire\Table;

use App\Models\Shift as ShiftModel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

class ShiftTable extends LivewireDatatable
{
    public function builder()
    {
        return ShiftModel::query();
    }

    public function columns()
    {
        return [
            Column::name('id_shift')
                ->label('ID Shift')
                ->defaultSort('desc'),

            Column::name('nama_shift')
                ->label('Nama Shift')
                ->searchable(),

            Column::name('start_time')
                ->label('Waktu Mulai'),

            Column::name('end_time')
                ->label('Waktu Selesai'),

            Column::callback(['id_shift'], function ($id) {
                return view('components.actions', [
                    'route' => 'shift',
                    'id'    => $id,
                ]);
            })->label('Aksi'),
        ];
    }
}
