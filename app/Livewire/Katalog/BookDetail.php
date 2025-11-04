<?php

namespace App\Livewire\User;

use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\Review;
use App\Enums\StatusAkun;
use App\Enums\StatusPeminjaman;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class BookDetail extends Component
{
    public Book $book;
    
    // Properti untuk Form Booking
    public string $alamat_pengantaran = '';
    public ?string $usulan_jadwal = ''; // Dibuat nullable

    // Properti untuk Form Review
    public int $newReviewRating = 5;
    public string $newReviewComment = '';

    public bool $sudahPernahPinjam = false;
    public bool $sudahReview = false;

    public function mount(Book $book)
    {
        $this->book = $book->load('reviews.user');
        
        // Ambil alamat default user
        if (auth()->user()->alamat) {
            $this->alamat_pengantaran = auth()->user()->alamat;
        }

        // Cek riwayat peminjaman untuk mengizinkan review
        $this->cekRiwayatReview();
    }

    /**
     * Logika Inti: Booking Buku
     */
    public function bookNow()
    {
        $user = auth()->user();

        // ---- VALIDASI ATURAN (Sesuai Perencanaan 4.1) ----

        // Validasi Form
        $this->validate([
            'alamat_pengantaran' => 'required|string|min:10|max:1000',
            'usulan_jadwal' => 'nullable|string|max:100',
        ]);

        // Cek 1: Stok Tersedia (meskipun di UI sudah, double check di backend)
        if ($this->book->stok_tersedia <= 0) {
            session()->flash('error', 'Stok buku sudah habis. Silakan kembali lagi nanti.');
            return;
        }

        // Cek 2: Status Akun Aktif
        if ($user->status_akun !== StatusAkun::Aktif) {
            session()->flash('error', 'Akun Anda sedang dibatasi (overdue). Anda tidak dapat meminjam buku baru.');
            return;
        }

        // Cek 3: Batas Peminjaman Aktif (Status 'Diterima') < 3
        $pinjamanAktif = Peminjaman::where('user_id', $user->id)
                            ->where('status', StatusPeminjaman::Diterima)
                            ->count();
        if ($pinjamanAktif >= 3) {
            session()->flash('error', 'Anda telah mencapai batas maksimum 3 buku yang sedang dipinjam (status "Diterima").');
            return;
        }

        // Cek 4 (Tambahan): User sudah me-request buku ini (status Pending/Disetujui, dll)
        $sudahRequest = Peminjaman::where('user_id', $user->id)
                            ->where('book_id', $this->book->id)
                            ->whereIn('status', [
                                StatusPeminjaman::Pending, 
                                StatusPeminjaman::Disetujui, 
                                StatusPeminjaman::Diproses, 
                                StatusPeminjaman::Diantar, 
                                StatusPeminjaman::Diterima
                            ])->exists();
        if ($sudahRequest) {
            session()->flash('error', 'Anda sudah meminjam atau sedang dalam proses peminjaman buku ini.');
            return;
        }

        // ---- PROSES BOOKING (Jika Lolos Validasi) ----
        try {
            DB::transaction(function () use ($user) {
                // 1. Kurangi stok buku
                $this->book->stok_tersedia -= 1;
                $this->book->save();

                // 2. Buat data peminjaman baru
                Peminjaman::create([
                    'user_id' => $user->id,
                    'book_id' => $this->book->id,
                    'status' => StatusPeminjaman::Pending,
                    'alamat_pengantaran' => $this->alamat_pengantaran,
                    'jadwal_pengantaran_usulan' => $this->usulan_jadwal, // Simpan usulan jadwal
                    'tgl_booking' => now(),
                ]);
            });

            // 3. Berhasil
            session()->flash('success', 'Buku berhasil di-booking! Admin akan segera memproses permintaan Anda.');
            return $this->redirect(route('user.peminjaman'));

        } catch (\Exception $e) {
            // 4. Gagal
            session()->flash('error', 'Terjadi kesalahan pada sistem. Silakan coba lagi.' . $e->getMessage());
        }
    }

    /**
     * Logika: Tambah Review
     */
    public function addReview()
    {
        $this->validate([
            'newReviewRating' => 'required|integer|min:1|max:5',
            'newReviewComment' => 'nullable|string|min:5|max:1000',
        ]);

        // Pastikan user pernah pinjam dan belum review
        $this->cekRiwayatReview();
        if (!$this->sudahPernahPinjam || $this->sudahReview) {
            session()->flash('error-review', 'Anda hanya bisa memberi review satu kali setelah peminjaman dikembalikan.');
            return;
        }

        Review::create([
            'user_id' => auth()->id(),
            'book_id' => $this->book->id,
            'rating' => $this->newReviewRating,
            'komentar' => $this->newReviewComment,
        ]);
        
        // Refresh komponen untuk menampilkan review baru
        $this->book->load('reviews.user');
        $this->cekRiwayatReview(); // Update status $sudahReview
        $this->newReviewComment = ''; // Kosongkan form
        session()->flash('success-review', 'Terima kasih atas review Anda!');
    }

    /**
     * Helper: Cek riwayat untuk form review
     */
    private function cekRiwayatReview()
    {
        $userId = auth()->id();
        
        // Cek apakah user pernah mengembalikan buku ini
        $this->sudahPernahPinjam = Peminjaman::where('user_id', $userId)
                                    ->where('book_id', $this->book->id)
                                    ->where('status', StatusPeminjaman::Dikembalikan)
                                    ->exists();
        
        // Cek apakah user sudah pernah memberi review untuk buku ini
        $this->sudahReview = Review::where('user_id', $userId)
                                ->where('book_id', $this->book->id)
                                ->exists();
    }

    public function render()
    {
        return view('livewire.katalog.book-detail');
    }
}

