# Dokumentasi Alur Data Flow Diagram (DFD) - Mercusuar Library

Dokumen ini berisi rancangan alur data menggunakan **Data Flow Diagram (DFD)** Level 0 (Context Diagram), Level 1, dan Level 2 yang diimplementasikan menggunakan **Mermaid**. Rancangan ini disesuaikan dengan arsitektur sistem informasi perpustakaan **Mercusuar Library** dengan aturan visual hitam-putih (monochrome), jalur alur **tegak lurus/orthogonal (tanpa kelokan melengkung atau diagonal)**, serta bentuk simbol yang sesuai standar:
*   **External Entity:** Kotak/Persegi panjang (`[Nama]`).
*   **Process:** Lingkaran/Oval (`((Nama))`).
*   **Data Store:** Dua garis sejajar (`label` dengan garis unicode `───────`).
*   **Data Flow:** Panah lurus orthogonal (`-->`).

---

## 1. Pemetaan Simbol DFD ke Mermaid

Pemetaan elemen DFD ke kode Mermaid menggunakan class styling khusus hitam-putih flat:

| Elemen DFD | Deskripsi | Representasi Mermaid | Contoh Kode |
| :--- | :--- | :--- | :--- |
| **External Entity** | Entitas luar yang mengirim atau menerima data | Kotak hitam-putih tajam | `Anggota["Nama"]:::entity` |
| **Process** | Proses pengolahan data | Lingkaran sempurna | `Sistem(("Nama")):::process` |
| **Data Store** | Tempat penyimpanan data (Database/File) | Dua garis horizontal sejajar | `D1["──────────<br>Nama<br>──────────"]` + `style D1 fill:none,stroke:none` |
| **Data Flow** | Arah aliran data | Panah lurus orthogonal | `A --> B` |

---

## 2. DFD Level 0 (Context Diagram)

Context Diagram mendefinisikan batas sistem secara garis besar, menggambarkan alur keluar masuknya data secara langsung (tanpa template kata "Kirim"/"Terima") antara sistem tunggal **Sistem Informasi Perpustakaan** dengan tiga entitas eksternal.

### Gambar Diagram DFD Level 0
![DFD Level 0](./dfd_level0.png)

### Kode Mermaid - DFD Level 0
```mermaid
%%{init: {'flowchart': {'curve': 'stepBefore'}}}%%
graph TD
    classDef entity fill:#fff,stroke:#000,stroke-width:2px;
    classDef process fill:#fff,stroke:#000,stroke-width:2px;
    
    Anggota["Anggota<br>(User/Member)"]:::entity
    Petugas["Petugas<br>(Admin)"]:::entity
    Kepala["Kepala Perpustakaan<br>(KepalaPerpus)"]:::entity
    Sistem(("0.0<br>Sistem Informasi<br>Perpustakaan<br>(Mercusuar Library)")):::process
    
    Anggota -->|"name, email, password, no_telepon, alamat,<br>user_id, book_id, tgl_booking"| Sistem
    Sistem -->|"role, status_akun, judul, gambar_cover, stok_tersedia,<br>status, tgl_jatuh_tempo"| Anggota
    
    Petugas -->|"judul, penulis, isbn, stok_total,<br>id_peminjaman, status, tgl_jatuh_tempo, tgl_selesai"| Sistem
    Sistem -->|"booking pending (status='Pinjam'),<br>alert keterlambatan (status='Overdue')"| Petugas
    
    Sistem -->|"Laporan peminjaman harian/bulanan (peminjamans),<br>Laporan data user terdaftar (users)"| Kepala
```

---

## 3. DFD Level 1 (Dekomposisi Sistem)

Level 1 memecah proses utama menjadi tiga modul fungsional utama dengan mengintegrasikan penyimpanan data (data store) yang direpresentasikan dengan garis sejajar horizontal. Garis alur ditata tegak lurus dengan visual yang rapi.

### Gambar Diagram DFD Level 1
![DFD Level 1](./dfd_level1.png)

