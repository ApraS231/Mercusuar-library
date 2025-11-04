<?php

namespace App\Models;

use App\Enums\Enums\StatusAkun as EnumsStatusAkun;
use App\Enums\Role;
use App\Enums\StatusAkun;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', //
        'status_akun', //
        'alamat', //
        'no_telepon', //
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Role::class, //
            'status_akun' => StatusAkun::class, //
        ];
    }

    /**
     * Relasi: Seorang User bisa memiliki banyak peminjaman.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    /**
     * Relasi: Seorang User bisa memberikan banyak review.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}