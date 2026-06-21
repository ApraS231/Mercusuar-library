<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Enums\Role;
use App\Enums\StatusAkun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessRestrictionsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $kepala;
    private User $user;
    private Book $book;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Mercusuar',
            'email' => 'admin@mercusuar.com',
            'password' => bcrypt('password'),
            'role' => Role::Admin,
            'status_akun' => StatusAkun::Aktif,
        ]);

        $this->kepala = User::create([
            'name' => 'Kepala Perpustakaan',
            'email' => 'kepala@mercusuar.com',
            'password' => bcrypt('password'),
            'role' => Role::KepalaPerpus,
            'status_akun' => StatusAkun::Aktif,
        ]);

        $this->user = User::create([
            'name' => 'Andi Anggota',
            'email' => 'andi@gmail.com',
            'password' => bcrypt('password'),
            'role' => Role::User,
            'status_akun' => StatusAkun::Aktif,
        ]);

        $category = Category::create(['nama_kategori' => 'Novel']);
        $this->book = Book::create([
            'judul' => 'Buku Test',
            'category_id' => $category->id,
            'penulis' => 'Penulis Test',
            'penerbit' => 'Penerbit Test',
            'stok_total' => 5,
            'stok_tersedia' => 5,
        ]);
    }

    /**
     * Test Kepala Perpustakaan is blocked from accessing Catalog, Book Detail, and My Loans pages.
     */
    public function test_kepala_perpustakaan_is_blocked_from_user_pages(): void
    {
        // 1. Catalog page (/dashboard)
        $response = $this->actingAs($this->kepala)->get('/dashboard');
        $response->assertStatus(403);

        // 2. Book Detail page (/book/{id})
        $response = $this->actingAs($this->kepala)->get(route('book.detail', $this->book->id));
        $response->assertStatus(403);

        // 3. My Loans page (/my-loans)
        $response = $this->actingAs($this->kepala)->get('/my-loans');
        $response->assertStatus(403);
    }

    /**
     * Test Admin is blocked from accessing My Loans page.
     */
    public function test_admin_is_blocked_from_my_loans_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/my-loans');
        $response->assertStatus(403);
    }

    /**
     * Test Admin is allowed to access Catalog.
     */
    public function test_admin_is_allowed_to_access_catalog(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
    }

    /**
     * Test Regular User is allowed to access Catalog and My Loans pages.
     */
    public function test_user_is_allowed_to_access_user_pages(): void
    {
        // 1. Catalog page (/dashboard)
        $response = $this->actingAs($this->user)->get('/dashboard');
        $response->assertStatus(200);

        // 2. My Loans page (/my-loans)
        $response = $this->actingAs($this->user)->get('/my-loans');
        $response->assertStatus(200);
    }

    /**
     * Test login redirects Kepala Perpustakaan to /kepala-perpus/dashboard.
     */
    public function test_login_redirects_kepala_perpustakaan_correctly(): void
    {
        $component = \Livewire\Volt\Volt::test('pages.auth.login')
            ->set('email', $this->kepala->email)
            ->set('password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('kepala-perpus.dashboard', absolute: false));
    }

    /**
     * Test login redirects Admin to /admin/dashboard.
     */
    public function test_login_redirects_admin_correctly(): void
    {
        $component = \Livewire\Volt\Volt::test('pages.auth.login')
            ->set('email', $this->admin->email)
            ->set('password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.dashboard', absolute: false));
    }
}
