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
    /**
     * Aksi untuk Konfirmasi Penerimaan Buku
     * Ini adalah logika inti dari Langkah 7.
     */
    public function confirmReceipt($id)
    {
        // 1. Cari peminjaman milik user yang sedang login
        $peminjaman = Peminjaman::where('id', $id)
                                ->where('user_id', auth()->id())
                                ->firstOrFail();

        // 2. Hanya proses jika statusnya 'Diantar'
        if ($peminjaman->status === StatusPeminjaman::Diantar) {
            
            // 3. Update data sesuai perencanaan
            $peminjaman->status = StatusPeminjaman::Diterima;
            $peminjaman->tgl_diterima = now();
            $peminjaman->tgl_jatuh_tempo = Carbon::now()->addDays(7); // Masa peminjaman 7 hari
            
            $peminjaman->save();

            session()->flash('success', 'Buku telah dikonfirmasi diterima. Selamat membaca! Jangan lupa kembalikan sebelum ' . $peminjaman->tgl_jatuh_tempo->format('d M Y') . '.');
        } else {
            session()->flash('error', 'Aksi tidak valid.');
        }
    }

    public function render()
    {
        $peminjamans = Peminjaman::where('user_id', auth()->id())
            ->with('book') // Load relasi buku
            ->latest('tgl_booking') // Urutkan berdasarkan yang terbaru
            ->get();

        return view('livewire.user.my-loans', [
            'peminjamans' => $peminjamans
        ]);
    }
}
