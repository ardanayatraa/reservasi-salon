<?php

namespace App\Mail;

use App\Models\Pemesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $pemesanan;

    public function __construct(Pemesanan $pemesanan)
    {
        $this->pemesanan = $pemesanan;
    }

    public function build()
    {
        return $this->subject('Konfirmasi Booking - Dewi Beauty Salon')
                    ->view('emails.booking-confirmation')
                    ->with([
                        'pemesanan' => $this->pemesanan,
                        'pelanggan' => $this->pemesanan->pelanggan,
                        'services' => $this->pemesanan->bookeds->load('perawatan')
                    ]);
    }
}
