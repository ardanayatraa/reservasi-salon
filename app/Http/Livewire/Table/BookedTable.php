<?php

namespace App\Http\Livewire\Table;

use App\Models\Booked as BookedModel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

class BookedTable extends LivewireDatatable
{
    public function builder()
    {
        return BookedModel::with(['pemesanan', 'perawatan']);
    }

    public function columns()
    {
        return [
            Column::name('id_booked')
                ->label('ID Booked')
                ->defaultSort('desc'),

            Column::name('pemesanan.id_pemesanan')
                ->label('Pemesanan')
                ->searchable(),

            Column::name('perawatan.nama_perawatan')
                ->label('Perawatan')
                ->searchable(),

            Column::name('tanggal_booked')
                ->label('Tanggal'),

            Column::callback(['id_booked'], function ($id) {
                return view('components.actions', [
                    'route' => 'booked',
                    'id'    => $id,
                ]);
            })->label('Aksi'),
        ];
    }
}
