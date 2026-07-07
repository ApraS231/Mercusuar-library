<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'Nama_Pengguna' => substr(fake()->name(), 0, 20),
            'Email_Pengguna' => Str::random(10) . '@g.com',
            'email_verified_at' => now(),
            'Kata_Sandi_Pengguna' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'Peran_Akses_Pengguna' => \App\Enums\Role::User,
            'Status_Akun_Pengguna' => \App\Enums\StatusAkun::Aktif,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
