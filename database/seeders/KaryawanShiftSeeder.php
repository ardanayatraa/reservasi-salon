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

        // 2. Data karyawan sesuai JSON (5 di shift pagi, 3 di shift siang)
        $karyawans = [
            // Shift Pagi (id_shift: 1)
            [
                'nama_lengkap' => 'Dewa Ayu Putu Sari',
                'email' => 'DewaAyu@gmail.com',
                'no_telepon' => '081234567890',
                'alamat' => 'Jl. Mas Ubud',
                'shift' => $shiftPagi
            ],
            [
                'nama_lengkap' => 'Ni Putu Melia Arista',
                'email' => 'Arista@gmail.com',
                'no_telepon' => '081888765234',
                'alamat' => 'Jl. Mas Ubud',
                'shift' => $shiftPagi
            ],
            [
                'nama_lengkap' => 'Ni Putu Yuli Damayanti',
                'email' => 'Yuli@gmail.com',
                'no_telepon' => '081893456723',
                'alamat' => 'Jl. Mas Ubud',
                'shift' => $shiftPagi
            ],
            [
                'nama_lengkap' => 'Ni Komang Bintan',
                'email' => 'Bintan@gmail.com',
                'no_telepon' => '081987776511',
                'alamat' => 'Jl. Mas Ubud',
                'shift' => $shiftPagi
            ],
            [
                'nama_lengkap' => 'Ni Wayan Budi Martini',
                'email' => 'Budi@gmail.com',
                'no_telepon' => '081543622190',
                'alamat' => 'Jl. Mas Ubud',
                'shift' => $shiftPagi
            ],
            // Shift Siang (id_shift: 2)
            [
                'nama_lengkap' => 'Ni Kadek Dwika Arthari',
                'email' => 'Dwika@gmail.com',
                'no_telepon' => '085113455440',
                'alamat' => 'Jl. Mas Ubud',
                'shift' => $shiftSiang
            ],
            [
                'nama_lengkap' => 'Desak Made Indah Sari',
                'email' => 'Indah@gmail.com',
                'no_telepon' => '085123456987',
                'alamat' => 'Jl. Mas Ubud',
                'shift' => $shiftSiang
            ],
            [
                'nama_lengkap' => 'Ni Made Hana Ari Lestari',
                'email' => 'Hana@gmail.com',
                'no_telepon' => '085678987651',
                'alamat' => 'Jl. Mas Ubud',
                'shift' => $shiftSiang
            ],
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
