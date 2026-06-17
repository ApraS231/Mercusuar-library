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
            'name' => 'Kepala Perpustakaan',
            'email' => 'kepala@mercusuar.com',
            'password' => bcrypt('password'),
            'role' => Role::KepalaPerpus,
            'status_akun' => StatusAkun::Aktif,
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
            'name' => 'Andi Anggota',
            'email' => 'andi@gmail.com',
            'password' => bcrypt('password'),
            'role' => Role::User,
            'status_akun' => StatusAkun::Aktif,
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
            'name' => 'Admin Mercusuar',
            'email' => 'admin@mercusuar.com',
            'password' => bcrypt('password'),
            'role' => Role::Admin,
            'status_akun' => StatusAkun::Aktif,
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
            'name' => 'Kepala Perpustakaan',
            'email' => 'kepala@mercusuar.com',
            'password' => bcrypt('password'),
            'role' => Role::KepalaPerpus,
            'status_akun' => StatusAkun::Aktif,
        ]);

        $response = \Livewire\Livewire::actingAs($kepala)
            ->test(\App\Livewire\KepalaPerpus\LaporanPeminjaman::class)
            ->call('exportCSV');

        $response->assertStatus(200);
    }
}
