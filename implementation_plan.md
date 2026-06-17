# Implementasi Pembaruan Alur Peminjaman & Modul Kepala Perpus

Mengimplementasikan perubahan kode sistem berdasarkan [Implementation Plan.md](file:///c:/laragon/www/mercusuar-library/Implementation%20Plan.md): menyederhanakan siklus peminjaman (hapus skema pengantaran fisik), dan menambahkan role `kepala_perpus` beserta modul laporan.

---

## User Review Required

> [!IMPORTANT]
> **Migration Database**: Perubahan ini akan membuat migration baru yang menghapus kolom `alamat_pengantaran`, `jadwal_pengantaran_usulan`, `jadwal_pengantaran_disetujui` dari tabel `peminjamans`, serta me-rename kolom `tgl_diterima` → `tgl_disetujui` dan `tgl_dikembalikan` → `tgl_selesai`. **Data lama di kolom-kolom tersebut akan hilang.** Pastikan sudah di-backup jika diperlukan.

> [!WARNING]
> **Breaking Change pada Status**: Status `Pending`, `Diproses`, `Diantar`, `Diterima`, `Dikembalikan` dihapus dan diganti menjadi `Pinjam`, `Disetujui`, `Ditolak`, `Selesai`, `Overdue`. Data peminjaman lama yang menggunakan status lama harus di-migrasi nilainya.

## Open Questions

1. **Data Status Lama**: Apakah data peminjaman yang sudah ada di database perlu di-migrasi statusnya (misal `Pending` → `Pinjam`, `Diterima` → `Disetujui`, `Dikembalikan` → `Selesai`), atau boleh di-fresh seed saja?

2. **Middleware Kepala Perpus**: Implementation Plan menyebut `role:kepala_perpus` — apakah ini menggunakan middleware baru terpisah, atau memodifikasi `EnsureUserIsAdmin` agar mendukung parameter role dinamis?

---

## Proposed Changes

### Tahap 1: Pembaruan Enumerator (Enums)

#### [MODIFY] [Role.php](file:///c:/laragon/www/mercusuar-library/app/Enums/Role.php)
- Tambahkan case baru: `KepalaPerpus = 'kepala_perpus'`

#### [MODIFY] [StatusPeminjaman.php](file:///c:/laragon/www/mercusuar-library/app/Enums/StatusPeminjaman.php)
- **Hapus** case: `Pending`, `Diproses`, `Diantar`, `Diterima`, `Dikembalikan`
- **Tambah/Ganti** menjadi: `Pinjam = 'Pinjam'`, `Disetujui = 'Disetujui'`, `Ditolak = 'Ditolak'`, `Selesai = 'Selesai'`, `Overdue = 'Overdue'`

---

### Tahap 2: Pembaruan Database & Model

#### [NEW] Migration `simplify_peminjamans_table`
- Hapus kolom: `alamat_pengantaran`, `jadwal_pengantaran_usulan`, `jadwal_pengantaran_disetujui`
- Rename kolom: `tgl_diterima` → `tgl_disetujui`, `tgl_dikembalikan` → `tgl_selesai`
- Ubah default value kolom `status` ke `'Pinjam'`
- (Opsional) Migrasi data status lama ke status baru

#### [MODIFY] [Peminjaman.php](file:///c:/laragon/www/mercusuar-library/app/Models/Peminjaman.php)
- **Hapus** cast: `jadwal_pengantaran_usulan`, `jadwal_pengantaran_disetujui`
- **Ganti** cast: `tgl_diterima` → `tgl_disetujui` (datetime), `tgl_dikembalikan` → `tgl_selesai` (datetime)

---

### Tahap 3: Refactoring Logika Livewire (Sirkulasi Utama)

#### [MODIFY] [BookDetail.php](file:///c:/laragon/www/mercusuar-library/app/Livewire/Katalog/BookDetail.php)
- **Hapus** properti: `$alamat_pengantaran`, `$usulan_jadwal`
- **Hapus** logika default alamat di `mount()` (`if (auth()->user()->alamat)`)
- **Hapus** validasi `alamat_pengantaran` dan `usulan_jadwal` di `bookNow()`
- **Ubah** cek pinjaman aktif dari status `Diterima` → `Disetujui`
- **Ubah** cek duplikat dari `[Pending, Disetujui, Diproses, Diantar, Diterima]` → `[Pinjam, Disetujui]`
- **Ubah** status insert dari `StatusPeminjaman::Pending` → `StatusPeminjaman::Pinjam`
- **Hapus** field `alamat_pengantaran` dan `jadwal_pengantaran_usulan` dari `Peminjaman::create()`
- **Ubah** syarat review dari `StatusPeminjaman::Dikembalikan` → `StatusPeminjaman::Selesai` di `cekRiwayatReview()`

#### [MODIFY] [book-detail.blade.php](file:///c:/laragon/www/mercusuar-library/resources/views/livewire/katalog/book-detail.blade.php)
- **Hapus** seluruh blok form `alamat_pengantaran` (textarea, label, error — baris ~91-100)
- **Hapus** seluruh blok form `usulan_jadwal` / Flatpickr date picker (baris ~102-132)
- **Hapus** asset Flatpickr CSS/JS dan custom style-nya (baris ~3-20)

---

#### [MODIFY] [ManagePeminjaman.php](file:///c:/laragon/www/mercusuar-library/app/Livewire/Admin/Transactions/ManagePeminjaman.php)
- **Ubah** default filter dari `'Pending'` → `'Pinjam'`
- **Ubah** metode `approve($id)`:
  - Status → `StatusPeminjaman::Disetujui`
  - **Tambah**: set `tgl_disetujui = now()` dan `tgl_jatuh_tempo = now()->addDays(7)`
- **Hapus** metode `markAsDelivered()`
- **Ganti** metode `confirmReturn($id)` → `markAsDone($id)`:
  - Status → `StatusPeminjaman::Selesai`
  - Set `tgl_selesai = now()` (bukan `tgl_dikembalikan`)
  - Kembalikan stok buku (+1)
  - Cek pemulihan akun: cari pinjaman `Overdue` lain milik user

#### [MODIFY] [manage-peminjaman.blade.php](file:///c:/laragon/www/mercusuar-library/resources/views/livewire/admin/transactions/manage-peminjaman.blade.php)
- **Hapus** tampilan `alamat_pengantaran` di kolom Peminjam (baris ~67-69)
- **Update** badge status `match()`: hapus case `Pending`, `Diantar`, `Diterima` → tambah case `Pinjam`, `Selesai`
- **Update** tombol aksi:
  - `Pending` → `Pinjam` (tombol Approve/Reject)
  - **Hapus** blok `Disetujui` → `markAsDelivered` (tombol "Kirim")
  - **Hapus** blok `Diantar` → "Menunggu User"
  - **Ganti** blok `Diterima || Overdue` → `Disetujui || Overdue` → `markAsDone` (tombol "Selesaikan")

---

#### [MODIFY] [MyLoans.php](file:///c:/laragon/www/mercusuar-library/app/Livewire/User/MyLoans.php)
- **Hapus** metode `confirmReceipt($id)` seluruhnya
- **Tambah** properti `$activeTab = 'aktif'` untuk filter tab
- **Ubah** `render()`: filter berdasarkan `$activeTab`:
  - Tab "Aktif": status `Pinjam`, `Disetujui`, `Overdue`
  - Tab "Riwayat": status `Selesai`, `Ditolak`

#### [MODIFY] [my-loans.blade.php](file:///c:/laragon/www/mercusuar-library/resources/views/livewire/user/my-loans.blade.php)
- **Tambah** UI tab/filter: "Pinjaman Aktif" dan "Riwayat Pinjaman" di atas list
- **Update** `$statusConfig` match(): hapus case `Pending`, `Diproses`, `Diantar`, `Diterima`, `Dikembalikan` → ganti dengan `Pinjam`, `Disetujui`, `Selesai`
- **Hapus** tombol `confirmReceipt` ("Terima Buku") di desktop dan mobile
- **Ubah** tombol "Beri Ulasan": tampilkan untuk status `Selesai` (bukan `Dikembalikan`)
- **Update** sidebar decorative line: warna berdasarkan status baru

---

#### [MODIFY] [CheckOverdueLoans.php](file:///c:/laragon/www/mercusuar-library/app/Console/Commands/CheckOverdueLoans.php)
- **Ubah** query dari `status = StatusPeminjaman::Diterima` → `StatusPeminjaman::Disetujui`

---

### Tahap 4: Implementasi Fitur "Kepala Perpus"

#### [NEW] Middleware atau modifikasi untuk role `kepala_perpus`
- Buat middleware `EnsureUserIsKepalaPerpus` atau buat middleware generik `EnsureUserHasRole` yang menerima parameter role
- Daftarkan alias di [app.php](file:///c:/laragon/www/mercusuar-library/bootstrap/app.php)

#### [MODIFY] [web.php](file:///c:/laragon/www/mercusuar-library/routes/web.php)
- **Tambah** grup route baru untuk Kepala Perpus:
  ```php
  Route::middleware(['auth', 'role:kepala_perpus'])->group(function () {
      Route::get('/laporan-peminjaman', LaporanPeminjaman::class)->name('kepala-perpus.laporan');
  });
  ```

#### [NEW] [LaporanPeminjaman.php](file:///c:/laragon/www/mercusuar-library/app/Livewire/KepalaPerpus/LaporanPeminjaman.php)
- Komponen baru dengan properti: `$search`, `$startDate`, `$endDate`
- Logika render: query `Peminjaman::with(['user', 'book'])` + search relasional (nama user / judul buku) + filter `whereBetween('created_at', ...)` + paginasi 15
- Layout: `components.layouts.admin`

#### [NEW] [laporan-peminjaman.blade.php](file:///c:/laragon/www/mercusuar-library/resources/views/livewire/kepala-perpus/laporan-peminjaman.blade.php)
- UI tabel TailwindCSS dengan:
  - Input search `wire:model.live.debounce.500ms="search"`
  - Input date range `wire:model.live="startDate"` dan `endDate`
  - Tabel kolom: Nama Peminjam, Judul Buku, Tanggal, Status
  - Pagination links

---

### Tahap 5: Update Komponen Pendukung

#### [MODIFY] [Dashboard.php](file:///c:/laragon/www/mercusuar-library/app/Livewire/Admin/Dashboard.php)
- Ubah query `$pendingLoans` dari `StatusPeminjaman::Pending` → `StatusPeminjaman::Pinjam`

#### [MODIFY] [DatabaseSeeder.php](file:///c:/laragon/www/mercusuar-library/database/seeders/DatabaseSeeder.php)
- Tambahkan user Kepala Perpus:
  ```php
  User::create([
      'name' => 'Kepala Perpustakaan',
      'email' => 'kepala@mercusuar.com',
      'password' => Hash::make('password'),
      'role' => Role::KepalaPerpus,
      ...
  ]);
  ```

---

## Verification Plan

### Automated Tests
- `php artisan migrate` — pastikan migration berjalan tanpa error
- `php artisan db:seed --force` — pastikan seeder berjalan dengan enum baru

### Manual Verification
- Akses `/dashboard` sebagai user → coba booking buku (form tanpa alamat/jadwal)
- Akses `/admin/transactions` sebagai admin → test approve (cek `tgl_disetujui` dan `tgl_jatuh_tempo` terisi), reject, dan markAsDone
- Akses `/my-loans` sebagai user → verifikasi tab Aktif/Riwayat dan tombol Review pada status Selesai
- Akses `/laporan-peminjaman` sebagai kepala_perpus → test search dan date filter
- Jalankan `php artisan app:check-overdue-loans` → verifikasi deteksi overdue pada status `Disetujui`
