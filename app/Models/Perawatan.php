<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perawatan extends Model
{
    use HasFactory;

    protected $table = 'perawatans';
    protected $primaryKey = 'id_perawatan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_perawatan',
        'deskripsi',
        'harga',
        'durasi',
        'kategori',
        'status',
        'gambar',
        'is_active'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'durasi' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke model Booked
     */
    public function bookeds()
    {
        return $this->hasMany(Booked::class, 'id_perawatan', 'id_perawatan');
    }

    /**
     * Scope untuk perawatan yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope berdasarkan kategori
     */
    public function scopeByKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Accessor untuk formatted harga
     */
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Accessor untuk formatted durasi
     */
    public function getFormattedDurasiAttribute()
    {
        if ($this->durasi >= 60) {
            $hours = floor($this->durasi / 60);
            $minutes = $this->durasi % 60;

            if ($minutes > 0) {
                return $hours . ' jam ' . $minutes . ' menit';
            } else {
                return $hours . ' jam';
            }
        } else {
            return $this->durasi . ' menit';
        }
    }

    /**
     * Accessor untuk gambar URL
     */
    public function getImageUrlAttribute()
    {
        if ($this->gambar) {
            return asset('storage/perawatan/' . $this->gambar);
        }

        return asset('images/default-treatment.jpg');
    }

    /**
     * Get total bookings untuk perawatan ini
     */
    public function getTotalBookingsAttribute()
    {
        return $this->bookeds()->count();
    }

    /**
     * Get rata-rata rating untuk perawatan ini
     */
    public function getAverageRatingAttribute()
    {
        $reviews = Review::whereHas('pemesanan.bookeds', function($query) {
            $query->where('id_perawatan', $this->id_perawatan);
        });

        if (Schema::hasColumn('reviews', 'status')) {
            $reviews->where('status', 'approved');
        }

        return $reviews->avg('rating') ?? 0;
    }

    /**
     * Get formatted rating dengan bintang
     */
    public function getFormattedRatingAttribute()
    {
        $rating = $this->average_rating;
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        $stars = str_repeat('★', $fullStars);
        if ($halfStar) {
            $stars .= '☆';
        }
        $stars .= str_repeat('☆', $emptyStars);

        return $stars . ' (' . number_format($rating, 1) . ')';
    }
}
