<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawans';
    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'no_telepon',
        'alamat',
        'id_shift',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift', 'id_shift');
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_karyawan', 'id_karyawan');
    }
}
