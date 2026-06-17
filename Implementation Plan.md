# **Implementation Plan: Pembaruan Alur Peminjaman & Modul Kepala Perpus**

**Sistem:** Mercusuar Library (Laravel 11 \+ Livewire v3)

**Tujuan:** Menyederhanakan status peminjaman (menghapus alur pengantaran fisik yang rumit), mengintegrasikan entitas "Review" pada akhir siklus, dan menambahkan \\textit{Role} kepala\_perpus dengan fitur pelaporan interaktif.

## **Tahap 1: Pembaruan Enumerator (Enums)**

### **1.1 Update app/Enums/Role.php**

Tambahkan role baru untuk Kepala Perpustakaan.

* **Tindakan:** Tambahkan \\textit{case} KepalaPerpus \= 'kepala\_perpus'.  
* **Kode Target:**  
  enum Role: string {  
      case Admin \= 'admin';  
      case User \= 'user';  
      case KepalaPerpus \= 'kepala\_perpus'; // Tambahan Baru  
  }

### **1.2 Update app/Enums/StatusPeminjaman.php**

Sederhanakan siklus peminjaman. Hapus status Diproses, Diantar, dan Diterima.

* **Tindakan:** Ubah struktur \\textit{case} menjadi berikut:  
  enum StatusPeminjaman: string {  
      case Pinjam \= 'Pinjam';       // Pengajuan awal (sebelumnya Pending)  
      case Disetujui \= 'Disetujui'; // Buku bisa diambil/dibaca  
      case Ditolak \= 'Ditolak';     // Pengajuan ditolak  
      case Selesai \= 'Selesai';     // Buku dikembalikan (Masuk ke Riwayat Pinjaman)  
      case Overdue \= 'Overdue';     // Terlambat dikembalikan  
  }

## **Tahap 2: Pembaruan Database & Model**

### **2.1 Buat Migration untuk Update Kolom Tabel peminjamans**

* **Tindakan:** Buat \\textit{migration} baru untuk mengubah default status dan menghapus kolom pengantaran yang tidak lagi relevan (opsional tapi disarankan untuk kebersihan DB).  
* **Perintah:** php artisan make:migration simplify\_peminjamans\_table  
* **Logika Migration:**  
  * Ubah enum status: $table-\>enum('status', \['Pinjam', 'Disetujui', 'Ditolak', 'Selesai', 'Overdue'\])-\>default('Pinjam')-\>change();  
  * (Opsional) Hapus kolom: jadwal\_pengantaran\_usulan, jadwal\_pengantaran\_disetujui, alamat\_pengantaran.

### **2.2 Update app/Models/Peminjaman.php**

* **Tindakan:** Hapus \\textit{casting} tanggal yang terkait dengan pengantaran dan ubah patokan tanggal jatuh tempo.  
* **Perubahan Casting:**  
  * Tetap pertahankan tgl\_booking dan tgl\_jatuh\_tempo.  
  * Ganti tgl\_diterima dan tgl\_dikembalikan menjadi sekadar tgl\_disetujui dan tgl\_selesai.

## **Tahap 3: Refactoring Logika Livewire (Sirkulasi Utama)**

### **3.1 Update App\\Livewire\\Katalog\\BookDetail (Aksi Pinjam)**

* **Tindakan:** Modifikasi metode bookNow().  
* **Perubahan:** \* Hapus input validasi untuk alamat\_pengantaran dan jadwal\_usulan.  
  * Saat \\textit{insert} data peminjaman, set status ke StatusPeminjaman::Pinjam.

### **3.2 Update App\\Livewire\\Admin\\Transactions\\ManagePeminjaman**

* **Tindakan:** Sederhanakan tombol aksi (\\textit{Actions}) pada antarmuka tabel admin.  
* **Perubahan Metode:**  
  * approve($id): Ubah status menjadi Disetujui. Set tgl\_disetujui \= now(), hitung tgl\_jatuh\_tempo \= now()-\>addDays(7).  
  * reject($id): Ubah status menjadi Ditolak. Kembalikan stok\_tersedia (+1).  
  * Hapus metode markAsDelivered().  
  * markAsDone($id) (Menggantikan confirmReturn): Ubah status menjadi Selesai, set tgl\_selesai \= now(), kembalikan stok (+1), dan cek pemulihan akun dari \\textit{Overdue}.

### **3.3 Update App\\Livewire\\User\\MyLoans (Aksi Riwayat & Review)**

* **Tindakan:**  
  * Hapus metode confirmReceipt() karena anggota tidak lagi mengonfirmasi penerimaan fisik via aplikasi.  
  * Buat *Tab* atau *Filter* UI: "Pinjaman Aktif" (Pinjam, Disetujui, Overdue) dan "Riwayat Pinjaman" (Selesai, Ditolak).  
  * Tampilkan tombol **"Tulis Review"** HANYA jika status peminjaman adalah Selesai dan user belum melakukan *review* pada buku tersebut.

