<?php

namespace Database\Seeders;

use App\Models\Perawatan;
use Illuminate\Database\Seeder;

class PerawatanSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel perawatan.
     */
    public function run(): void
    {
        $perawatans = [
            [
                'name' => 'Potong Rambut',
                'description' => 'Gaya potong rambut terbaru dengan teknik profesional untuk tampilan segar dan percaya diri.',
                'price' => 80000,
                'duration' => '30',
                'image' => 'https://images.unsplash.com/photo-1601933470928-cda0031e0d4a?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80',
            ],
            [
                'name' => 'Pewarnaan Rambut',
                'description' => 'Ubah tampilan Anda dengan pilihan warna rambut modern dan berkualitas tinggi.',
                'price' => 350000,
                'duration' => '90',
                'image' => 'https://images.unsplash.com/photo-1611951049516-df90062b97cf?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80',
            ],
            [
                'name' => 'Creambath',
                'description' => 'Perawatan rambut dan kulit kepala menyeluruh dengan pijatan relaksasi.',
                'price' => 100000,
                'duration' => '45',
                'image' => 'https://images.unsplash.com/photo-1616628182504-6ef7d5826423?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80',
            ],
            [
                'name' => 'Masker Rambut',
                'description' => 'Perawatan intensif dengan masker alami untuk menutrisi dan memperbaiki rambut rusak.',
                'price' => 100000,
                'duration' => '30',
                'image' => 'https://images.unsplash.com/photo-1620912189860-71fc4e169057?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80',
            ],
            [
                'name' => 'Smooting',
                'description' => 'Teknik pelurusan rambut profesional agar halus, lembut, dan berkilau.',
                'price' => 300000,
                'duration' => '120',
                'image' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80',
            ],
            [
                'name' => 'Hair Extension',
                'description' => 'Tambahkan panjang dan volume rambut dengan hair extension berkualitas tinggi.',
                'price' => 15000, // per helai
                'duration' => '60',
                'image' => 'https://images.unsplash.com/photo-1616627797083-12dbf683c4e9?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80',
            ],
            [
                'name' => 'Keriting Rambut',
                'description' => 'Gaya rambut keriting tahan lama dengan teknik keriting modern dan aman.',
                'price' => 800000,
                'duration' => '150',
                'image' => 'https://images.unsplash.com/photo-1616627565124-fbfd2f4c95de?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80',
            ],
            [
                'name' => 'Keramas',
                'description' => 'Layanan cuci rambut menyegarkan dengan pijatan lembut dan produk berkualitas.',
                'price' => 30000,
                'duration' => '20',
                'image' => 'https://images.unsplash.com/photo-1620912436980-0423e1de5a9c?ixlib=rb-4.0.3&auto=format&fit=crop&w=870&q=80',
            ],
        ];

        foreach ($perawatans as $p) {
            Perawatan::create([
                'nama_perawatan' => $p['name'],
                'deskripsi'      => $p['description'],
                'harga'          => $p['price'],
                'waktu'          => $p['duration'],
                'foto'           => $p['image'],
            ]);
        }
    }
}