### Kode Mermaid - DFD Level 1
```mermaid
%%{init: {'flowchart': {'curve': 'stepBefore'}}}%%
graph LR
    classDef entity fill:#fff,stroke:#000,stroke-width:2px;
    classDef process fill:#fff,stroke:#000,stroke-width:2px;

    %% Entities
    Anggota1["Anggota"]:::entity
    Anggota2["Anggota"]:::entity
    Petugas1["Petugas"]:::entity
    Petugas2["Petugas"]:::entity
    Kepala["Kepala Perpustakaan"]:::entity

    %% Processes
    P1(("1.0<br>Kelola Data Master<br>(User & Buku)")):::process
    P2(("2.0<br>Transaksi Peminjaman<br>& Pengembalian")):::process
    P3(("3.0<br>Penyusunan Laporan<br>& Dashboard")):::process

    P1 ~~~ P2 ~~~ P3

    %% Data Stores
    D1_Users1["────────────────<br>   tb_users   <br>────────────────"]
    D1_Users2["────────────────<br>   tb_users   <br>────────────────"]
    D2_Books1["────────────────<br>   tb_books   <br>────────────────"]
    D2_Books2["────────────────<br>   tb_books   <br>────────────────"]
    D3_Loans1["────────────────<br>   tb_loans   <br>────────────────"]
    D3_Loans2["────────────────<br>   tb_loans   <br>────────────────"]

    style D1_Users1 fill:none,stroke:none;
    style D1_Users2 fill:none,stroke:none;
    style D2_Books1 fill:none,stroke:none;
    style D2_Books2 fill:none,stroke:none;
    style D3_Loans1 fill:none,stroke:none;
    style D3_Loans2 fill:none,stroke:none;

    %% 1.0 Kelola Data Master
    Anggota1 -->|"name, email, password, no_telepon"| P1
    P1 -->|"name, email, password, no_telepon"| D1_Users1
    Petugas1 -->|"judul, penulis, isbn, stok_total"| P1
    P1 -->|"judul, penulis, isbn, stok_total, stok_tersedia"| D2_Books1

    %% 2.0 Transaksi Peminjaman & Pengembalian
    Anggota2 -->|"user_id, book_id, tgl_booking"| P2
    Petugas2 -->|"tgl_disetujui, tgl_selesai"| P2
    D1_Users2 -->|"status_akun"| P2
    P2 -->|"stok_tersedia"| D2_Books1
    P2 -->|"status, tgl_booking, tgl_disetujui, tgl_jatuh_tempo, tgl_selesai"| D3_Loans1

    %% 3.0 Laporan
    D1_Users2 -->|"name, email, role, status_akun"| P3
    D2_Books2 -->|"judul, penulis, isbn, stok_total"| P3
    D3_Loans2 -->|"status, tgl_booking, tgl_selesai"| P3
    P3 -->|"Laporan peminjaman & keanggotaan"| Kepala
```

---

## 4. DFD Level 2 (Detail Transaksi)

Level 2 memecah **Process 2.0 (Transaksi Peminjaman & Pengembalian)** menjadi 4 sub-proses spesifik yang menggambarkan alur penulisan, pembacaan, dan pembaruan field database. Proses digambar secara linier dari kiri ke kanan (2.1 -> 2.2 -> 2.3 -> 2.4) untuk visualisasi yang rapi dan teratur dengan garis tegak lurus.

### Gambar Diagram DFD Level 2
![DFD Level 2](./dfd_level2.png)

### Kode Mermaid - DFD Level 2
```mermaid
%%{init: {'flowchart': {'curve': 'stepBefore'}}}%%
graph LR
    classDef entity fill:#fff,stroke:#000,stroke-width:2px;
    classDef process fill:#fff,stroke:#000,stroke-width:2px;

    %% Processes in a linear order (Left to Right)
    P2_1(("2.1<br>Pengajuan Booking<br>(Status: Pinjam)")):::process
    P2_2(("2.2<br>Persetujuan Peminjaman<br>(Status: Disetujui)")):::process
    P2_3(("2.3<br>Konfirmasi Pengembalian<br>(Status: Selesai)")):::process
    P2_4(("2.4<br>Deteksi Terlambat<br>(Status: Overdue)")):::process

    P2_1 ~~~ P2_2 ~~~ P2_3 ~~~ P2_4

    %% Duplicated Entities to keep lines straight
    Anggota1["Anggota (User)"]:::entity
    Anggota2["Anggota (User)"]:::entity
    Anggota3["Anggota (User)"]:::entity
    Petugas1["Petugas (Admin)"]:::entity
    Petugas2["Petugas (Admin)"]:::entity

    %% Duplicated Data Stores
    D_Books1["────────────────<br>   books table   <br>────────────────"]
    D_Books2["────────────────<br>   books table   <br>────────────────"]
    D_Loans1["────────────────<br>peminjamans table<br>────────────────"]
    D_Loans2["────────────────<br>peminjamans table<br>────────────────"]

    style D_Books1 fill:none,stroke:none;
    style D_Books2 fill:none,stroke:none;
    style D_Loans1 fill:none,stroke:none;
    style D_Loans2 fill:none,stroke:none;

    %% Column 1: Process 2.1
    Anggota1 -->|"user_id, book_id, tgl_booking"| P2_1
    D_Books1 -->|"stok_tersedia"| P2_1
    P2_1 -->|"user_id, book_id, status='Pinjam', tgl_booking"| D_Loans1

    %% Column 2: Process 2.2
    Petugas1 -->|"tgl_disetujui, tgl_jatuh_tempo"| P2_2
    P2_2 -->|"status='Disetujui', tgl_disetujui, tgl_jatuh_tempo"| D_Loans1
    P2_2 -->|"stok_tersedia (stok - 1)"| D_Books1
    P2_2 -->|"status='Disetujui', tgl_jatuh_tempo"| Anggota2

    %% Column 3: Process 2.3
    Petugas2 -->|"tgl_selesai"| P2_3
    P2_3 -->|"status='Selesai', tgl_selesai"| D_Loans2
    P2_3 -->|"stok_tersedia (stok + 1)"| D_Books2

    %% Column 4: Process 2.4
    D_Loans2 -->|"tgl_selesai, tgl_jatuh_tempo"| P2_4
    P2_4 -->|"status='Overdue'"| D_Loans2
    P2_4 -->|"status='Overdue', denda"| Anggota3
```