### **3.4 Update Job App\\Console\\Commands\\CheckOverdueLoans**

* **Tindakan:** Ubah parameter query pencarian keterlambatan.  
* **Logika Baru:** Cari peminjaman dengan status Disetujui (bukan lagi Diterima) yang tgl\_jatuh\_tempo \< now(). Ubah statusnya menjadi Overdue dan set status akun user menjadi dibatasi.

## **Tahap 4: Implementasi Fitur "Kepala Perpus"**

### **4.1 Setup Route & Middleware**

* **Tindakan:** Tambahkan Middleware baru (atau gunakan *Gate*/*Policy*) untuk melindungi rute /kepala-perpus/\*.  
* **Route:** Daftarkan \\textit{endpoint} baru di routes/web.php.  
  Route::middleware(\['auth', 'role:kepala\_perpus'\])-\>group(function () {  
      Route::get('/laporan-peminjaman', App\\Livewire\\KepalaPerpus\\LaporanPeminjaman::class)-\>name('kepala-perpus.laporan');  
  });

### **4.2 Buat Livewire Component LaporanPeminjaman**

* **Perintah:** php artisan make:livewire KepalaPerpus\\LaporanPeminjaman  
* **Logika Komponen (LaporanPeminjaman.php):**  
  namespace App\\Livewire\\KepalaPerpus;

  use Livewire\\Component;  
  use Livewire\\WithPagination;  
  use App\\Models\\Peminjaman;

  class LaporanPeminjaman extends Component  
  {  
      use WithPagination;

      public $search \= '';  
      public $startDate;  
      public $endDate;

      // Reset pagination saat pencarian atau filter tanggal berubah  
      public function updatingSearch() { $this-\>resetPage(); }  
      public function updatingStartDate() { $this-\>resetPage(); }  
      public function updatingEndDate() { $this-\>resetPage(); }

      public function render()  
      {  
          $query \= Peminjaman::with(\['user', 'book'\]);

          // 1\. Search Logic (Relational)  
          if (\!empty($this-\>search)) {  
              $query-\>whereHas('user', function($q) {  
                  $q-\>where('name', 'like', '%' . $this-\>search . '%');  
              })-\>orWhereHas('book', function($q) {  
                  $q-\>where('judul', 'like', '%' . $this-\>search . '%');  
              });  
          }

          // 2\. Time Filtering Logic (Berdasarkan tgl\_booking / update terbaru)  
          if ($this-\>startDate && $this-\>endDate) {  
              $query-\>whereBetween('created\_at', \[$this-\>startDate . ' 00:00:00', $this-\>endDate . ' 23:59:59'\]);  
          }

          // 3\. Pagination Configuration  
          $laporan \= $query-\>orderBy('created\_at', 'desc')-\>paginate(15);

          return view('livewire.kepala-perpus.laporan-peminjaman', \[  
              'laporan' \=\> $laporan  
          \])-\>layout('components.layouts.admin'); // Gunakan layout admin yang sama  
      }  
  }

### **4.3 Buat View Blade laporan-peminjaman.blade.php**

* **Tindakan:** Implementasikan UI *TailwindCSS* dengan *input debounce* dan *date picker*.  
* **Potongan Kode Utama (UI Controls):**  
  \<\!-- Search dengan Debounce \--\>  
  \<input type="text"   
         wire:model.live.debounce.500ms="search"   
         placeholder="Cari nama peminjam atau judul buku..."   
         class="form-input rounded-md shadow-sm"\>

  \<\!-- Filter Waktu \--\>  
  \<div class="flex gap-2"\>  
      \<input type="date" wire:model.live="startDate" class="form-input rounded-md"\>  
      \<span class="self-center"\>s/d\</span\>  
      \<input type="date" wire:model.live="endDate" class="form-input rounded-md"\>  
  \</div\>

  \<\!-- Tampilkan Tabel Data Pagination \--\>  
  \<\!-- (Looping data $laporan menggunakan foreach, tampilkan kolom Nama, Judul Buku, Tanggal, Status) \--\>

  \<\!-- Render Pagination Links \--\>  
  \<div class="mt-4"\>  
      {{ $laporan-\>links() }}  
  \</div\>

## **Ringkasan Eksekusi untuk AI Agent:**

1. Jalankan modifikasi pada Role.php dan StatusPeminjaman.php.  
2. Buat migration untuk merapikan tabel peminjamans.  
3. \\textit{Refactor} ManagePeminjaman.php, BookDetail.php, dan MyLoans.php dengan menghapus logika "Pengantaran" dan menyisipkan logika "Review" pada status Selesai.  
4. Update command CheckOverdueLoans.php.  
5. Buat dan \\textit{route} komponen Livewire baru untuk fitur laporan kepala\_perpus dengan mengimplementasikan \\textit{trait} WithPagination dan \\textit{modifier} wire:model.live.debounce.500ms.