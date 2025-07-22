<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanans';
    protected $primaryKey = 'id_pemesanan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pelanggan',
        'id_karyawan',
        'tanggal_pemesanan',
        'waktu',
        'jumlah_perawatan',
        'total',
        'sub_total',
        'metode_pembayaran',
        'status_pemesanan',
        'status_pembayaran',
        'token',
        'alasan_pembatalan',
        'cancelled_at',
        'cancelled_by',
        'completed_at',
        'started_at',
        'reschedule_count',
        'original_date',
        'original_time',
        'payment_deadline',
        'status'
    ];

    protected $casts = [
        'tanggal_pemesanan' => 'date',
        'original_date' => 'date',
        'cancelled_at' => 'datetime',
        'completed_at' => 'datetime',
        'started_at' => 'datetime',
        'reschedule_count' => 'integer',
        'total' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'payment_deadline' => 'datetime',
    ];

    // Existing relationships
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    public function bookeds()
    {
        return $this->hasMany(Booked::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemesanan', 'id_pemesanan');
    }

    // Review relationships - both singular and plural for compatibility
    public function review()
    {
        return $this->hasOne(Review::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function bookingLogs()
    {
        return $this->hasMany(BookingLog::class, 'id_pemesanan', 'id_pemesanan');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status_pemesanan', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status_pemesanan', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status_pemesanan', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status_pemesanan', 'cancelled');
    }

    // Helper methods
    public function canCancel()
    {
        $bookingDate = \Carbon\Carbon::parse($this->tanggal_pemesanan);
        $now = \Carbon\Carbon::now();

        if ($bookingDate->isToday() || $bookingDate->isPast()) {
            return false;
        }

        if ($bookingDate->diffInDays($now) < 1) {
            return false;
        }

        return in_array($this->status_pemesanan, ['confirmed', 'pending']);
    }

    public function canReschedule()
    {
        if ($this->reschedule_count >= 1) {
            return false;
        }

        $bookingDate = \Carbon\Carbon::parse($this->tanggal_pemesanan);
        $now = \Carbon\Carbon::now();

        if ($bookingDate->isToday() || $bookingDate->isPast()) {
            return false;
        }

        if ($bookingDate->diffInDays($now) < 1) {
            return false;
        }

        return $this->status_pemesanan === 'confirmed';
    }

    public function canReview()
    {
        return $this->status_pemesanan === 'completed' && !$this->hasReview();
    }

    /**
     * Cek apakah pemesanan sudah pernah direview
     */
    public function hasReview()
    {
        return $this->review()->exists();
    }

    /**
     * Get the first review (for single review system)
     */
    public function getFirstReview()
    {
        return $this->review;
    }

    /**
     * Get all reviews (for multiple review system)
     */
    public function getAllReviews()
    {
        return $this->reviews;
    }

    /**
     * Accessor untuk formatted total harga
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    /**
     * Accessor untuk formatted sub total
     */
    public function getFormattedSubTotalAttribute()
    {
        return 'Rp ' . number_format($this->sub_total, 0, ',', '.');
    }

    /**
     * Accessor untuk status pemesanan dalam bahasa Indonesia
     */
    public function getStatusPemesananIndonesianAttribute()
    {
        $translations = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'in_progress' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'rescheduled' => 'Dijadwal Ulang'
        ];

        return $translations[$this->status_pemesanan] ?? $this->status_pemesanan;
    }

    /**
     * Accessor untuk status pembayaran dalam bahasa Indonesia
     */
    public function getStatusPembayaranIndonesianAttribute()
    {
        $translations = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan'
        ];

        return $translations[$this->status_pembayaran] ?? $this->status_pembayaran;
    }

    /**
     * Get formatted datetime for display
     */
    public function getFormattedBookingDateTimeAttribute()
    {
        return $this->tanggal_pemesanan->format('d M Y') . ' ' . $this->waktu;
    }

    /**
     * Check if booking is today
     */
    public function isTodayBooking()
    {
        return $this->tanggal_pemesanan->isToday();
    }

    /**
     * Check if booking is in the past
     */
    public function isPastBooking()
    {
        return $this->tanggal_pemesanan->isPast();
    }

    /**
     * Get days until booking
     */
    public function getDaysUntilBooking()
    {
        if ($this->isPastBooking()) {
            return 0;
        }

        return \Carbon\Carbon::now()->diffInDays($this->tanggal_pemesanan);
    }

    /**
     * Get booking status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'bg-warning',
            'confirmed' => 'bg-info',
            'in_progress' => 'bg-primary',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            'rescheduled' => 'bg-secondary'
        ];

        return $classes[$this->status_pemesanan] ?? 'bg-secondary';
    }

    /**
     * Get payment status badge class for UI
     */
    public function getPaymentStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'bg-warning',
            'paid' => 'bg-success',
            'failed' => 'bg-danger',
            'refunded' => 'bg-info'
        ];

        return $classes[$this->status_pembayaran] ?? 'bg-secondary';
    }
}
