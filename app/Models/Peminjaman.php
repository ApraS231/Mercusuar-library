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

    protected $primaryKey = 'Id_Peminjaman';

    /**
     * Mengizinkan mass assignment untuk semua atribut kecuali ID.
     */
    protected $guarded = ['Id_Peminjaman'];

    /**
     * Get the attributes that should be cast.
      * @return array<string, string>
      */
     protected function casts(): array
     {
         return [
             'Status_Peminjaman' => StatusPeminjaman::class,
             'Tanggal_Pinjam' => 'datetime',
             'Tanggal_Disetujui' => 'datetime',
             'Tanggal_Jatuh_Tempo' => 'date',
             'Tanggal_Selesai' => 'datetime',
         ];
     }
 
     /**
      * Relasi: Satu peminjaman dimiliki oleh satu user.
      */
     public function user(): BelongsTo
     {
         return $this->belongsTo(User::class, 'Id_Pengguna');
     }
 
     /**
      * Relasi: Satu peminjaman terkait dengan satu buku.
      */
     public function book(): BelongsTo
     {
         return $this->belongsTo(Book::class, 'Id_Buku');
     }
}