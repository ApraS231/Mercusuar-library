<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    /**
     * Mengizinkan mass assignment untuk semua atribut kecuali ID.
     */
    protected $guarded = ['id'];

    /**
     * Relasi: Satu review dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu review diberikan untuk satu buku.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}