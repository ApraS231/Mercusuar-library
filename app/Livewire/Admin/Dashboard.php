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
    public $jumlahPeminjamanPending;
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
        $this->jumlahPeminjamanPending = Peminjaman::where('status', StatusPeminjaman::Pending)->count();
        $this->jumlahBuku = Book::count();
        $this->jumlahUserAktif = User::where('status_akun', StatusAkun::Aktif)->count();
        $this->jumlahOverdue = Peminjaman::where('status', StatusPeminjaman::Overdue)->count();
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
