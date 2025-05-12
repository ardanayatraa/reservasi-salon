<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booked extends Model
{
    protected $primaryKey = 'id_booked';

    protected $fillable = [
        'id_pemesanan',
        'id_perawatan',
        'tanggal_booked',
        'waktu',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function perawatan()
    {
        return $this->belongsTo(Perawatan::class, 'id_perawatan', 'id_perawatan');
    }
}
