<?php

namespace App\Livewire\Admin;

use App\Models\Book;
use App\Models\User;
use App\Models\Peminjaman;
use App\Enums\StatusAkun;
use App\Enums\StatusPeminjaman;
use Livewire\Component;
use Livewire\Attributes\Layout;

// Menggunakan layout admin yang baru kita buat
#[Layout('components.layouts.admin')] 
class Dashboard extends Component
{
    // Properti untuk menyimpan data statistik
    public $pendingLoans;
    public $jumlahBuku;
    public $jumlahUserAktif;
    public $jumlahOverdue;

    /**
     * Method mount() dijalankan saat komponen di-load
     * Kita isi propertinya di sini
     */
    public function mount()
    {
        // Ambil data sesuai logika di ROADMAP
        $this->pendingLoans = Peminjaman::where('Status_Peminjaman', StatusPeminjaman::Pinjam)->count();
        $this->jumlahBuku = Book::count();
        $this->jumlahUserAktif = User::where('Status_Akun_Pengguna', StatusAkun::Aktif)->count();
        $this->jumlahOverdue = Peminjaman::where('Status_Peminjaman', StatusPeminjaman::Overdue)->count();
    }
    
    /**
     * Method render() akan me-render file view
     */
    public function render()
    {
        // View ini akan diisi dengan data dari properti di atas
        return view('livewire.admin.dashboard');
    }
}