<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    protected $primaryKey = 'id_perawatan';

    protected $fillable = [
        'nama_perawatan',
        'foto',
        'deskripsi',
        'waktu',
        'harga',
    ];

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_perawatan', 'id_perawatan');
    }

    public function bookeds()
    {
        return $this->hasMany(Booked::class, 'id_perawatan', 'id_perawatan');
    }
}
