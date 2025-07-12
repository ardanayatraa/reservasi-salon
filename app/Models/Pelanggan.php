<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pelanggan extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'email',
        'no_telepon',
        'alamat',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_pelanggan', 'id_pelanggan');
    }
}
