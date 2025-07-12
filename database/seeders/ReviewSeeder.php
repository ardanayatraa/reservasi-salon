<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Pemesanan;

class ReviewSeeder extends Seeder
{
    public function run()
    {
        $completedBookings = Pemesanan::where('status_pemesanan', 'completed')->take(5)->get();

        foreach ($completedBookings as $booking) {
            Review::create([
                'id_pemesanan' => $booking->id_pemesanan,
                'id_pelanggan' => $booking->id_pelanggan,
                'rating' => rand(4, 5),
                'komentar' => 'Pelayanan sangat memuaskan! Akan datang lagi.',
                'status' => 'approved',
                'tanggal_review' => now()
            ]);
        }
    }
}
