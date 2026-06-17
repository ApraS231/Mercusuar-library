# DFD Level 2 (Rincian Proses Kompleks) - Mercusuar Library

Dokumen ini berisi rincian Level 2 untuk dua proses paling kompleks di sistem: **Proses 4.0 (Sirkulasi Peminjaman)** dan **Proses 5.0 (Pengecekan Overdue)**.

---

## 1. DFD Level 2A: Rincian Proses 4.0 (Sirkulasi Peminjaman)

Menggambarkan alur mulai dari validasi booking oleh user, persetujuan admin, hingga pengembalian buku.

```mermaid
graph TD
    %% Styling (Hitam Putih / Monochrome)
    classDef proses fill:#FFFFFF,stroke:#000000,stroke-width:2px,color:#000000;
    classDef entitas fill:#FFFFFF,stroke:#000000,stroke-width:2px,color:#000000;
    classDef datastore fill:#FFFFFF,stroke:#000000,stroke-width:2px,color:#000000;

    %% Entities
    User[Pengguna Mahasiswa]:::entitas
    Admin[Admin Perpustakaan]:::entitas

    %% Data Stores
    D1[D1. Tabel User]:::datastore
    D2[D2. Tabel Buku]:::datastore
    D3[D3. Tabel Peminjaman]:::datastore

    %% Sub-Processes
    P4_1((4.1 Validasi<br>Akun & Stok)):::proses
    P4_2((4.2 Proses<br>Booking Buku)):::proses
    P4_3((4.3 Persetujuan &<br>Penolakan Admin)):::proses
    P4_4((4.4 Proses<br>Pengembalian Buku)):::proses

    %% Flows - 4.1 Validasi
    User -- "Kirim Permintaan Booking" --> P4_1
    D1 -- "Baca Status Akun & Batas Pinjam" --> P4_1
    D2 -- "Baca Jumlah Stok Tersedia" --> P4_1
    P4_1 -- "Hasil Validasi Lolos" --> P4_2

    %% Flows - 4.2 Booking
    P4_2 -- "Kurangi Stok Buku (-1)" --> D2
    P4_2 -- "Simpan Transaksi (Status: Pinjam)" --> D3
    P4_2 -- "Notifikasi Sukses Booking" --> User

    %% Flows - 4.3 Approve/Reject
    Admin -- "Kirim Keputusan (Approve/Reject)" --> P4_3
    D3 -- "Baca Detail Booking Masuk" --> P4_3
    P4_3 -- "Update Status (Disetujui/Ditolak)" --> D3
    P4_3 -- "Kembalikan Stok Buku (Jika Ditolak)" --> D2
    P4_3 -- "Notifikasi Status Booking" --> User

    %% Flows - 4.4 Pengembalian
    Admin -- "Konfirmasi Pengembalian Buku" --> P4_4
    D3 -- "Baca Data Transaksi Aktif" --> P4_4
    P4_4 -- "Update Status (Selesai, tgl_selesai: now)" --> D3
    P4_4 -- "Kembalikan Stok Buku (+1)" --> D2
    P4_4 -- "Cek Overdue Lain & Aktifkan Akun" --> D1
    P4_4 -- "Notifikasi Pengembalian Selesai" --> User
```

---

## 2. DFD Level 2B: Rincian Proses 5.0 (Pengecekan Overdue)

Menggambarkan alur scheduler otomatis mengecek keterlambatan buku harian dan membatasi akun.

```mermaid
graph TD
    %% Styling (Hitam Putih / Monochrome)
    classDef proses fill:#FFFFFF,stroke:#000000,stroke-width:2px,color:#000000;
    classDef entitas fill:#FFFFFF,stroke:#000000,stroke-width:2px,color:#000000;
    classDef datastore fill:#FFFFFF,stroke:#000000,stroke-width:2px,color:#000000;

    %% Entities
    Scheduler[Cron Job Pengecekan]:::entitas

    %% Data Stores
    D1[D1. Tabel User]:::datastore
    D3[D3. Tabel Peminjaman]:::datastore

    %% Sub-Processes
    P5_1((5.1 Pemindaian<br>Jatuh Tempo)):::proses
    P5_2((5.2 Pembaruan<br>Status Overdue)):::proses
    P5_3((5.3 Pembatasan<br>Akun Anggota)):::proses

    %% Flows - 5.1 Scan
    Scheduler -- "Trigger Harian (Pukul 00:00)" --> P5_1
    D3 -- "Query Status Disetujui & Lewat Jatuh Tempo" --> P5_1
    P5_1 -- "Kirim Daftar ID Transaksi Overdue" --> P5_2

    %% Flows - 5.2 Update Transaction
    P5_2 -- "Update Status ke Overdue" --> D3
    P5_2 -- "Kirim ID Anggota Pelanggar" --> P5_3

    %% Flows - 5.3 Restrict Account
    P5_3 -- "Update status_akun ke Dibatasi" --> D1
    P5_3 -- "Log Pembaruan Overdue & Akun" --> Scheduler
```
