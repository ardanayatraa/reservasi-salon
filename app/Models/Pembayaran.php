<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_pemesanan',
        'tanggal_pembayaran',
        'total_harga',
        'status_pembayaran',
        'metode_pembayaran',
        'snap_token',
        'notifikasi',
        'order_id',
    ];

        protected $casts = [
                'tanggal_pembayaran' => 'date',
                    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
