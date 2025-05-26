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
            ['nama_lengkap'=>'Ahmad','username'=>'ahmad','email'=>'ahmad@example.com','no_telepon'=>'081234567890','alamat'=>'Jl. Melati No.1','shift'=>$shiftPagi],
            ['nama_lengkap'=>'Budi','username'=>'budi','email'=>'budi@example.com','no_telepon'=>'081234567891','alamat'=>'Jl. Mawar No.2','shift'=>$shiftPagi],
            ['nama_lengkap'=>'Citra','username'=>'citra','email'=>'citra@example.com','no_telepon'=>'081234567892','alamat'=>'Jl. Kenanga No.3','shift'=>$shiftPagi],
            ['nama_lengkap'=>'Dedi','username'=>'dedi','email'=>'dedi@example.com','no_telepon'=>'081234567893','alamat'=>'Jl. Anggrek No.4','shift'=>$shiftPagi],
            ['nama_lengkap'=>'Eka','username'=>'eka','email'=>'eka@example.com','no_telepon'=>'081234567894','alamat'=>'Jl. Dahlia No.5','shift'=>$shiftPagi],
            ['nama_lengkap'=>'Fajar','username'=>'fajar','email'=>'fajar@example.com','no_telepon'=>'081234567895','alamat'=>'Jl. Teratai No.6','shift'=>$shiftSiang],
            ['nama_lengkap'=>'Gita','username'=>'gita','email'=>'gita@example.com','no_telepon'=>'081234567896','alamat'=>'Jl. Lili No.7','shift'=>$shiftSiang],
            ['nama_lengkap'=>'Hendra','username'=>'hendra','email'=>'hendra@example.com','no_telepon'=>'081234567897','alamat'=>'Jl. Melur No.8','shift'=>$shiftSiang],
            ['nama_lengkap'=>'Indah','username'=>'indah','email'=>'indah@example.com','no_telepon'=>'081234567898','alamat'=>'Jl. Kamboja No.9','shift'=>$shiftSiang],
        ];

        foreach ($karyawans as $data) {
            Karyawan::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'username'     => $data['username'],
                'password'     => Hash::make('password123'),  // ganti default password jika perlu
                'email'        => $data['email'],
                'no_telepon'   => $data['no_telepon'],
                'alamat'       => $data['alamat'],
                'id_shift'     => $data['shift']->id_shift,
            ]);
        }
    }
}
