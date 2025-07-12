<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pemesanan;
    public $type;
    public $additionalData;

    public function __construct($pemesanan, $type, $additionalData = [])
    {
        $this->pemesanan = $pemesanan;
        $this->type = $type;
        $this->additionalData = $additionalData;
    }

    public function build()
    {
        $subject = $this->getSubjectByType();

        return $this->subject($subject)
                    ->view('emails.booking-notification')
                    ->with([
                        'pemesanan' => $this->pemesanan,
                        'type' => $this->type,
                        'additionalData' => $this->additionalData
                    ]);
    }

    private function getSubjectByType()
    {
        $subjects = [
            'booking_confirmation' => 'Konfirmasi Booking - Dewi Beauty Salon',
            'booking_reminder' => 'Pengingat Booking - Dewi Beauty Salon',
            'cancellation' => 'Pembatalan Booking - Dewi Beauty Salon',
            'reschedule' => 'Perubahan Jadwal Booking - Dewi Beauty Salon',
            'refund' => 'Konfirmasi Refund - Dewi Beauty Salon'
        ];

        return $subjects[$this->type] ?? 'Notifikasi - Dewi Beauty Salon';
    }
}
