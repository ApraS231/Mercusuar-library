<?php

namespace App\Livewire\KepalaPerpus;

use App\Models\Book;
use App\Models\User;
use App\Models\Peminjaman;
use App\Enums\StatusPeminjaman;
use App\Enums\StatusAkun;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public $jumlahBuku;
    public $jumlahAnggota;
    public $totalPeminjaman;
    public $pinjamanAktif;
    public $peminjamanSelesai;
    public $peminjamanOverdue;
    public $rasioKeterlambatan = 0;
    public $recentLoans = [];

    public function mount()
    {
        $this->jumlahBuku = Book::count();
        $this->jumlahAnggota = User::where('Peran_Akses_Pengguna', \App\Enums\Role::User)->count();
        $this->totalPeminjaman = Peminjaman::count();
        $this->pinjamanAktif = Peminjaman::whereIn('Status_Peminjaman', [StatusPeminjaman::Pinjam, StatusPeminjaman::Disetujui])->count();
        $this->peminjamanSelesai = Peminjaman::where('Status_Peminjaman', StatusPeminjaman::Selesai)->count();
        $this->peminjamanOverdue = Peminjaman::where('Status_Peminjaman', StatusPeminjaman::Overdue)->count();
        
        if ($this->totalPeminjaman > 0) {
            $this->rasioKeterlambatan = round(($this->peminjamanOverdue / $this->totalPeminjaman) * 100, 1);
        }

        $this->recentLoans = Peminjaman::with(['user', 'book'])
            ->latest('Tanggal_Pinjam')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.kepala-perpus.dashboard');
    }
}
