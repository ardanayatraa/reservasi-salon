<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    public function run()
    {
        // Akun Admin
        Admin::create([
            'username'   => 'admin',
            'password'   => Hash::make('password'),
            'email'      => 'admin@salon.com',
            'no_telepon' => '081234567890',
            'alamat'     => 'Jl. Merdeka No.1, Jakarta',
        ]);

        // Akun Pelanggan
        Pelanggan::create([
            'nama_lengkap' => 'Budi Santoso',
            'username'     => 'pelanggan',
            'password'     => Hash::make('password'),
            'email'        => 'budi@example.com',
            'no_telepon'   => '082112345678',
            'alamat'       => 'Jl. Melati No.5, Bandung',
        ]);

        Pelanggan::create([
            'nama_lengkap' => 'Siti Aminah',
            'username'     => 'siti',
            'password'     => Hash::make('rahasia456'),
            'email'        => 'siti@example.com',
            'no_telepon'   => '085212345678',
            'alamat'       => 'Jl. Anggrek No.10, Surabaya',
        ]);
    }
}
