<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id_kategori';

    protected $fillable = ['Nama_kategori'];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'Id_kategori');
    }
}
