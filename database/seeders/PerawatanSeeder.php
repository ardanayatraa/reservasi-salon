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
                'name' => 'Pewarnaan Rambut',
                'description' => 'Ubah tampilan Anda dengan pilihan warna rambut modern dan berkualitas tinggi.',
                'price' => 350000,
                'duration' => '90',
                'image' => 'perawatan/Fa3Bk5gOmK3XnwJ0T8gse2iCJrTci5SDLUYdQMGa.jpg',
            ],
            [
                'name' => 'Creambath',
                'description' => 'Perawatan rambut dan kulit kepala menyeluruh dengan pijatan relaksasi.',
                'price' => 100000,
                'duration' => '60',
                'image' => 'perawatan/acJQakWETtiViyGvWDYcJDZ0iJ0qBURKMtSmvNun.jpg',
            ],
            [
                'name' => 'Masker Rambut',
                'description' => 'Perawatan intensif dengan masker alami untuk menutrisi dan memperbaiki rambut rusak.',
                'price' => 100000,
                'duration' => '30',
                'image' => 'perawatan/jNaRZGB6MfulWmY1ibmcFABMnzg8RkWwzgzzHFV0.jpg',
            ],
            [
                'name' => 'Smooting',
                'description' => 'Teknik pelurusan rambut profesional agar halus, lembut, dan berkilau.',
                'price' => 300000,
                'duration' => '120',
                'image' => 'perawatan/cByAU94nkccWn6337gfp0pou3DbjB6F9yszW4y2K.jpg',
            ],
            [
                'name' => 'Hair Extension',
                'description' => 'Tambahkan panjang dan volume rambut dengan hair extension berkualitas tinggi.',
                'price' => 15000, // per helai
                'duration' => '60',
                'image' => 'perawatan/OY4Ry1VuNoeTmieQLLLbshOCOsyEkFm5a1VoZctl.jpg',
            ],
            [
                'name' => 'Keriting Rambut',
                'description' => 'Gaya rambut keriting tahan lama dengan teknik keriting modern dan aman.',
                'price' => 800000,
                'duration' => '30',
                'image' => 'perawatan/LzQGwEeAS5Xn8vTzQJbmTZSsGSdAobuj4YX13rRp.jpg',
            ],
            [
                'name' => 'Keramas',
                'description' => 'Layanan cuci rambut menyegarkan dengan pijatan lembut dan produk berkualitas.',
                'price' => 30000,
                'duration' => '90',
                'image' => 'perawatan/hXwaAuNjWitLtQQDjXPXQC4YMlwJuyIqtOZvtd7E.jpg',
            ],
            [
                'name' => 'Potong Rambut',
                'description' => 'Gaya potong rambut terbaru dengan teknik profesional untuk tampilan segar dan percaya diri.',
                'price' => 80000,
                'duration' => '30',
                'image' => 'perawatan/LSlsmlyug3Bx6tPabVGfCDfAXZsWxj0ef0EixbQ5.jpg',
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
