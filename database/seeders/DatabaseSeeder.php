<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Enums\Role;
use App\Enums\StatusAkun;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Hapus data lama (opsional, tapi bagus untuk development)
        User::query()->delete();
        Book::query()->delete();
        \App\Models\Category::query()->delete();

        // 0. BUAT DATA KATEGORI
        $novel = \App\Models\Category::create(['Nama_kategori' => 'Novel']);
        $sains = \App\Models\Category::create(['Nama_kategori' => 'Sains']);
        $filsafat = \App\Models\Category::create(['Nama_kategori' => 'Filsafat']);
        $selfDev = \App\Models\Category::create(['Nama_kategori' => 'Self Dev']);

        // 1. BUAT USER ADMIN
        User::create([
            'Nama_Pengguna' => 'Admin Mercusuar',
            'Email_Pengguna' => 'admin@mercusuar.com',
            'Kata_Sandi_Pengguna' => Hash::make('password'),
            'Peran_Akses_Pengguna' => Role::Admin,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
            'Alamat_Pengguna' => 'Jl. Admin No. 1, Jakarta',
            'No_Telepon_Pengguna' => '081234567890',
        ]);

        // 2. BUAT USER KEPALA PERPUSTAKAAN
        User::create([
            'Nama_Pengguna' => 'Kepala Perpustakaan',
            'Email_Pengguna' => 'kepala@mercusuar.com',
            'Kata_Sandi_Pengguna' => Hash::make('password'),
            'Peran_Akses_Pengguna' => Role::KepalaPerpus,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
            'Alamat_Pengguna' => 'Jl. Pustaka No. 50, Jakarta',
            'No_Telepon_Pengguna' => '089876543210',
        ]);

        // 3. BUAT USER BIASA
        User::create([
            'Nama_Pengguna' => 'Anggota Biasa (Andi)',
            'Email_Pengguna' => 'andi@gmail.com',
            'Kata_Sandi_Pengguna' => Hash::make('password'),
            'Peran_Akses_Pengguna' => Role::User,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
            'Alamat_Pengguna' => 'Jl. Pengguna No. 10, Bandung',
            'No_Telepon_Pengguna' => '081111111111',
        ]);

        User::create([
            'Nama_Pengguna' => 'Anggota Lain (Budi)',
            'Email_Pengguna' => 'budi@gmail.com',
            'Kata_Sandi_Pengguna' => Hash::make('password'),
            'Peran_Akses_Pengguna' => Role::User,
            'Status_Akun_Pengguna' => StatusAkun::Aktif,
            'Alamat_Pengguna' => 'Jl. Pembaca No. 5, Surabaya',
            'No_Telepon_Pengguna' => '082222222222',
        ]);

        // 3. BUAT DATA BUKU
        Book::create([
            'judul' => 'Laskar Pelangi',
            'Id_kategori' => $novel->Id_kategori,
            'penulis' => 'Andrea Hirata',
            'penerbit' => 'Bentang Pustaka',
            'deskripsi' => 'Novel yang menceritakan kehidupan 10 anak dari keluarga miskin yang bersekolah (SD dan SMP) di sebuah sekolah Muhammadiyah di Belitung yang penuh dengan keterbatasan.',
            'ISBN' => '11111111',
            'stok_total' => 5,
            'stok_tersedia' => 5,
            'gambar_cover' => 'laskar.jpg'
        ]);

        Book::create([
            'judul' => 'Bumi Manusia',
            'Id_kategori' => $novel->Id_kategori,
            'penulis' => 'Pramoedya Ananta Toer',
            'penerbit' => 'Hasta Mitra',
            'deskripsi' => 'Salah satu novel dalam tetralogi Pulau Buru. Menceritakan kisah Minke, seorang pemuda pribumi di era kolonial Belanda.',
            'ISBN' => '22222222',
            'stok_total' => 3,
            'stok_tersedia' => 3,
            'gambar_cover' => 'bumi.jpg'
        ]);

        Book::create([
            'judul' => 'Filosofi Teras',
            'Id_kategori' => $filsafat->Id_kategori,
            'penulis' => 'Henry Manampiring',
            'penerbit' => 'Kompas Gramedia',
            'deskripsi' => 'Sebuah buku pengantar filsafat Stoa yang relevan dengan kehidupan masa kini, untuk hidup lebih tenang dan mengurangi emosi negatif.',
            'ISBN' => '33333333',
            'stok_total' => 10,
            'stok_tersedia' => 10,
            'gambar_cover' => 'teras.jpg'
        ]);

        Book::create([
            'judul' => 'Atomic Habits',
            'Id_kategori' => $selfDev->Id_kategori,
            'penulis' => 'James Clear',
            'penerbit' => 'Penguin Random House',
            'deskripsi' => 'Cara mudah dan teruji untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk.',
            'ISBN' => '44444444',
            'stok_total' => 7,
            'stok_tersedia' => 7,
            'gambar_cover' => 'habits.jpg'
        ]);

        Book::create([
            'judul' => 'Sapiens',
            'Id_kategori' => $sains->Id_kategori,
            'penulis' => 'Yuval Noah Harari',
            'penerbit' => 'Harper',
            'deskripsi' => 'Buku ini melacak umat manusia dari zaman batu hingga saat ini.',
            'ISBN' => '55555555',
            'stok_total' => 4,
            'stok_tersedia' => 4,
            'gambar_cover' => 'sapiens.jpg'
        ]);
    }
}
