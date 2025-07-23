<?php

namespace App\Console\Commands;

use App\Models\Pemesanan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CancelUnpaidReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:cancel-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel reservations that have passed their payment deadline';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting to check for unpaid reservations...');

        // Get all pending reservations with passed payment deadline
        $unpaidReservations = Pemesanan::where('status_pembayaran', 'pending')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<', now())
            ->get();

        $count = $unpaidReservations->count();
        $this->info("Found {$count} unpaid reservations with passed payment deadline.");

        foreach ($unpaidReservations as $reservation) {
            try {
                $this->info("Cancelling reservation #{$reservation->id_pemesanan}");
                $reservation->cancelDueToPaymentDeadline();
                $this->info("Reservation #{$reservation->id_pemesanan} cancelled successfully.");

                // Log the cancellation
                Log::info("Reservation #{$reservation->id_pemesanan} automatically cancelled due to payment deadline.");
            } catch (\Exception $e) {
                $this->error("Error cancelling reservation #{$reservation->id_pemesanan}: {$e->getMessage()}");
                Log::error("Error cancelling reservation #{$reservation->id_pemesanan}: {$e->getMessage()}");
            }
        }

        $this->info('Finished processing unpaid reservations.');
        return 0;
    }
}
