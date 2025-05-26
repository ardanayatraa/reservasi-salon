<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanans';
    protected $primaryKey = 'id_pemesanan';

    protected $fillable = [
        'id_user',
        'id_pelanggan',
        'id_karyawan',
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
    protected $casts = [
        'tanggal_pemesanan' => 'datetime',

    ];

    // Relasi ke Admin yang menginput
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_user', 'id_admin');
    }

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Relasi ke Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    // Relasi ke Booked (detail perawatan yang dipesan)
    public function bookeds()
    {
        return $this->hasMany(Booked::class, 'id_pemesanan', 'id_pemesanan');
    }

    // Relasi pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }
}
