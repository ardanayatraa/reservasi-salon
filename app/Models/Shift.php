<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'shifts';
    protected $primaryKey = 'id_shift';

    protected $fillable = [
        'nama_shift',
        'start_time',
        'end_time',
    ];

    public function karyawans()
    {
        return $this->hasMany(Karyawan::class, 'id_shift', 'id_shift');
    }
}
