<?php

use Illuminate\Support\Facades\Route;

// Import semua komponen Livewire yang kita gunakan sebagai halaman
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Books\ListBooks as AdminListBooks;
use App\Livewire\Admin\Transactions\ManagePeminjaman as AdminManagePeminjaman;
use App\Livewire\Admin\Users\ListUsers as AdminListUsers;

// Komponen Frontend Pengguna
use App\Livewire\Katalog\BookCatalog;
use App\Livewire\Katalog\BookDetail;
use App\Livewire\User\MyLoans;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute web untuk aplikasi Anda.
|
*/

// Rute Homepage (dialihkan ke login atau dashboard)
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});


// === RUTE PENGGUNA (USER) ===
// Didasarkan pada BAGIAN 2 dari Roadmap

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Langkah 5: Halaman utama user adalah Katalog Buku
    Route::get('/dashboard', BookCatalog::class)->name('dashboard');

    // Langkah 7: Halaman "Peminjaman Saya"
    Route::get('/my-loans', MyLoans::class)->name('user.peminjaman');

    // Langkah 8: Halaman Profil (Bawaan Breeze)
    Route::view('/profile', 'profile')->name('profile');
});

// Langkah 6: Halaman Detail Buku (Hanya perlu auth, tidak perlu 'verified')
Route::get('/book/{book}', BookDetail::class)
    ->middleware('auth')
    ->name('book.detail');


// === RUTE ADMIN ===
// Didasarkan pada BAGIAN 1 dari Roadmap
// Semua rute di sini memerlukan login DAN middleware 'admin'

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Langkah 1: Dashboard Admin
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

    // Langkah 2: Manajemen Buku
    Route::get('/books', AdminListBooks::class)->name('books.index');

    // Langkah 3: Manajemen Peminjaman
    Route::get('/transactions', AdminManagePeminjaman::class)->name('transactions.index');

    // Langkah 4: Manajemen User
    Route::get('/users', AdminListUsers::class)->name('users.index');
});


// Rute Autentikasi (Login, Register, dll)
// Dibuat oleh Breeze
require __DIR__.'/auth.php';

