<?php

namespace App\Jobs;

use App\Models\Pemesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelUnpaidReservationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The reservation instance.
     *
     * @var \App\Models\Pemesanan
     */
    protected $reservation;

    /**
     * Create a new job instance.
     *
     * @param  \App\Models\Pemesanan  $reservation
     * @return void
     */
    public function __construct(Pemesanan $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Check if the reservation is still pending payment and deadline has passed
        if ($this->reservation->isWaitingForPayment() && $this->reservation->isPaymentDeadlinePassed()) {
            try {
                Log::info("Processing automatic cancellation for reservation #{$this->reservation->id_pemesanan}");

                // Cancel the reservation
                $this->reservation->cancelDueToPaymentDeadline();

                Log::info("Reservation #{$this->reservation->id_pemesanan} automatically cancelled due to payment deadline.");
            } catch (\Exception $e) {
                Log::error("Error cancelling reservation #{$this->reservation->id_pemesanan}: {$e->getMessage()}");
            }
        }
    }
}
