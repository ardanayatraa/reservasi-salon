<?php

namespace Database\Seeders;

use App\Models\Perawatan;
use Illuminate\Database\Seeder;

class PerawatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perawatans = [
            [
                'name' => 'Facial Signature',
                'description' => 'Facial signature kami menggabungkan teknologi perawatan kulit canggih dengan teknik tradisional untuk meremajakan dan merevitalisasi kulit Anda.',
                'price' => 450000,
                'duration' => '60',
                'image' => 'https://images.unsplash.com/photo-1596178060810-72c633ce9383?ixlib=rb-4.0.3&auto=format&fit=crop&w=1169&q=80'
            ],
            [
                'name' => 'Ritual Rambut Mewah',
                'description' => 'Transformasikan rambut Anda dengan perawatan premium kami yang menampilkan minyak langka dan ekstrak botani untuk kilau dan kesehatan yang tak tertandingi.',
                'price' => 650000,
                'duration' => '90',
                'image' => 'https://images.unsplash.com/photo-1595867818082-083862f3d630?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80'
            ],
            [
                'name' => 'Pijat Bali',
                'description' => 'Nikmati relaksasi mendalam dengan pijat Bali tradisional kami yang ditingkatkan dengan minyak aromaterapi premium.',
                'price' => 550000,
                'duration' => '75',
                'image' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80'
            ]
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
