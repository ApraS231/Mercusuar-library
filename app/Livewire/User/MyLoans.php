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
        if (auth()->user()->role !== \App\Enums\Role::User) {
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
        $query = Peminjaman::where('user_id', auth()->id())
            ->with('book')
            ->latest('tgl_booking');

        if ($this->activeTab === 'aktif') {
            $query->whereIn('status', [
                StatusPeminjaman::Pinjam,
                StatusPeminjaman::Disetujui,
                StatusPeminjaman::Overdue
            ]);
        } else {
            $query->whereIn('status', [
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
