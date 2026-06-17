<?php

namespace App\Livewire\Admin\Transactions;

use App\Enums\StatusAkun;
use App\Enums\StatusPeminjaman;
use App\Models\Peminjaman;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')] // Menggunakan layout admin
class ManagePeminjaman extends Component
{
    use WithPagination;

    // Properti untuk memfilter status
    public $filterStatus = 'Pinjam'; // Default filter
    public $statuses = []; // Untuk tab filter

    public function mount()
    {
        // Mengisi array $statuses dengan semua nilai dari Enum
        $this->statuses = array_map(fn($case) => $case->value, StatusPeminjaman::cases());
    }

    /**
     * Mengubah filter status
     */
    public function setFilter($status)
    {
        $this->filterStatus = $status;
        $this->resetPage(); // Reset paginasi saat filter diubah
    }

    /**
     * Logika: Menyetujui booking
     * Sesuai Roadmap: Ubah status ke 'Disetujui', set tgl_disetujui & tgl_jatuh_tempo
     */
    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => StatusPeminjaman::Disetujui,
            'tgl_disetujui' => now(),
            'tgl_jatuh_tempo' => now()->addDays(7)
        ]);
        session()->flash('success', 'Booking telah disetujui. Silakan serahkan buku kepada peminjam.');
    }

    /**
     * Logika: Menolak booking
     * Sesuai Roadmap: Ubah status ke 'Ditolak' & Kembalikan stok
     */
    public function reject($id)
    {
        $peminjaman = Peminjaman::with('book')->findOrFail($id);
        
        // 1. Ubah status
        $peminjaman->update(['status' => StatusPeminjaman::Ditolak]);
        
        // 2. Kembalikan stok
        $peminjaman->book->increment('stok_tersedia');
        
        session()->flash('success', 'Booking ditolak dan stok telah dikembalikan.');
    }

    /**
     * Logika: Selesaikan peminjaman (Buku dikembalikan)
     * Sesuai Roadmap: 
     * 1. Ubah status ke 'Selesai'
     * 2. Catat tgl_selesai
     * 3. Kembalikan stok
     * 4. Cek & Aktifkan akun user jika tidak ada denda lain
     */
    public function markAsDone($id)
    {
        // Eager load relasi yang dibutuhkan
        $peminjaman = Peminjaman::with('user', 'book')->findOrFail($id);

        // 1. & 2. Ubah status dan catat tanggal selesai
        $peminjaman->update([
            'status' => StatusPeminjaman::Selesai,
            'tgl_selesai' => now()
        ]);

        // 3. Kembalikan stok
        $peminjaman->book->increment('stok_tersedia');

        // 4. Cek & Aktifkan akun user
        $user = $peminjaman->user;
        
        // Cek apakah user ini MASIH punya pinjaman lain yang 'Overdue'
        $hasOtherOverdueLoans = $user->peminjamans()
                                     ->where('status', StatusPeminjaman::Overdue)
                                     ->exists();
        
        // Jika TIDAK ADA lagi pinjaman overdue, aktifkan akunnya
        if (!$hasOtherOverdueLoans) {
            $user->update(['status_akun' => StatusAkun::Aktif]);
        }

        session()->flash('success', 'Peminjaman telah diselesaikan dan stok diperbarui.');
    }

    public function render()
    {
        // Ambil data peminjaman
        $peminjamans = Peminjaman::query()
            ->with('user', 'book') // Eager load data user dan buku
            ->where('status', $this->filterStatus) // Filter berdasarkan status
            ->latest('tgl_booking') // Urutkan
            ->paginate(10); // Paginasi

        return view('livewire.admin.transactions.manage-peminjaman', [
            'peminjamans' => $peminjamans
        ]);
    }
}
