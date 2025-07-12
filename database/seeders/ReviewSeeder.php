<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Pemesanan;
use App\Models\Pelanggan;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing pemesanan and pelanggan
        $pemesanans = Pemesanan::all();
        $pelanggans = Pelanggan::all();

        if ($pemesanans->isEmpty() || $pelanggans->isEmpty()) {
            $this->command->info('No pemesanan or pelanggan found. Skipping review seeding.');
            return;
        }

        // Clear existing reviews
        Review::truncate();

        // Create sample reviews with correct statuses
        $reviews = [
            [
                'id_pemesanan' => $pemesanans->first()->id_pemesanan,
                'id_pelanggan' => $pelanggans->first()->id_pelanggan,
                'rating' => 5,
                'komentar' => 'Pelayanan sangat memuaskan! Staff ramah dan profesional.',
                'status' => 'approved',
                'admin_notes' => 'Review positif, layanan sesuai standar.',
                'tanggal_review' => now()->subDays(5),
            ],
            [
                'id_pemesanan' => $pemesanans->skip(1)->first()?->id_pemesanan ?? $pemesanans->first()->id_pemesanan,
                'id_pelanggan' => $pelanggans->skip(1)->first()?->id_pelanggan ?? $pelanggans->first()->id_pelanggan,
                'rating' => 4,
                'komentar' => 'Hasil perawatan bagus, tapi agak lama menunggu.',
                'status' => 'pending',
                'admin_notes' => null,
                'tanggal_review' => now()->subDays(3),
            ],
            [
                'id_pemesanan' => $pemesanans->skip(2)->first()?->id_pemesanan ?? $pemesanans->first()->id_pemesanan,
                'id_pelanggan' => $pelanggans->skip(2)->first()?->id_pelanggan ?? $pelanggans->first()->id_pelanggan,
                'rating' => 2,
                'komentar' => 'Tidak puas dengan hasil perawatan.',
                'status' => 'rejected',
                'admin_notes' => 'Review tidak sesuai dengan standar layanan kami.',
                'tanggal_review' => now()->subDays(1),
            ],
        ];

        foreach ($reviews as $reviewData) {
            Review::create($reviewData);
        }

        $this->command->info('Reviews seeded successfully with correct statuses!');
    }
}
