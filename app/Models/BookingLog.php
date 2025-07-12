<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLog extends Model
{
    use HasFactory;

    protected $table = 'booking_logs';
    protected $primaryKey = 'id_log';
    public $incrementing = true;
    protected $keyType = 'int';

    // Hanya menggunakan created_at sesuai migration
    const UPDATED_AT = null;

    protected $fillable = [
        'id_pemesanan',
        'action_type',
        'reason',
        'old_date',
        'old_time',
        'new_date',
        'new_time',
        'refund_amount',
        'refund_status'
    ];

    protected $casts = [
        'old_date' => 'date',
        'old_time' => 'datetime:H:i:s',
        'new_date' => 'date',
        'new_time' => 'datetime:H:i:s',
        'refund_amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke model Pemesanan
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    /**
     * Scope untuk action type tertentu
     */
    public function scopeByActionType($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope untuk cancellation logs
     */
    public function scopeCancellations($query)
    {
        return $query->where('action_type', 'cancel');
    }

    /**
     * Scope untuk reschedule logs
     */
    public function scopeReschedules($query)
    {
        return $query->where('action_type', 'reschedule');
    }

    /**
     * Scope untuk refund logs
     */
    public function scopeRefunds($query)
    {
        return $query->where('action_type', 'refund');
    }

    /**
     * Scope untuk refund status tertentu
     */
    public function scopeByRefundStatus($query, $status)
    {
        return $query->where('refund_status', $status);
    }

    /**
     * Accessor untuk mendapatkan formatted refund amount
     */
    public function getFormattedRefundAmountAttribute()
    {
        return $this->refund_amount ? 'Rp ' . number_format($this->refund_amount, 0, ',', '.') : null;
    }

    /**
     * Accessor untuk mendapatkan action type dalam bahasa Indonesia
     */
    public function getActionTypeIndonesianAttribute()
    {
        $translations = [
            'cancel' => 'Pembatalan',
            'reschedule' => 'Reschedule',
            'refund' => 'Refund'
        ];

        return $translations[$this->action_type] ?? $this->action_type;
    }

    /**
     * Accessor untuk mendapatkan refund status dalam bahasa Indonesia
     */
    public function getRefundStatusIndonesianAttribute()
    {
        $translations = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'completed' => 'Selesai',
            'failed' => 'Gagal'
        ];

        return $translations[$this->refund_status] ?? $this->refund_status;
    }
}
