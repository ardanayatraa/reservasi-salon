<?php

namespace App\Http\Livewire\Table;

use App\Models\Pemesanan as PemesananModel;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use Illuminate\Support\Facades\DB;

class PemesananTable extends LivewireDatatable
{
    public $model = PemesananModel::class;
    public $statuses = [
        'pending' => 'Menunggu',
        'confirmed' => 'Dikonfirmasi',
        'in_progress' => 'Sedang Berlangsung',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
        'cancelled_by_salon' => 'Dibatalkan oleh Salon',
        'no_show' => 'Tidak Hadir',
        'resheduled' => 'Dijadwal Ulang',
    ];

    protected $listeners = ['changeStatus' => 'updateStatus'];


    public function builder()
    {
        return PemesananModel::with(['pelanggan', 'karyawan']);
    }

    public function columns()
    {
        return [
            Column::name('id_pemesanan')
                ->label('ID Pemesanan')
                ->defaultSort('desc'),

            Column::callback(['id_pemesanan'], function ($id) {
                $pemesanan = \App\Models\Pemesanan::with('bookeds.perawatan')->find($id);

                if (!$pemesanan || $pemesanan->bookeds->isEmpty()) {
                    return '<span class="text-gray-400 italic text-sm">Tidak ada</span>';
                }

                return $pemesanan->bookeds->map(function ($booked) {
                    $nama = $booked->perawatan->nama_perawatan ?? 'Perawatan Tidak Ditemukan';
                    return '<span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded-full mr-1 mb-1">'
                        . e($nama) . '</span>';
                })->implode(' ');
            })->label('Perawatan')->unsortable()->exportCallback(function () {
                return '-'; // atau gabungkan perawatan jika ingin ekspor
            }),


            Column::name('pelanggan.nama_lengkap')
                ->label('Pelanggan')
                ->searchable(),

            Column::name('karyawan.nama_lengkap')
                ->label('Karyawan')
                ->searchable(),

            Column::name('tanggal_pemesanan')
                ->label('Tanggal')
                ->searchable(),

         Column::callback(['waktu'], function ($id) {
    $pemesanan = \App\Models\Pemesanan::with('bookeds.perawatan')->find($id);

    if (!$pemesanan) return '-';

    $startTime = \Carbon\Carbon::parse($pemesanan->waktu);

    $totalDuration = $pemesanan->bookeds->sum(function ($booked) {
        return $booked->perawatan->durasi ?? 0;
    });

    $endTime = $startTime->copy()->addMinutes($totalDuration);

    return $startTime->format('H:i') . ' - ' . $endTime->format('H:i');
})->label('Rentang Waktu')->unsortable(),


            Column::callback(['id_pemesanan', 'status_pemesanan'], function ($id, $status) {
                return view('components.status-dropdown', [
                    'id' => $id,
                    'current' => $status,
                    'statuses' => $this->statuses,
                ]);
            })->label('Status'),

            // Column::callback(['id_pemesanan'], function ($id) {
            //     return view('components.actions', [
            //         'route' => 'pemesanan',
            //         'id'    => $id,
            //     ]);
            // })->label('Aksi'),
        ];
    }

    public function updateStatus($id, $newStatus)
    {
        $pemesanan = PemesananModel::find($id);
        if ($pemesanan) {
            $pemesanan->status_pemesanan = $newStatus;
            $pemesanan->save();
        }
    }
}
