<?php

namespace App\Livewire\KepalaPerpus;

use App\Models\Peminjaman;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.admin')]
class LaporanPeminjaman extends Component
{
    use WithPagination;

    public $search = '';
    public $startDate = '';
    public $endDate = '';

    // Reset pagination when filter updates
    public function updatingSearch() { $this->resetPage(); }
    public function updatingStartDate() { $this->resetPage(); }
    public function updatingEndDate() { $this->resetPage(); }

    public function render()
    {
        $query = Peminjaman::with(['user', 'book'])->latest('Tanggal_Pinjam');

        // Filter search (Judul Buku atau Nama User)
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('book', function($qb) {
                    $qb->where('judul', 'like', '%' . $this->search . '%');
                })->orWhereHas('user', function($qu) {
                    $qu->where('Nama_Pengguna', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Filter tanggal booking
        if ($this->startDate) {
            $query->whereDate('Tanggal_Pinjam', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('Tanggal_Pinjam', '<=', $this->endDate);
        }

        $stats = [
            'total' => Peminjaman::count(),
            'selesai' => Peminjaman::where('Status_Peminjaman', \App\Enums\StatusPeminjaman::Selesai)->count(),
            'overdue' => Peminjaman::where('Status_Peminjaman', \App\Enums\StatusPeminjaman::Overdue)->count(),
            'aktif' => Peminjaman::whereIn('Status_Peminjaman', [\App\Enums\StatusPeminjaman::Pinjam, \App\Enums\StatusPeminjaman::Disetujui])->count(),
        ];

        $peminjamans = $query->paginate(15);

        return view('livewire.kepala-perpus.laporan-peminjaman', [
            'peminjamans' => $peminjamans,
            'stats' => $stats
        ]);
    }

    /**
     * Export filtered loan reports to CSV
     */
    public function exportCSV()
    {
        $query = Peminjaman::with(['user', 'book'])->latest('Tanggal_Pinjam');

        // Apply filters
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('book', function($qb) {
                    $qb->where('judul', 'like', '%' . $this->search . '%');
                })->orWhereHas('user', function($qu) {
                    $qu->where('Nama_Pengguna', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->startDate) {
            $query->whereDate('Tanggal_Pinjam', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('Tanggal_Pinjam', '<=', $this->endDate);
        }

        $peminjamans = $query->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=laporan-sirkulasi-' . now()->format('Y-m-d') . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($peminjamans) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($file, [
                'ID Transaksi', 
                'Judul Buku', 
                'Penulis', 
                'Nama Anggota', 
                'Email Anggota', 
                'Tanggal Booking', 
                'Tanggal Disetujui', 
                'Tanggal Kembali', 
                'Status'
            ]);

            // CSV Data
            foreach ($peminjamans as $row) {
                fputcsv($file, [
                    $row->Id_Peminjaman,
                    $row->book->judul ?? 'Buku Dihapus',
                    $row->book->penulis ?? '-',
                    $row->user->Nama_Pengguna ?? 'User Dihapus',
                    $row->user->Email_Pengguna ?? '-',
                    $row->Tanggal_Pinjam ? $row->Tanggal_Pinjam->format('Y-m-d H:i:s') : '-',
                    $row->Tanggal_Disetujui ? $row->Tanggal_Disetujui->format('Y-m-d H:i:s') : '-',
                    $row->Tanggal_Selesai ? $row->Tanggal_Selesai->format('Y-m-d H:i:s') : '-',
                    $row->Status_Peminjaman->value
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
