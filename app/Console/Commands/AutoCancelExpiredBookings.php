<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoCancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:cancel-expired-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Batalkan otomatis pemesanan yang melewati batas waktu pembayaran dan status masih menunggu pembayaran.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $expired = \App\Models\Pemesanan::where('status', 'menunggu pembayaran')
            ->where('payment_deadline', '<', now())
            ->get();

        $count = 0;
        foreach ($expired as $pemesanan) {
            $pemesanan->status = 'kedaluwarsa';
            $pemesanan->save();
            // TODO: buka slot layanan/karyawan & notifikasi
            $count++;
        }
        $this->info("{$count} pemesanan dibatalkan otomatis.");
        return Command::SUCCESS;
    }
}
