# Software Architecture Specification (SAS)
## Mercusuar Library System

Dokumen *Software Architecture Specification* (SAS) ini memberikan deskripsi arsitektural tingkat tinggi mengenai sistem perpustakaan digital **Mercusuar Library**. Dokumen ini dirancang untuk menjelaskan struktur arsitektur sistem, skema database, kontrol keamanan, serta alur proses bisnis utama secara mendalam.

---

## 1. Tinjauan Umum Sistem

**Mercusuar Library** adalah sistem manajemen perpustakaan digital terintegrasi yang memudahkan pengguna untuk mencari buku, membaca ulasan, melakukan peminjaman/booking secara online dengan sistem pengantaran fisik, serta menulis ulasan. Sistem juga menyediakan panel administrasi komprehensif bagi pustakawan (Admin) untuk mengelola data buku, kategori, transaksi peminjaman, dan status keanggotaan.

Aplikasi ini dibangun menggunakan arsitektur modern berbasis framework PHP **Laravel 11** dan **Laravel Livewire v3**. Kombinasi ini menghasilkan aplikasi web SPA (*Single Page Application*) reaktif yang efisien, tanpa memerlukan penulisan API RESTful terpisah atau framework JavaScript sisi klien yang rumit seperti Vue atau React.

---

## 2. Arsitektur Tingkat Tinggi (High-Level Architecture)

Sistem menggunakan pola arsitektur **Model-View-Controller (MVC)** yang disempurnakan oleh konsep **Livewire Component-State Driven**. Di bawah ini adalah gambaran alur komponen dan request lifecycle pada sistem Mercusuar Library:

```mermaid
graph TD
    Client[Browser Pengguna] -->|1. Request / Interaksi| Router[Laravel Web Router]
    Router -->|2. Filter Route| Middleware{Middleware Pipeline}
    
    subgraph Security Layer
        Middleware -->|auth| AuthMW[Autentikasi Breeze]
        Middleware -->|admin| AdminMW[EnsureUserIsAdmin]
        Middleware -->|status.check| StatusMW[CheckAccountStatus]
    end
    
    AuthMW & AdminMW & StatusMW -->|3. Kirim ke Komponen| Livewire[Livewire Component / Volt Class]
    
    subgraph Data & Logic Layer
        Livewire -->|4. Query & Manipulasi| Model[Eloquent Models]
        Model -->|5. Akses Tabel| DB[(Database)]
    end
    
    Livewire -->|6. Render dengan State| View[Blade View Engine + TailwindCSS]
    View -->|7. Kirim DOM Diff / HTML| Client
```

### Keterangan Arsitektur:
1. **Client/Browser**: Melakukan pengiriman HTTP request atau interaksi AJAX tersembunyi yang digenerate oleh Livewire Javascript Assets.
2. **Laravel Router**: Menentukan rute web (misalnya `/dashboard` atau `/admin/transactions`) dan mengarahkan ke komponen Livewire yang tepat.
3. **Middleware Pipeline**:
   - `auth`: Memastikan pengguna telah masuk log.
   - `admin`: Membatasi akses rute administratif khusus untuk admin.
   - `status.check`: Memeriksa apakah akun pengguna sedang dibatasi (status `dibatasi`). Jika ya, request diblokir dengan kode HTTP 403.
4. **Livewire Component & Volt**: Mengontrol logika bisnis, validasi masukan, state properties, dan berkomunikasi dengan Eloquent Model.
5. **Eloquent Model**: Mengabstraksikan struktur database menjadi objek PHP yang mudah dikueri.
6. **Blade View + TailwindCSS**: Merender struktur antarmuka secara dinamis berdasarkan state terbaru dari komponen Livewire.

---

## 3. Skema Database & Entity-Relationship Diagram (ERD)

Sistem ini didukung oleh database relasional yang terdiri dari lima entitas utama: `users`, `books`, `categories`, `peminjamans`, dan `reviews`.

