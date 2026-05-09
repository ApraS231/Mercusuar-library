<?php

namespace App\Livewire\Admin\Books;

use App\Models\Book;
use App\Models\Category; // TAMBAHAN: Import Model Category
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Illuminate\Database\QueryException; // TAMBAHAN: Untuk menangkap error database

#[Layout('components.layouts.admin')]
class ListBooks extends Component
{
    use WithPagination;
    use WithFileUploads;

    // --- PROPERTI ---
    public $showModal = false;
    public $bookId; // Null = Create, Ada Isi = Edit

    // --- ATURAN VALIDASI (RULES) ---
    
    #[Rule('required|string|max:255')]
    public $judul = '';

    // TAMBAHAN: Input untuk Kategori
    #[Rule('required|exists:categories,id', as: 'kategori')] 
    public $category_id = ''; 

    #[Rule('nullable|string|max:255')]
    public $penulis = '';

    #[Rule('nullable|string|max:255')]
    public $penerbit = '';
    
    #[Rule('nullable|string')]
    public $deskripsi = '';

    #[Rule('nullable|string|max:25|unique:books,isbn')]
    public $isbn = '';

    #[Rule('required|integer|min:0')]
    public $stok_total = 1;

    #[Rule('nullable|image|max:2048')] 
    public $gambar_cover_baru;

    public $gambar_cover_lama;

    /**
     * Reset semua field form menjadi kosong
     */
    public function resetFields()
    {
        $this->reset([
            'bookId', 
            'judul', 
            'category_id', // Reset kategori juga
            'penulis', 
            'penerbit', 
            'deskripsi', 
            'isbn', 
            'stok_total', 
            'gambar_cover_baru', 
            'gambar_cover_lama'
        ]);
        $this->resetErrorBag();
    }

    /**
     * Buka modal tambah buku
     */
    public function create()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    /**
     * Buka modal edit buku
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        
        $this->bookId = $id;
        $this->judul = $book->judul;
        $this->category_id = $book->category_id; // Load kategori dari DB
        $this->penulis = $book->penulis;
        $this->penerbit = $book->penerbit;
        $this->deskripsi = $book->deskripsi;
        $this->isbn = $book->isbn;
        $this->stok_total = $book->stok_total;
        $this->gambar_cover_lama = $book->gambar_cover;

        $this->showModal = true;
    }

    /**
     * Tutup modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    /**
     * Simpan data (Create / Update)
     */
    public function save()
    {
        $this->validate();

        $data = [
            'judul' => $this->judul,
            'category_id' => $this->category_id, // Simpan kategori
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'deskripsi' => $this->deskripsi,
            'isbn' => $this->isbn,
            'stok_total' => $this->stok_total,
        ];

        $isNewBook = !$this->bookId;

        // Handle Upload Gambar
        if ($this->gambar_cover_baru) {
            // Hapus gambar lama jika sedang edit dan gambar lama ada
            if ($this->gambar_cover_lama) {
                Storage::disk('public')->delete($this->gambar_cover_lama);
            }
            // Simpan gambar baru
            $data['gambar_cover'] = $this->gambar_cover_baru->store('covers', 'public');
        }

        // Simpan ke Database
        $book = Book::updateOrCreate(['id' => $this->bookId], $data);

        // Logika Stok Awal: Jika buku baru, samakan stok tersedia dengan stok total
        if ($isNewBook) {
            $book->stok_tersedia = $this->stok_total;
            $book->save();
        }

        $this->closeModal();
        session()->flash('success', $isNewBook ? 'Buku berhasil ditambahkan.' : 'Buku berhasil diperbarui.');
    }

    /**
     * Hapus buku dengan penanganan error relasi
     */
    public function delete($id)
    {
        try {
            $book = Book::findOrFail($id);

            // Cek dan hapus gambar fisik
            if ($book->gambar_cover) {
                Storage::disk('public')->delete($book->gambar_cover);
            }

            $book->delete();
            session()->flash('success', 'Buku berhasil dihapus.');

        } catch (QueryException $e) {
            // Menangkap error jika buku masih dipinjam (Constraint Violation)
            // Kode 23000 adalah kode standar SQL untuk Integrity Constraint Violation
            if ($e->getCode() == "23000") {
                session()->flash('error', 'GAGAL: Buku tidak dapat dihapus karena masih ada riwayat peminjaman/transaksi.');
            } else {
                session()->flash('error', 'Terjadi kesalahan sistem saat menghapus buku.');
            }
        }
    }

    /**
     * Render View
     */
    public function render()
    {
        // Ambil data buku + kategorinya (Eager Loading)
        $books = Book::with('category')
                    ->latest()
                    ->paginate(10);
        
        // Ambil semua kategori untuk Dropdown di Modal
        $categories = Category::all();

        return view('livewire.admin.books.list-books', [
            'books' => $books,
            'categories' => $categories // Kirim variabel ini ke view
        ]);
    }
}