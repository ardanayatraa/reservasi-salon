<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailNotification extends Model
{
    use HasFactory;

    protected $table = 'email_notifications';
    protected $primaryKey = 'id_notification';
    public $incrementing = true;
    protected $keyType = 'int';

    // Hanya menggunakan sent_at sesuai migration
    const CREATED_AT = 'sent_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'id_pelanggan',
        'email_type',
        'subject',
        'body',
        'status'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Relasi ke model Pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Scope untuk email type tertentu
     */
    public function scopeByEmailType($query, $emailType)
    {
        return $query->where('email_type', $emailType);
    }

    /**
     * Scope untuk email yang berhasil dikirim
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope untuk email yang gagal dikirim
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope untuk booking confirmation emails
     */
    public function scopeBookingConfirmation($query)
    {
        return $query->where('email_type', 'booking_confirmation');
    }

    /**
     * Scope untuk booking reminder emails
     */
    public function scopeBookingReminder($query)
    {
        return $query->where('email_type', 'booking_reminder');
    }

    /**
     * Scope untuk cancellation emails
     */
    public function scopeCancellation($query)
    {
        return $query->where('email_type', 'cancellation');
    }

    /**
     * Scope untuk reschedule emails
     */
    public function scopeReschedule($query)
    {
        return $query->where('email_type', 'reschedule');
    }

    /**
     * Scope untuk refund emails
     */
    public function scopeRefund($query)
    {
        return $query->where('email_type', 'refund');
    }

    /**
     * Accessor untuk mendapatkan email type dalam bahasa Indonesia
     */
    public function getEmailTypeIndonesianAttribute()
    {
        $translations = [
            'booking_confirmation' => 'Konfirmasi Booking',
            'booking_reminder' => 'Pengingat Booking',
            'cancellation' => 'Pembatalan',
            'reschedule' => 'Reschedule',
            'refund' => 'Refund'
        ];

        return $translations[$this->email_type] ?? $this->email_type;
    }

    /**
     * Accessor untuk mendapatkan status dalam bahasa Indonesia
     */
    public function getStatusIndonesianAttribute()
    {
        $translations = [
            'sent' => 'Terkirim',
            'failed' => 'Gagal'
        ];

        return $translations[$this->status] ?? $this->status;
    }

    /**
     * Accessor untuk mendapatkan icon berdasarkan email type
     */
    public function getEmailTypeIconAttribute()
    {
        $icons = [
            'booking_confirmation' => '✅',
            'booking_reminder' => '⏰',
            'cancellation' => '❌',
            'reschedule' => '📅',
            'refund' => '💰'
        ];

        return $icons[$this->email_type] ?? '📧';
    }

    /**
     * Accessor untuk mendapatkan status icon
     */
    public function getStatusIconAttribute()
    {
        return $this->status === 'sent' ? '✅' : '❌';
    }
}
