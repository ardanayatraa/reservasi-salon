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

        Pelanggan::create([
    'nama_lengkap' => 'Budi Lempog',
    'username'     => 'lempog',
    'password'     => Hash::make('rahasia'),
    'email'        => 'budi@example.com',
    'no_telepon'   => '08123456789',
    'alamat'       => 'Jl. Mawar No.10'
]);
    }
}
