<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    /**
     * Mengizinkan mass assignment untuk semua atribut kecuali ID.
     */
    protected $guarded = ['id'];

    /**
     * Relasi: Satu buku bisa ada di banyak transaksi peminjaman.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Relasi: Satu buku bisa memiliki banyak review.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relasi: Satu buku dimiliki oleh satu kategori.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Accessor untuk URL Gambar Cover Buku.
     * Mendukung URL eksternal (seeded) dan path local public storage.
     */
    public function getCoverUrlAttribute(): string
    {
        if (!$this->gambar_cover) {
            return 'https://placehold.co/400x600/F3EDF7/6750A4?text=' . urlencode($this->judul ?? 'No Cover');
        }

        if (str_starts_with($this->gambar_cover, 'http://') || str_starts_with($this->gambar_cover, 'https://')) {
            return $this->gambar_cover;
        }

        return asset('storage/' . $this->gambar_cover);
    }
}