### Diagram Hubungan Entitas (ERD)

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        string role "admin | user"
        string status_akun "aktif | dibatasi"
        text alamat
        string no_telepon
        string remember_token
        timestamps timestamps
    }
    
    BOOKS {
        bigint id PK
        string judul
        string penulis
        string penerbit
        text deskripsi
        string isbn UK
        string gambar_cover
        integer stok_total
        integer stok_tersedia
        bigint category_id FK
        timestamps timestamps
    }
    
    CATEGORIES {
        bigint id PK
        string nama_kategori UK
        timestamps timestamps
    }
    
    PEMINJAMANS {
        bigint id PK
        bigint user_id FK
        bigint book_id FK
        string status "Pending | Disetujui | Ditolak | Diproses | Diantar | Diterima | Dikembalikan | Overdue"
        text alamat_pengantaran
        datetime jadwal_pengantaran_usulan
        datetime jadwal_pengantaran_disetujui
        datetime tgl_booking
        datetime tgl_diterima
        date tgl_jatuh_tempo
        datetime tgl_dikembalikan
        timestamps timestamps
    }
    
    REVIEWS {
        bigint id PK
        bigint user_id FK
        bigint book_id FK
        tinyint rating "1 - 5"
        text komentar
        timestamps timestamps
    }

    USERS ||--o{ PEMINJAMANS : "memiliki"
    USERS ||--o{ REVIEWS : "memberikan"
    CATEGORIES ||--o{ BOOKS : "mengklasifikasikan"
    BOOKS ||--o{ PEMINJAMANS : "dilibatkan dalam"
    BOOKS ||--o{ REVIEWS : "menerima"
```

### Relasi & Aturan Database (Database Constraints):
1. **`users` -> `peminjamans`**: Relasi `One-to-Many` (Satu user memiliki banyak peminjaman). Penghapusan user dibatasi (`onDelete('restrict')`) jika user tersebut masih memiliki riwayat peminjaman untuk menjaga integritas data transaksi.
2. **`books` -> `peminjamans`**: Relasi `One-to-Many`. Penghapusan buku dibatasi (`onDelete('restrict')`) jika buku terkait sedang dalam status dipinjam atau terdapat dalam catatan peminjaman.
3. **`categories` -> `books`**: Relasi `One-to-Many`. Jika kategori dihapus, kolom `category_id` pada buku yang bersangkutan diatur menjadi kosong (`onDelete('set null')`).
4. **`reviews`**: Terikat ke `users` dan `books` dengan relasi cascade (`onDelete('cascade')`). Jika buku atau user dihapus, ulasan terkait otomatis terhapus.

---

## 4. Keamanan & Kontrol Akses (Security & Access Control)

Sistem menerapkan prinsip *Least Privilege* (hak istimewa minimum) melalui kombinasi otentikasi berbasis peran (Role-Based Access Control) dan validasi status akun:

### 4.1 Peran Pengguna (Roles)
Didefinisikan dalam `App\Enums\Role`:
- **`admin`**: Memiliki hak akses penuh untuk melakukan operasi CRUD data buku, manajemen kategori, verifikasi transaksi (penerimaan, pengiriman, pengembalian), serta pengubahan status dan peran user lain.
- **`user`**: Anggota perpustakaan yang hanya dapat menjelajahi katalog buku, mengajukan peminjaman (booking), mengonfirmasi penerimaan buku di alamatnya, dan menulis ulasan untuk buku yang telah sukses dikembalikannya.

### 4.2 Status Akun (Account Status)
Didefinisikan dalam `App\Enums\StatusAkun`:
- **`aktif`**: Status default keanggotaan. User diizinkan melakukan pencarian dan pengajuan peminjaman buku baru.
- **`dibatasi`**: Akun pengguna dibatasi karena memiliki keterlambatan pengembalian buku (status transaksi `Overdue`). Pengguna dengan status ini **tidak diizinkan** melakukan peminjaman buku baru dan seluruh rute non-publik mereka diblokir oleh middleware `CheckAccountStatus` (HTTP 403).

### 4.3 Pipa Perlindungan Middleware (Middleware Pipeline)
Rute-rute aplikasi dilindungi oleh filter middleware berikut:
- **`auth`**: Memastikan user sudah login.
- **`admin` (`EnsureUserIsAdmin`)**:
  - Mengecek `$request->user()->role === Role::Admin`.
  - Jika bukan admin, mengalihkan ke `/dashboard` dengan pesan error "Anda tidak memiliki hak akses admin."
- **`status.check` (`CheckAccountStatus`)**:
  - Mengecek `$request->user()->status_akun !== StatusAkun::Aktif`.
  - Jika akun dibatasi, menghentikan request dengan respons `abort(403, 'Akun Anda sedang DIBATASI...')`.

---

## 5. Alur & Diagram Sekuens Utama (Key System Flows)

### 5.1 Alur Booking Buku (Peminjaman Baru)
Alur ini memastikan pengguna berstatus aktif meminjam buku yang tersedia secara aman menggunakan mekanisme transaksi database guna mencegah *race conditions* pada stok buku.

```mermaid
sequenceDiagram
    autonumber
    actor User as Anggota (User)
    participant Detail as BookDetail Livewire Component
    participant DB as Database (Transaction)

    User->>Detail: Klik "Booking Buku" & Isi Alamat + Jadwal
    Note over Detail: Validasi Input Alamat & Jadwal Usulan
    
    Detail->>DB: Cek Stok Buku (stok_tersedia > 0)
    DB-->>Detail: Kembalikan Nilai Stok
    
    alt Stok Habis (<= 0)
        Detail-->>User: Tampilkan Flash Error: Stok Habis
    else Stok Tersedia
        Detail->>DB: Cek Status Akun User
        DB-->>Detail: Status Akun (Aktif/Dibatasi)
        
        alt Status Akun Dibatasi
            Detail-->>User: Tampilkan Flash Error: Akun Dibatasi
        else Status Akun Aktif
            Detail->>DB: Hitung Peminjaman Berstatus 'Diterima'
            DB-->>Detail: Jumlah Pinjaman Aktif
            
            alt Jumlah Pinjaman >= 3
                Detail-->>User: Tampilkan Flash Error: Maksimum 3 Buku
            else Lolos Semua Validasi
                Detail->>DB: Mulai Transaksi (DB::transaction)
                Detail->>DB: Kurangi stok_tersedia buku sebesar 1
                Detail->>DB: Buat data baru di tabel `peminjamans` (Status: Pending)
                Detail->>DB: Commit Transaksi
                DB-->>Detail: Sukses
                Detail-->>User: Alihkan ke /my-loans dengan Pesan Sukses
            end
        end
    end
```

---

### 5.2 Alur Pengiriman & Penerimaan Buku
Berikut adalah siklus perubahan status peminjaman dari pertama kali diajukan hingga diterima secara fisik oleh pengguna:

```mermaid
stateDiagram-v2
    [*] --> Pending : User mengajukan booking
    
    state Admin_Actions <<choice>>
    Pending --> Admin_Actions : Evaluasi oleh Admin
    
    Admin_Actions --> Ditolak : Admin menolak booking (Stok buku dikembalikan +1)
    Admin_Actions --> Disetujui : Admin menyetujui booking
    
    Disetujui --> Diproses : Admin memproses pengemasan
    Diproses --> Diantar : Kurir mengirimkan buku ke alamat user
    
    Diantar --> Diterima : Anggota klik "Konfirmasi Diterima" via /my-loans
    Note right of Diterima
        - Catat tgl_diterima = Sekarang
        - Hitung tgl_jatuh_tempo = Sekarang + 7 hari
    end Note

    Diterima --> Dikembalikan : Admin memverifikasi pengembalian fisik buku
    Note right of Dikembalikan
        - Catat tgl_dikembalikan = Sekarang
        - Stok buku bertambah +1
        - Cek pinjaman overdue lain milik user
        - Jika tidak ada overdue lain, status_akun -> Aktif
    end Note

    Diterima --> Overdue : Tanggal jatuh tempo terlewati (Dicek oleh Cron Job Harian)
    Note right of Overdue
        - Status Peminjaman diubah -> Overdue
        - Status Akun User diubah -> Dibatasi
    end Note
    
    Overdue --> Dikembalikan : Pengguna mengembalikan buku yang terlambat
    Ditolak --> [*]
    Dikembalikan --> [*]
```

---

### 5.3 Alur Konfirmasi Pengembalian Buku & Pemulihan Akun
Ketika buku dikembalikan secara fisik ke perpustakaan, pustakawan (Admin) memicu proses pengembalian yang secara otomatis memperbarui stok dan mengevaluasi status akun anggota:

```mermaid
sequenceDiagram
    autonumber
    actor Admin
    participant Mgr as ManagePeminjaman Livewire Component
    participant DB as Database

    Admin->>Mgr: Klik "Konfirmasi Pengembalian" (ID Transaksi)
    Mgr->>DB: Ambil Peminjaman dengan Relasi User & Book
    DB-->>Mgr: Data Peminjaman Lengkap
    
    Mgr->>DB: Update Peminjaman (status = Dikembalikan, tgl_dikembalikan = now())
    Mgr->>DB: Tambah stok_tersedia buku (+1)
    
    Mgr->>DB: Hitung peminjaman Overdue lain milik User tersebut
    DB-->>Mgr: Jumlah Peminjaman Overdue Lainnya (N)
    
    alt N == 0 (Tidak ada pinjaman overdue lain)
        Mgr->>DB: Update status_akun User menjadi 'aktif'
        Note over DB: Keanggotaan dipulihkan otomatis
    else N > 0
        Note over Mgr: Akun tetap 'dibatasi' karena memiliki buku overdue lain
    end
    
    Mgr-->>Admin: Kirim Umpan Balik Sukses ke UI
```

---

### 5.4 Alur Pengecekan Keterlambatan Otomatis (Cron Job/Scheduler)
Sistem memiliki mekanisme penegakan aturan otomatis untuk melacak peminjaman yang terlambat dikembalikan. Tugas terjadwal (*scheduled task*) berjalan setiap hari:

```mermaid
sequenceDiagram
    autonumber
    participant Cron as Scheduler (Daily 00:00)
    participant Cmd as CheckOverdueLoans Command
    participant DB as Database
    participant Log as System Log (laravel.log)

    Cron->>Cmd: Jalankan Command `php artisan app:check-overdue-loans`
    Cmd->>DB: Cari Peminjaman (status = 'Diterima' AND tgl_jatuh_tempo < Hari_Ini)
    DB-->>Cmd: List Peminjaman Overdue

    alt List Kosong
        Cmd-->>Cron: Berhenti (Output: Tidak ada peminjaman overdue)
    else Ditemukan Peminjaman Overdue (M)
        loop Setiap Transaksi Overdue
            Cmd->>DB: Ubah status peminjaman menjadi 'Overdue'
            Cmd->>Log: Catat log Peminjaman ID & User ID Overdue
        end
        Cmd->>DB: Ekstrak semua User ID yang memiliki transaksi overdue tersebut
        Cmd->>DB: Update status_akun User terkait menjadi 'dibatasi'
        DB-->>Cmd: Sukses Update
        Cmd-->>Cron: Berhenti (Output: Akun untuk M user dibatasi)
    end
```

---
*Dokumen ini merupakan bagian dari spesifikasi teknis Mercusuar Library. Segala perubahan desain arsitektural wajib didokumentasikan dan disetujui oleh tim pengembang.*
