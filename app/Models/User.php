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

    protected $primaryKey = 'Id_pengguna';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Nama_Pengguna',
        'Email_Pengguna',
        'Kata_Sandi_Pengguna',
        'Peran_Akses_Pengguna',
        'Status_Akun_Pengguna',
        'Alamat_Pengguna',
        'No_Telepon_Pengguna',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'Kata_Sandi_Pengguna',
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
            'Kata_Sandi_Pengguna' => 'hashed',
            'Peran_Akses_Pengguna' => Role::class,
            'Status_Akun_Pengguna' => StatusAkun::class,
        ];
    }

    public function getAuthPassword()
    {
        return $this->Kata_Sandi_Pengguna;
    }

    /**
     * Override standard email attribute for password resets.
     */
    public function getEmailForPasswordReset()
    {
        return $this->Email_Pengguna;
    }

    /**
     * Override standard email attribute for verification.
     */
    public function getEmailForVerification()
    {
        return $this->Email_Pengguna;
    }

    /**
     * Relasi: Seorang User bisa memiliki banyak peminjaman.
     */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'Id_Pengguna');
    }

    /**
     * Relasi: Seorang User bisa memberikan banyak review.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'Id_Pengguna');
    }

    /**
     * Accessor untuk Tautan WhatsApp Pengguna.
     */
    public function getWaUrlAttribute(): ?string
    {
        if (!$this->No_Telepon_Pengguna) {
            return null;
        }

        // Hapus karakter non-digit
        $cleanPhone = preg_replace('/[^0-9]/', '', $this->No_Telepon_Pengguna);

        // Jika diawali 08, ganti dengan 628
        if (str_starts_with($cleanPhone, '08')) {
            $cleanPhone = '628' . substr($cleanPhone, 2);
        }

        return 'https://wa.me/' . $cleanPhone;
    }
}