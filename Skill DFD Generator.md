# **Skill: DFD Generator (Level 0, Level 1, Level 2\)**

**Deskripsi:**

Instruksi ini membimbing AI Agent untuk bertindak sebagai System Analyst. Agent akan menelusuri struktur direktori, alur fitur aplikasi, dan skema database, lalu menghasilkan Data Flow Diagram (DFD) menggunakan sintaks Mermaid. Agent diasumsikan memiliki akses ke mcp mermaid untuk merender/memvalidasi diagram.

## **🛠️ Langkah 1: Eksplorasi & Analisis Sistem**

Sebelum membuat diagram, Agent **WAJIB** melakukan penelusuran (scanning) pada *codebase* menggunakan perintah internal (seperti membaca file atau *grep*).

1. **Analisis Database & Relasi (Data Stores):**  
   * Telusuri direktori app/Models/ dan database/migrations/.  
   * Identifikasi tabel-tabel utama (misalnya: users, books, peminjamans).  
2. **Analisis Aktor/Entitas (External Entities):**  
   * Telusuri file app/Enums/Role.php atau tabel users untuk melihat aktor yang terlibat (misal: Admin, User, Kepala Perpus).  
   * Identifikasi sistem pemicu otomatis (misal: Scheduler/Cron Job di app/Console/Commands/).  
3. **Analisis Alur Fitur (Processes):**  
   * Telusuri direktori Controller atau komponen Livewire (app/Livewire/).  
   * Identifikasi proses utama seperti Pencarian Buku, Booking/Peminjaman, Pengembalian, dan Laporan.

## **📐 Langkah 2: Aturan Standar Sintaks Mermaid untuk DFD**

Agent **WAJIB** menggunakan bentuk-bentuk spesifik berikut saat menulis blok mermaid agar diagram yang dihasilkan merepresentasikan DFD secara akurat, konsisten, dan dapat dibaca dengan jelas:

* **Entitas Luar (External Entity):**  
  * **Fungsi:** Mewakili pihak luar (aktor, sistem lain, atau departemen) yang memberikan data ke sistem atau menerima data dari sistem.  
  * **Bentuk Mermaid:** Kotak persegi panjang standar.  
  * **Sintaks:** NamaNode\[Nama Entitas\] (Contoh: User\[Pengguna Mahasiswa\])  
* **Proses (Process):**  
  * **Fungsi:** Mewakili transformasi data (di mana input diubah menjadi output). Setiap proses harus memiliki nomor unik dan kata kerja aktif.  
  * **Bentuk Mermaid:** Lingkaran murni atau oval (untuk menyesuaikan panjang teks).  
  * **Sintaks:** NamaNode((Nomor. Nama Proses)) (Contoh: P1((1.0 Proses Peminjaman)))  
* **Penyimpanan Data (Data Store):**  
  * **Fungsi:** Mewakili tabel dalam database, file, atau repositori tempat data disimpan untuk digunakan kembali.  
  * **Bentuk Mermaid:** Kotak persegi panjang biasa (sesuai instruksi spesifik untuk tidak menggunakan silinder/database bawaan). Untuk membedakannya dari Entitas Luar, sertakan ID unik dengan huruf 'D' (misal D1, D2).  
  * **Sintaks:** NamaNode\[D\#. Nama Tabel\] (Contoh: D1\[D1. Data Peminjaman\])  
* **Alur Data (Data Flow):**  
  * **Fungsi:** Menunjukkan pergerakan data dari satu bagian ke bagian lain. Alur data **wajib** diberi label teks untuk menjelaskan paket data apa yang sedang mengalir.  
  * **Bentuk Mermaid:** Garis panah berarah.  
  * **Sintaks:** NodeA \-- "Label Data yang Mengalir" \--\> NodeB (Contoh: User \-- "Data Login" \--\> P1)  
* **Tema/Gaya (Opsional namun Disarankan):** Tambahkan deklarasi classDef untuk membedakan elemen secara visual (misal: Proses berwarna kuning, Data Store berwarna hijau, Entitas berwarna biru).

## **🚀 Langkah 3: Eksekusi Pembuatan Diagram**

Jika pengguna memberikan prompt: *"Buatkan DFD dari sistem ini"*, Agent harus merespons dengan 3 blok Mermaid secara berurutan:

### **1\. DFD Level 0 (Diagram Konteks)**

**Fokus:** Interaksi antara entitas luar dengan sistem sebagai satu kesatuan.

**Instruksi Pembuatan:**

* Buat 1 proses utama di tengah bernama 0.0 Sistem \[Nama Aplikasi\].  
* Petakan semua Aktor (Admin, User, Kepala Perpus, Scheduler) di sekeliling sistem.  
* Tarik garis panah *input* (data yang masuk dari aktor ke sistem) dan *output* (informasi yang keluar dari sistem ke aktor).  
* **Catatan:** Tidak boleh ada *Data Store* pada Level 0\.

### **2\. DFD Level 1 (Diagram Utama)**

**Fokus:** Dekomposisi sistem menjadi modul-modul fungsional besar dan keterkaitannya dengan tabel database.

**Instruksi Pembuatan:**

* Pecah sistem menjadi proses utama (misal: 1.0 Autentikasi, 2.0 Kelola Buku, 3.0 Sirkulasi Pinjam, 4.0 Pengecekan Overdue, 5.0 Laporan).  
* Petakan setiap proses tersebut dengan **Data Stores** (tabel database terkait) menggunakan bentuk kotak.  
* Tunjukkan alur data yang valid: Entitas Luar \-\> Proses \-\> Data Store. (Ingat: Entitas tidak boleh berhubungan langsung dengan Data Store tanpa melalui Proses).

### **3\. DFD Level 2 (Rincian Proses Kompleks)**

**Fokus:** Menggambarkan logika spesifik dari fitur yang paling kompleks.

**Instruksi Pembuatan:**

* Pilih 1 atau 2 proses dari Level 1 yang memiliki logika bercabang (misal: Proses Sirkulasi Pinjam yang memiliki validasi stok atau validasi status akun).  
* Dekomposisi proses tersebut. Contoh membedah 3.0 Sirkulasi Pinjam:  
  * 3.1 Validasi Stok  
  * 3.2 Cek Status Akun  
  * 3.3 Simpan Transaksi Pinjam  
* Petakan aliran data antar sub-proses ini secara linier dan jelas, beserta interaksinya dengan Data Store yang kotak.

## **📝 Format Output Agent**

Saat menjawab, Agent harus menggunakan format **file terpisah** untuk masing-masing level DFD. Hasilkan 3 file berbeda dengan format penamaan sebagai berikut:

**1\. File DFD Level 0 (dfd\_level\_0.md)**

\[Blok kode Mermaid Level 0\]

**2\. File DFD Level 1 (dfd\_level\_1.md)**

\[Blok kode Mermaid Level 1\]

**3\. File DFD Level 2 (dfd\_level\_2.md)**

\[Blok kode Mermaid Level 2\]

**Batasan (Constraints):**

1. JANGAN membuat diagram yang terlalu ruwet (*spaghetti diagram*) agar parser Mermaid tidak gagal memuat.  
2. Gunakan bahasa Indonesia untuk nama proses dan label aliran data.  
3. Pastikan penomoran proses logis berjenjang (0.0 \-\> 1.0 \-\> 1.1, dst).  
4. Pastikan semua entitas saling terhubung secara logis; Entitas Luar tidak boleh langsung menunjuk ke Data Store.