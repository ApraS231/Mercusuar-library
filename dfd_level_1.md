# DFD Level 1 (Diagram Utama) - Mercusuar Library

Berikut adalah diagram utama (Level 1) yang disederhanakan dengan mendekomposisikan alur proses yang detail ke diagram Level 2. Diagram ini berfokus pada interaksi tingkat tinggi antara entitas, proses utama, dan data store.

```mermaid
graph TD
    %% Styling (Hitam Putih / Monochrome)
    classDef proses fill:#FFFFFF,stroke:#000000,stroke-width:2px,color:#000000;
    classDef entitas fill:#FFFFFF,stroke:#000000,stroke-width:2px,color:#000000;
    classDef datastore fill:#FFFFFF,stroke:#000000,stroke-width:2px,color:#000000;

    %% External Entities
    User[Pengguna Mahasiswa]:::entitas
    Admin[Admin Perpustakaan]:::entitas
    KepalaPerpus[Kepala Perpustakaan]:::entitas
    Scheduler[Cron Job Pengecekan]:::entitas

    %% Processes
    P1((1.0 Autentikasi &<br>Kelola Pengguna)):::proses
    P2((2.0 Kelola Buku &<br>Kategori)):::proses
    P3((3.0 Katalog &<br>Pencarian Buku)):::proses
    P4((4.0 Sirkulasi<br>Peminjaman)):::proses
    P5((5.0 Pengecekan<br>Overdue)):::proses
    P6((6.0 Laporan &<br>Monitoring)):::proses

    %% Data Stores
    D1[D1. Tabel User]:::datastore
    D2[D2. Tabel Buku]:::datastore
    D3[D3. Tabel Peminjaman]:::datastore
    D4[D4. Tabel Review]:::datastore
    D5[D5. Tabel Kategori]:::datastore

    %% Layout Constraints (Forces Top-to-Bottom Rank Structure)
    User ~~~ P1
    Admin ~~~ P2
    KepalaPerpus ~~~ P6
    Scheduler ~~~ P5
    
    P1 ~~~ D1
    P2 ~~~ D2
    P3 ~~~ D4
    P4 ~~~ D3
    P6 ~~~ D3

    %% Flows - Process 1.0 (Autentikasi & Kelola Pengguna)
    User -- "Kredensial Login & Registrasi" --> P1
    Admin -- "Kredensial & Update User" --> P1
    KepalaPerpus -- "Kredensial Login" --> P1
    P1 -- "Data User (Write/Read)" --> D1
    D1 -- "Data Profil & Sesi" --> P1
    P1 -- "Sesi & Hak Akses" --> User
    P1 -- "Sesi & Hak Akses" --> Admin
    P1 -- "Sesi & Hak Akses" --> KepalaPerpus

    %% Flows - Process 2.0 (Kelola Buku & Kategori)
    Admin -- "Input Data Buku & Kategori" --> P2
    P2 -- "Data Buku (Write)" --> D2
    P2 -- "Data Kategori (Write)" --> D5
    D2 -- "Data Buku" --> P2
    D5 -- "Data Kategori" --> P2
    P2 -- "Konfirmasi Sukses" --> Admin

    %% Flows - Process 3.0 (Katalog & Pencarian Buku)
    User -- "Filter Cari & Review" --> P3
    D2 -- "Data Buku" --> P3
    D5 -- "Data Kategori" --> P3
    D4 -- "Data Review" --> P3
    P3 -- "Simpan Review Baru" --> D4
    P3 -- "Tampilan Katalog & Detail" --> User

    %% Flows - Process 4.0 (Sirkulasi Peminjaman)
    User -- "Data Booking Buku" --> P4
    Admin -- "Keputusan & Konfirmasi Pinjam" --> P4
    D1 -- "Data Akun & Status" --> P4
    D2 -- "Data Stok Buku" --> P4
    P4 -- "Data Peminjaman (Write/Read)" --> D3
    D3 -- "Data Peminjaman" --> P4
    P4 -- "Update Stok Buku" --> D2
    P4 -- "Update Status Akun" --> D1
    P4 -- "Status Booking & Notifikasi" --> User
    P4 -- "Daftar Antrean Booking" --> Admin

    %% Flows - Process 5.0 (Pengecekan Overdue)
    Scheduler -- "Trigger Pengecekan Harian" --> P5
    D3 -- "Data Peminjaman Jatuh Tempo" --> P5
    P5 -- "Update Transaksi Overdue" --> D3
    P5 -- "Update Status Akun Dibatasi" --> D1
    P5 -- "Log Pembaruan Overdue" --> Scheduler

    %% Flows - Process 6.0 (Laporan & Monitoring)
    KepalaPerpus -- "Parameter Laporan" --> P6
    D3 -- "Data Peminjaman" --> P6
    D2 -- "Data Buku" --> P6
    D1 -- "Data Anggota" --> P6
    P6 -- "Dashboard Statistik & Laporan" --> KepalaPerpus
```
