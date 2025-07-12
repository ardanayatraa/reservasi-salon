<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $primaryKey = 'id_review';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pemesanan',
        'id_pelanggan',
        'rating',
        'komentar',
        'status',
        'tanggal_review',
        'admin_notes'
    ];

    protected $casts = [
        'rating' => 'integer',
        'tanggal_review' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
        // Removed default status to prevent overriding actual status
    ];

    /**
     * Relasi ke model Pemesanan
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    /**
     * Relasi ke model Pelanggan
     */
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    /**
     * Scope untuk review yang sudah disetujui
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk review yang pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk review yang ditolak
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Accessor untuk menampilkan rating dalam bentuk bintang
     */
    public function getStarRatingAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Mutator untuk memastikan rating dalam range 1-5
     */
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = max(1, min(5, (int)$value));
    }
}
