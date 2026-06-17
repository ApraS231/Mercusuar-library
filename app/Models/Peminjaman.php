<?php

namespace App\Models;

use App\Enums\StatusPeminjaman;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'peminjamans';

    /**
     * Mengizinkan mass assignment untuk semua atribut kecuali ID.
     */
    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => StatusPeminjaman::class,
            'tgl_booking' => 'datetime',
            'tgl_disetujui' => 'datetime',
            'tgl_jatuh_tempo' => 'date',
            'tgl_selesai' => 'datetime',
        ];
    }

    /**
     * Relasi: Satu peminjaman dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Satu peminjaman terkait dengan satu buku.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}