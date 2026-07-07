<?php

namespace Tests\Feature;

use App\Models\User;
use App\Enums\Role;
use App\Enums\StatusAkun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test kepala perpustakaan can access reporting page.
     */
    public function test_kepala_perpustakaan_can_access_laporan_peminjaman(): void
    {
        $kepala = User::create([
            'Nama_Pengguna' => 'Kepala Perpustakaan',
            'Email_Pengguna' => 'kepala@mercusuar.com',
            'Kata_Sandi_Pengguna' => bcrypt('password'),
            'Peran_Akses_Pengguna' => Role::KepalaPerpus,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
        ]);

        $response = $this->actingAs($kepala)->get('/kepala-perpus/laporan-peminjaman');

        $response->assertStatus(200);
    }

    /**
     * Test regular user cannot access reporting page.
     */
    public function test_user_cannot_access_laporan_peminjaman(): void
    {
        $user = User::create([
            'Nama_Pengguna' => 'Andi Anggota',
            'Email_Pengguna' => 'andi@gmail.com',
            'Kata_Sandi_Pengguna' => bcrypt('password'),
            'Peran_Akses_Pengguna' => Role::User,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
        ]);

        $response = $this->actingAs($user)->get('/kepala-perpus/laporan-peminjaman');

        $response->assertRedirect('/dashboard');
    }

    /**
     * Test admin cannot access reporting page.
     */
    public function test_admin_cannot_access_laporan_peminjaman(): void
    {
        $admin = User::create([
            'Nama_Pengguna' => 'Admin Mercusuar',
            'Email_Pengguna' => 'admin@mercusuar.com',
            'Kata_Sandi_Pengguna' => bcrypt('password'),
            'Peran_Akses_Pengguna' => Role::Admin,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
        ]);

        $response = $this->actingAs($admin)->get('/kepala-perpus/laporan-peminjaman');

        $response->assertRedirect('/dashboard');
    }

    /**
     * Test kepala perpustakaan can export sirkulasi reports to CSV.
     */
    public function test_kepala_perpustakaan_can_export_laporan_to_csv(): void
    {
        $kepala = User::create([
            'Nama_Pengguna' => 'Kepala Perpustakaan',
            'Email_Pengguna' => 'kepala@mercusuar.com',
            'Kata_Sandi_Pengguna' => bcrypt('password'),
            'Peran_Akses_Pengguna' => Role::KepalaPerpus,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
        ]);

        $response = \Livewire\Livewire::actingAs($kepala)
            ->test(\App\Livewire\KepalaPerpus\LaporanPeminjaman::class)
            ->call('exportCSV');

        $response->assertStatus(200);
    }
}
