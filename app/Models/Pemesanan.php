<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'id_user',
        'id_pelanggan',
        'id_perawatan',
        'tanggal_pemesanan',
        'waktu',
        'jumlah_perawatan',
        'status_pemesanan',
        'total',
        'sub_total',
        'metode_pembayaran',
        'status_pembayaran',
        'token',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_user', 'id_admin');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function perawatan()
    {
        return $this->belongsTo(Perawatan::class, 'id_perawatan', 'id_perawatan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function bookeds()
    {
        return $this->hasMany(Booked::class, 'id_pemesanan', 'id_pemesanan');
    }
}
