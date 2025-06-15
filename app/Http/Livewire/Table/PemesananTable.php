<?php

namespace App\Http\Livewire\Table;

use App\Models\Pemesanan as PemesananModel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;

class PemesananTable extends LivewireDatatable
{
    public function builder()
    {
        return PemesananModel::with(['pelanggan', 'perawatan']);
    }

    public function columns()
    {
        return [
            Column::name('id_pemesanan')
                ->label('ID Pemesanan')
                ->defaultSort('desc'),

            Column::name('pelanggan.nama_lengkap')
                ->label('Pelanggan')
                ->searchable(),
            Column::name('karyawan.nama_lengkap')
                ->label('Karyawan')
                ->searchable(),

            Column::name('tanggal_pemesanan')
                ->label('Tanggal')
                ->searchable(),
            Column::name('waktu')
                ->label('Waktu')
                ->searchable(),

            Column::name('status_pemesanan')
                ->label('Status'),

            Column::callback(['id_pemesanan'], function ($id) {
                return view('components.actions', [
                    'route' => 'pemesanan',
                    'id'    => $id,
                ]);
            })->label('Aksi'),
        ];
    }
}
