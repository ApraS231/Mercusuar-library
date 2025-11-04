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

        // 1. BUAT USER ADMIN
        User::create([
            'name' => 'Admin Mercusuar',
            'email' => 'admin@mercusuar.com',
            'password' => Hash::make('password'), // password default: password
            'role' => Role::Admin,
            'status_akun' => StatusAkun::Aktif,
            'alamat' => 'Jl. Admin No. 1, Jakarta',
            'no_telepon' => '081234567890',
        ]);

        // 2. BUAT USER BIASA
        User::create([
            'name' => 'Anggota Biasa (Andi)',
            'email' => 'andi@gmail.com',
            'password' => Hash::make('password'), // password default: password
            'role' => Role::User,
            'status_akun' => StatusAkun::Aktif,
            'alamat' => 'Jl. Pengguna No. 10, Bandung',
            'no_telepon' => '081111111111',
        ]);

        User::create([
            'name' => 'Anggota Lain (Budi)',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'), // password default: password
            'role' => Role::User,
            'status_akun' => StatusAkun::Aktif,
            'alamat' => 'Jl. Pembaca No. 5, Surabaya',
            'no_telepon' => '082222222222',
        ]);

        // 3. BUAT DATA BUKU
        Book::create([
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'penerbit' => 'Bentang Pustaka',
            'deskripsi' => 'Novel yang menceritakan kehidupan 10 anak dari keluarga miskin yang bersekolah (SD dan SMP) di sebuah sekolah Muhammadiyah di Belitung yang penuh dengan keterbatasan.',
            'stok_total' => 5,
            'stok_tersedia' => 5,
            'gambar_cover' => 'https://placehold.co/400x600/EBF5FF/7F92B0?text=Laskar+Pelangi'
        ]);

        Book::create([
            'judul' => 'Bumi Manusia',
            'penulis' => 'Pramoedya Ananta Toer',
            'penerbit' => 'Hasta Mitra',
            'deskripsi' => 'Salah satu novel dalam tetralogi Pulau Buru. Menceritakan kisah Minke, seorang pemuda pribumi di era kolonial Belanda.',
            'stok_total' => 3,
            'stok_tersedia' => 3,
            'gambar_cover' => 'https://placehold.co/400x600/EBF5FF/7F92B0?text=Bumi+Manusia'
        ]);

        Book::create([
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'penerbit' => 'Kompas Gramedia',
            'deskripsi' => 'Sebuah buku pengantar filsafat Stoa yang relevan dengan kehidupan masa kini, untuk hidup lebih tenang dan mengurangi emosi negatif.',
            'stok_total' => 10,
            'stok_tersedia' => 10,
            'gambar_cover' => 'https://placehold.co/400x600/EBF5FF/7F92B0?text=Filosofi+Teras'
        ]);

        Book::create([
            'judul' => 'Atomic Habits',
            'penulis' => 'James Clear',
            'penerbit' => 'Penguin Random House',
            'deskripsi' => 'Cara mudah dan teruji untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk.',
            'stok_total' => 7,
            'stok_tersedia' => 7,
            'gambar_cover' => 'https://placehold.co/400x600/EBF5FF/7F92B0?text=Atomic+Habits'
        ]);

        Book::create([
            'judul' => 'Sapiens: Riwayat Singkat Umat Manusia',
            'penulis' => 'Yuval Noah Harari',
            'penerbit' => 'Harper',
            'deskripsi' => 'Buku ini melacak evolusi umat manusia dari zaman batu hingga saat ini.',
            'stok_total' => 4,
            'stok_tersedia' => 4,
            'gambar_cover' => 'https://placehold.co/400x600/EBF5FF/7F92B0?text=Sapiens'
        ]);
    }
}
