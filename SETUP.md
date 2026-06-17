# Panduan Setup Project - Mercusuar Library

Dokumentasi ini menjelaskan langkah-langkah untuk menyiapkan dan menjalankan aplikasi **Mercusuar Library** di lingkungan lokal menggunakan Laragon/MySQL di Windows.

---

## 🛠️ Prasyarat Sistem

Sebelum memulai, pastikan perangkat Anda telah terpasang:
- **PHP >= 8.2** (dilengkapi ekstensi pdo, gd, fileinfo, dll)
- **Composer** (Dependency Manager untuk PHP)
- **Node.js & NPM** (untuk build assets Vite)
- **MySQL / MariaDB** (melalui Laragon atau server lokal lainnya)

---

## 🚀 Langkah Instalasi

Ikuti langkah-langkah di bawah ini secara berurutan:

### 1. Salin Environment Configuration
Salin berkas konfigurasi environtment bawaan dari `.env.example` ke `.env`:
```bash
cp .env.example .env
```
*Catatan: Konfigurasi default di dalam `.env` sudah disesuaikan untuk Laragon dengan database `db_mercusuar`, user `root`, dan tanpa password.*

### 2. Instal Dependensi PHP
Jalankan perintah berikut untuk menginstal semua library Laravel yang diperlukan:
```bash
composer install
```

### 3. Generate Application Key
Jalankan perintah ini untuk membuat key enkripsi aplikasi baru:
```bash
php artisan key:generate
```

### 4. Instal Dependensi Frontend
Instal paket Node.js untuk aset tampilan:
```bash
npm install
```

### 5. Buat Database & Jalankan Migrasi + Seeder
1. Buat database baru di MySQL dengan nama **`db_mercusuar`**.
2. Jalankan perintah migrasi tabel beserta pengisian data awal (Seeder) termasuk Kategori Buku, User default, dan koleksi buku awal:
   ```bash
   php artisan migrate:fresh --seed
   ```

### 6. Hubungkan Storage (PENTING untuk Gambar Buku)
Agar gambar cover buku lokal yang diunggah ke folder `storage/app/public` dapat diakses oleh browser lewat URL `/storage/...`, Anda perlu membuat symbolic link (shortcut):
```bash
php artisan storage:link
```
> ⚠️ **Catatan Penting untuk Windows / Laragon:**
> Jika perintah di atas gagal atau menampilkan pesan error hak akses, buka terminal (PowerShell atau Command Prompt) dengan hak akses **Administrator** (Run as Administrator), navigasikan ke direktori proyek, lalu jalankan kembali perintah tersebut.

---

## 🔄 Alur Setelah Melakukan Git Pull

Setiap kali Anda menarik perubahan kode terbaru dari repositori Git (`git pull`), jalankan perintah berikut untuk memastikan dependensi, konfigurasi, dan database Anda sinkron:

1. **Sinkronkan Dependensi PHP & Frontend** (jika ada pembaruan library/paket):
   ```bash
   composer install
   npm install
   ```

2. **Jalankan Migrasi Database Baru** (jika ada penambahan tabel atau kolom baru):
   ```bash
   php artisan migrate
   ```

3. **Bersihkan Cache Konfigurasi & Rute**:
   ```bash
   php artisan optimize:clear
   ```

---

## 💻 Cara Menjalankan Aplikasi

Jalankan dua terminal terpisah untuk mengaktifkan server backend dan compiler frontend:

1. **Terminal 1: Server Backend PHP**
   ```bash
   php artisan serve
   ```
   Aplikasi akan berjalan di alamat: [http://127.0.0.1:8000](http://127.0.0.1:8000)

2. **Terminal 2: Compiler Frontend (Vite)**
   ```bash
   npm run dev
   ```

---

## 🔑 Akun Login Bawaan (Default Credentials)

Gunakan akun di bawah ini untuk menguji berbagai hak akses setelah melakukan seeding:

| Peran (Role) | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@mercusuar.com` | `password` | Mengelola buku, kategori, sirkulasi, dan pengguna. |
| **Kepala Perpustakaan** | `kepala@mercusuar.com` | `password` | Melihat laporan sirkulasi dan dashboard pemantauan. |
| **Anggota Biasa (Andi)** | `andi@gmail.com` | `password` | Meminjam buku, melihat katalog, & memberikan ulasan. |
| **Anggota Biasa (Budi)** | `budi@gmail.com` | `password` | Meminjam buku, melihat katalog, & memberikan ulasan. |
