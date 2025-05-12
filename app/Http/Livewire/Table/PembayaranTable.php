<?php

namespace App\Http\Livewire\Table;

use App\Models\Pembayaran as PembayaranModel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

class PembayaranTable extends LivewireDatatable
{
    public function builder()
    {
        return PembayaranModel::with('pemesanan');
    }

    public function columns()
    {
        return [
            Column::name('id_pembayaran')
                ->label('ID Pembayaran')
                ->defaultSort('desc'),

            Column::name('pemesanan.id_pemesanan')
                ->label('Pemesanan')
                ->searchable(),

            Column::name('tanggal_pembayaran')
                ->label('Tanggal')
                ->searchable(),

            Column::name('total_harga')
                ->label('Total'),

            Column::name('status_pembayaran')
                ->label('Status'),

            Column::callback(['id_pembayaran'], function ($id) {
                return view('components.actions', [
                    'route' => 'pembayaran',
                    'id'    => $id,
                ]);
            })->label('Aksi'),
        ];
    }
}
