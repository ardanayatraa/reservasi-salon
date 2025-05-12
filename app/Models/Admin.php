<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_admin';

    protected $fillable = [
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
        return $this->hasMany(Pemesanan::class, 'id_user', 'id_admin');
    }
}
