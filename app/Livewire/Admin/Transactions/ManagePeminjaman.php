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
    public $filterStatus = 'Pending'; // Default filter
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
     * Sesuai Roadmap: Ubah status ke 'Disetujui'
     */
    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => StatusPeminjaman::Disetujui]);
        session()->flash('success', 'Booking telah disetujui.');
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
     * Logika: Menandai sebagai 'Diantar'
     * Sesuai Roadmap: Ubah status ke 'Diantar'
     */
    public function markAsDelivered($id)
    {
        Peminjaman::findOrFail($id)->update(['status' => StatusPeminjaman::Diantar]);
        session()->flash('success', 'Status peminjaman diubah menjadi "Diantar".');
    }

    /**
     * Logika: Konfirmasi pengembalian buku
     * Sesuai Roadmap: 
     * 1. Ubah status ke 'Dikembalikan'
     * 2. Catat tgl_dikembalikan
     * 3. Kembalikan stok
     * 4. Cek & Aktifkan akun user jika tidak ada denda lain
     */
    public function confirmReturn($id)
    {
        // Eager load relasi yang dibutuhkan
        $peminjaman = Peminjaman::with('user', 'book')->findOrFail($id);

        // 1. & 2. Ubah status dan catat tanggal
        $peminjaman->update([
            'status' => StatusPeminjaman::Dikembalikan,
            'tgl_dikembalikan' => now()
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

        session()->flash('success', 'Pengembalian buku telah dikonfirmasi dan stok diperbarui.');
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
