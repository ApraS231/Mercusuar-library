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
        $query = Peminjaman::with(['user', 'book'])->latest('tgl_booking');

        // Filter search (Judul Buku atau Nama User)
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('book', function($qb) {
                    $qb->where('judul', 'like', '%' . $this->search . '%');
                })->orWhereHas('user', function($qu) {
                    $qu->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        // Filter tanggal booking
        if ($this->startDate) {
            $query->whereDate('tgl_booking', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('tgl_booking', '<=', $this->endDate);
        }

        $stats = [
            'total' => Peminjaman::count(),
            'selesai' => Peminjaman::where('status', \App\Enums\StatusPeminjaman::Selesai)->count(),
            'overdue' => Peminjaman::where('status', \App\Enums\StatusPeminjaman::Overdue)->count(),
            'aktif' => Peminjaman::whereIn('status', [\App\Enums\StatusPeminjaman::Pinjam, \App\Enums\StatusPeminjaman::Disetujui])->count(),
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
        $query = Peminjaman::with(['user', 'book'])->latest('tgl_booking');

        // Apply filters
        if ($this->search) {
            $query->where(function($q) {
                $q->whereHas('book', function($qb) {
                    $qb->where('judul', 'like', '%' . $this->search . '%');
                })->orWhereHas('user', function($qu) {
                    $qu->where('name', 'like', '%' . $this->search . '%');
                });
            });
        }

        if ($this->startDate) {
            $query->whereDate('tgl_booking', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('tgl_booking', '<=', $this->endDate);
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
                    $row->id,
                    $row->book->judul ?? 'Buku Dihapus',
                    $row->book->penulis ?? '-',
                    $row->user->name ?? 'User Dihapus',
                    $row->user->email ?? '-',
                    $row->tgl_booking ? $row->tgl_booking->format('Y-m-d H:i:s') : '-',
                    $row->tgl_disetujui ? $row->tgl_disetujui->format('Y-m-d H:i:s') : '-',
                    $row->tgl_selesai ? $row->tgl_selesai->format('Y-m-d H:i:s') : '-',
                    $row->status->value
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
