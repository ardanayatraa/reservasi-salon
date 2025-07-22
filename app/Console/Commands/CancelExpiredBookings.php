<?php

namespace App\Console\Commands;

use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel bookings that have passed their payment deadline.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Checking for expired bookings...');

        $expiredBookings = Pemesanan::where('status', 'pending')
            ->where('payment_deadline', '<', Carbon::now())
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('No expired bookings found.');
            return Command::SUCCESS;
        }

        foreach ($expiredBookings as $booking) {
            $booking->status = 'canceled';
            $booking->save();
            Log::info("Booking #{$booking->id_pemesanan} has been canceled due to non-payment.");
        }

        $this->info("Canceled {$expiredBookings->count()} expired bookings.");

        return Command::SUCCESS;
    }
}
