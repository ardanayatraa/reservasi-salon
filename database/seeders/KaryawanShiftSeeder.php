<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shift;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;

class KaryawanShiftSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat 2 shift
        $shiftPagi = Shift::create([
            'nama_shift' => 'Pagi',
            'start_time' => '08:30:00',
            'end_time'   => '17:00:00',
        ]);

        $shiftSiang = Shift::create([
            'nama_shift' => 'Siang',
            'start_time' => '11:30:00',
            'end_time'   => '19:00:00',
        ]);

        // 2. Data 9 karyawan (5 di shift pagi, 4 di shift siang)
        $karyawans = [
            ['nama_lengkap'=>'Sistri','email'=>'sistri@example.com','no_telepon'=>'081234567890','alamat'=>'Jl. Melati No.1','shift'=>$shiftPagi],

        ];

        foreach ($karyawans as $data) {
            Karyawan::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'email'        => $data['email'],
                'no_telepon'   => $data['no_telepon'],
                'alamat'       => $data['alamat'],
                'id_shift'     => $data['shift']->id_shift,
            ]);
        }
    }
}
