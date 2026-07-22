<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Book;
use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response
            ->assertOk()
            ->assertSeeVolt('pages.auth.admin-login');
    }

    public function test_admin_can_authenticate_using_admin_login_screen(): void
    {
        $admin = User::factory()->create([
            'Peran_Akses_Pengguna' => Role::Admin,
        ]);

        $component = Volt::test('pages.auth.admin-login')
            ->set('email', $admin->Email_Pengguna)
            ->set('password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.dashboard', absolute: false));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_regular_user_cannot_authenticate_using_admin_login_screen(): void
    {
        $user = User::factory()->create([
            'Peran_Akses_Pengguna' => Role::User,
        ]);

        $component = Volt::test('pages.auth.admin-login')
            ->set('email', $user->Email_Pengguna)
            ->set('password', 'password');

        $component->call('login');

        $component->assertHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_book_can_be_created_with_28_char_isbn_and_tahun_pengadaan(): void
    {
        $isbn28 = '978-602-291-663-5-2024-00123'; // Exactly 28 chars
        $book = Book::create([
            'judul' => 'Buku Test ISBN 28',
            'ISBN' => $isbn28,
            'tahun_pengadaan' => 2024,
            'stok_total' => 5,
            'stok_tersedia' => 5,
        ]);

        $this->assertEquals(28, strlen($book->ISBN));
        $this->assertEquals(2024, $book->tahun_pengadaan);
    }
}
