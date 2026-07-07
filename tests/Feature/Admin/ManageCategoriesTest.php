<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Category;
use App\Enums\Role;
use App\Enums\StatusAkun;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Livewire\Admin\Categories\ListCategories;
use Tests\TestCase;

class ManageCategoriesTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;
    private User $kepala;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'Nama_Pengguna' => 'Admin Mercusuar',
            'Email_Pengguna' => 'admin@mercusuar.com',
            'Kata_Sandi_Pengguna' => bcrypt('password'),
            'Peran_Akses_Pengguna' => Role::Admin,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
        ]);

        $this->user = User::create([
            'Nama_Pengguna' => 'Andi Anggota',
            'Email_Pengguna' => 'andi@gmail.com',
            'Kata_Sandi_Pengguna' => bcrypt('password'),
            'Peran_Akses_Pengguna' => Role::User,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
        ]);

        $this->kepala = User::create([
            'Nama_Pengguna' => 'Kepala Perpustakaan',
            'Email_Pengguna' => 'kepala@mercusuar.com',
            'Kata_Sandi_Pengguna' => bcrypt('password'),
            'Peran_Akses_Pengguna' => Role::KepalaPerpus,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
        ]);
    }

    /**
     * Test guest cannot access categories page.
     */
    public function test_guest_cannot_access_categories_page(): void
    {
        $response = $this->get(route('admin.categories.index'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test regular user cannot access categories page.
     */
    public function test_user_cannot_access_categories_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.categories.index'));
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test kepala perpustakaan cannot access categories page.
     */
    public function test_kepala_perpustakaan_cannot_access_categories_page(): void
    {
        $response = $this->actingAs($this->kepala)->get(route('admin.categories.index'));
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test admin can access categories page.
     */
    public function test_admin_can_access_categories_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertSeeLivewire(ListCategories::class);
    }

    /**
     * Test admin can create a category.
     */
    public function test_admin_can_create_category(): void
    {
        Livewire::actingAs($this->admin)
            ->test(ListCategories::class)
            ->set('nama_kategori', 'Sains')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Kategori berhasil ditambahkan.');

        $this->assertDatabaseHas('categories', [
            'Nama_kategori' => 'Sains',
        ]);
    }

    /**
     * Test validation unique category name.
     */
    public function test_admin_cannot_create_category_with_duplicate_name(): void
    {
        Category::create(['Nama_kategori' => 'Sains']);

        Livewire::actingAs($this->admin)
            ->test(ListCategories::class)
            ->set('nama_kategori', 'Sains')
            ->call('save')
            ->assertHasErrors(['nama_kategori' => 'unique']);
    }

    /**
     * Test admin can update a category.
     */
    public function test_admin_can_update_category(): void
    {
        $category = Category::create(['Nama_kategori' => 'Sains']);

        Livewire::actingAs($this->admin)
            ->test(ListCategories::class)
            ->call('edit', $category->Id_kategori)
            ->assertSet('nama_kategori', 'Sains')
            ->set('nama_kategori', 'Fisika')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Kategori berhasil diperbarui.');

        $this->assertDatabaseHas('categories', [
            'Id_kategori' => $category->Id_kategori,
            'Nama_kategori' => 'Fisika',
        ]);
    }

    /**
     * Test admin can delete a category.
     */
    public function test_admin_can_delete_category(): void
    {
        $category = Category::create(['Nama_kategori' => 'Sains']);

        Livewire::actingAs($this->admin)
            ->test(ListCategories::class)
            ->call('delete', $category->Id_kategori)
            ->assertSee('Kategori berhasil dihapus.');

        $this->assertDatabaseMissing('categories', [
            'Id_kategori' => $category->Id_kategori,
        ]);
    }
}
