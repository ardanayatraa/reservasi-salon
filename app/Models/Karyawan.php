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
        'availability_status',
    ];

    protected $casts = [
        'availability_status' => 'string',
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift', 'id_shift');
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_karyawan', 'id_karyawan');
    }

    /**
     * Check if the employee is available
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->availability_status === 'available';
    }

    /**
     * Set employee as available
     *
     * @return void
     */
    public function setAvailable()
    {
        $this->update(['availability_status' => 'available']);
    }

    /**
     * Set employee as unavailable
     *
     * @return void
     */
    public function setUnavailable()
    {
        $this->update(['availability_status' => 'unavailable']);
    }
}
