<?php

namespace App\Livewire\Admin\Books;

use App\Models\Book;
use App\Models\Category; // TAMBAHAN: Import Model Category
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Illuminate\Database\QueryException; // TAMBAHAN: Untuk menangkap error database

#[Layout('components.layouts.admin')]
class ListBooks extends Component
{
    use WithPagination;
    use WithFileUploads;

    // --- PROPERTI ---
    public $showModal = false;
    public $bookId; // Null = Create, Ada Isi = Edit

    public $search = '';
    public $judul = '';
    public $category_id = ''; 
    public $penulis = '';
    public $penerbit = '';
    public $deskripsi = '';
    public $isbn = '';
    public $tahun_pengadaan = '';
    public $stok_total = 1;
    public $gambar_cover_baru;
    public $gambar_cover_lama;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Aturan validasi dinamis
     */
    public function rules()
    {
        return [
            'judul' => 'required|string|max:50',
            'category_id' => 'required|exists:categories,Id_kategori',
            'penulis' => 'nullable|string|max:30',
            'penerbit' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string',
            'isbn' => 'nullable|string|max:28|unique:books,ISBN,' . $this->bookId . ',Id_Buku',
            'tahun_pengadaan' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'stok_total' => 'required|integer|min:0',
            'gambar_cover_baru' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Nama atribut untuk pesan error
     */
    protected $validationAttributes = [
        'category_id' => 'kategori',
        'gambar_cover_baru' => 'gambar cover baru',
        'tahun_pengadaan' => 'tahun pengadaan',
    ];

    /**
     * Reset semua field form menjadi kosong
     */
    public function resetFields()
    {
        $this->reset([
            'bookId', 
            'judul', 
            'category_id', 
            'penulis', 
            'penerbit', 
            'deskripsi', 
            'isbn', 
            'tahun_pengadaan',
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
        $this->category_id = $book->Id_kategori; 
        $this->penulis = $book->penulis;
        $this->penerbit = $book->penerbit;
        $this->deskripsi = $book->deskripsi;
        $this->isbn = $book->ISBN;
        $this->tahun_pengadaan = $book->tahun_pengadaan;
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
            'Id_kategori' => $this->category_id, 
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'deskripsi' => $this->deskripsi,
            'ISBN' => $this->isbn,
            'tahun_pengadaan' => $this->tahun_pengadaan ?: null,
            'stok_total' => $this->stok_total,
        ];

        $isNewBook = !$this->bookId;

        // Handle Upload Gambar
        if ($this->gambar_cover_baru) {
            // Hapus gambar lama jika sedang edit, gambar lama ada, dan bukan URL eksternal
            if ($this->gambar_cover_lama && !str_starts_with($this->gambar_cover_lama, 'http://') && !str_starts_with($this->gambar_cover_lama, 'https://')) {
                Storage::disk('public')->delete($this->gambar_cover_lama);
            }
            // Simpan gambar baru dengan nama acak pendek agar muat di varchar(20) "covers/xxxxx.ext"
            $ext = $this->gambar_cover_baru->getClientOriginalExtension();
            $fileName = \Illuminate\Support\Str::random(5) . '.' . $ext;
            $data['gambar_cover'] = $this->gambar_cover_baru->storeAs('covers', $fileName, 'public');
        }

        // Simpan ke Database
        $book = Book::updateOrCreate(['Id_Buku' => $this->bookId], $data);

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

            // Cek dan hapus gambar fisik (bukan URL eksternal)
            if ($book->gambar_cover && !str_starts_with($book->gambar_cover, 'http://') && !str_starts_with($book->gambar_cover, 'https://')) {
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
                    ->when($this->search, function($q) {
                        $q->where('Id_Buku', 'like', '%'.$this->search.'%')
                          ->orWhere('judul', 'like', '%'.$this->search.'%')
                          ->orWhere('penulis', 'like', '%'.$this->search.'%')
                          ->orWhere('ISBN', 'like', '%'.$this->search.'%');
                    })
                    ->latest('Id_Buku')
                    ->paginate(10);
        
        // Ambil semua kategori untuk Dropdown di Modal
        $categories = Category::all();

        return view('livewire.admin.books.list-books', [
            'books' => $books,
            'categories' => $categories // Kirim variabel ini ke view
        ]);
    }
}