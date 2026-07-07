<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use App\Models\User;
use App\Enums\StatusPeminjaman;
use App\Enums\StatusAkun;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CheckOverdueLoans extends Command
{
    /**
     * Nama dan signature dari command.
     * Kita akan memanggilnya dengan `php artisan app:check-overdue-loans`
     */
    protected $signature = 'app:check-overdue-loans';

    /**
     * Deskripsi command.
     */
    protected $description = 'Cari peminjaman yang sudah disetujui dan melewati tanggal jatuh tempo, lalu tandai sebagai Overdue dan batasi akun user.';

    /**
     * Logika utama command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan peminjaman overdue...');

        // 1. Ambil semua peminjaman yang:
        //    - Statusnya "Disetujui"
        //    - Tanggal jatuh temponya SUDAH LEWAT (kurang dari hari ini)
        $overdueLoans = Peminjaman::where('Status_Peminjaman', StatusPeminjaman::Disetujui)
                                ->where('Tanggal_Jatuh_Tempo', '<', Carbon::now()->toDateString())
                                ->get();

        if ($overdueLoans->isEmpty()) {
            $this->info('Tidak ada peminjaman overdue yang ditemukan.');
            return 0;
        }

        $this->info("Ditemukan " . $overdueLoans->count() . " peminjaman overdue.");

        $userIdsToRestrict = [];

        foreach ($overdueLoans as $peminjaman) {
            
            // 2. Ubah status peminjaman menjadi Overdue
            $peminjaman->Status_Peminjaman = StatusPeminjaman::Overdue;
            $peminjaman->save();

            // 3. Catat ID user yang perlu dibatasi
            if (!in_array($peminjaman->Id_Pengguna, $userIdsToRestrict)) {
                $userIdsToRestrict[] = $peminjaman->Id_Pengguna;
            }

            Log::info("Peminjaman ID: $peminjaman->Id_Peminjaman (User ID: $peminjaman->Id_Pengguna) telah ditandai Overdue.");
        }

        // 4. Ubah status akun user menjadi Dibatasi
        if (!empty($userIdsToRestrict)) {
            User::whereIn('Id_pengguna', $userIdsToRestrict)
                ->update(['Status_Akun_Pengguna' => StatusAkun::Dibatasi]);
            
            $this->info("Akun untuk " . count($userIdsToRestrict) . " user telah ditandai 'Dibatasi'.");
        }

        $this->info('Pengecekan selesai.');
        return 0;
    }
}
