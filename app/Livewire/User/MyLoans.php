<?php

namespace App\Livewire\User;

use App\Models\Peminjaman;
use App\Enums\StatusPeminjaman;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // Menggunakan layout user (bawaan Breeze)
class MyLoans extends Component
{
    public string $activeTab = 'aktif';

    public function mount()
    {
        if (auth()->user()->Peran_Akses_Pengguna !== \App\Enums\Role::User) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }
    }

    /**
     * Set active tab
     */
    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $query = Peminjaman::where('Id_Pengguna', auth()->id())
            ->with('book')
            ->latest('Tanggal_Pinjam');

        if ($this->activeTab === 'aktif') {
            $query->whereIn('Status_Peminjaman', [
                StatusPeminjaman::Pinjam,
                StatusPeminjaman::Disetujui,
                StatusPeminjaman::Overdue
            ]);
        } else {
            $query->whereIn('Status_Peminjaman', [
                StatusPeminjaman::Selesai,
                StatusPeminjaman::Ditolak
            ]);
        }

        $peminjamans = $query->get();

        return view('livewire.user.my-loans', [
            'peminjamans' => $peminjamans
        ]);
    }
}
