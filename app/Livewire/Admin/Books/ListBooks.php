<?php

namespace App\Livewire\Admin\Books;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;      // Untuk paginasi
use Livewire\WithFileUploads;    // Untuk upload gambar
use Illuminate\Support\Facades\Storage; // Untuk menghapus file
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;     // Untuk validasi

#[Layout('components.layouts.admin')] // Menggunakan layout admin
class ListBooks extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Properti untuk modal
    public $showModal = false;
    public $bookId; // Untuk membedakan create/update

    // Properti untuk form (rules validasi dari Roadmap)
    #[Rule('required|string|max:255')]
    public $judul = '';
    
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

    #[Rule('nullable|image|max:2048')] // Validasi untuk upload (max 2MB)
    public $gambar_cover_baru;

    public $gambar_cover_lama;

    /**
     * Helper untuk reset form
     */
    public function resetFields()
    {
        $this->reset(['bookId', 'judul', 'penulis', 'penerbit', 'deskripsi', 'isbn', 'stok_total', 'gambar_cover_baru', 'gambar_cover_lama']);
        $this->resetErrorBag(); // Menghapus pesan error
    }

    /**
     * Membuka modal untuk membuat buku baru
     */
    public function create()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    /**
     * Menutup modal
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetFields();
    }

    /**
     * Menyimpan data (baik baru atau update)
     */
    public function save()
    {
        // Validasi data
        $this->validate();

        $data = [
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'deskripsi' => $this->deskripsi,
            'isbn' => $this->isbn,
            'stok_total' => $this->stok_total,
        ];

        // Cek apakah ini buku baru
        $isNewBook = !$this->bookId;

        // Handle File Upload
        if ($this->gambar_cover_baru) {
            // Hapus gambar lama jika ada (saat update)
            if ($this->gambar_cover_lama) {
                Storage::disk('public')->delete($this->gambar_cover_lama);
            }
            // Simpan gambar baru
            $data['gambar_cover'] = $this->gambar_cover_baru->store('covers', 'public');
        }

        // Simpan ke database
        $book = Book::updateOrCreate(['id' => $this->bookId], $data);

        // Sesuai Roadmap: Set stok_tersedia = stok_total saat buku baru
        if ($isNewBook) {
            $book->stok_tersedia = $this->stok_total;
            $book->save();
        }

        $this->closeModal();
        session()->flash('success', $isNewBook ? 'Buku berhasil ditambahkan.' : 'Buku berhasil diperbarui.');
        $this->dispatch('close-modal'); // Emit event jika diperlukan
    }

    /**
     * Membuka modal untuk mengedit buku
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);
        
        $this->bookId = $id;
        $this->judul = $book->judul;
        $this->penulis = $book->penulis;
        $this->penerbit = $book->penerbit;
        $this->deskripsi = $book->deskripsi;
        $this->isbn = $book->isbn;
        $this->stok_total = $book->stok_total;
        $this->gambar_cover_lama = $book->gambar_cover;

        $this->showModal = true;
    }

    /**
     * Menghapus buku
     */
    public function delete($id)
    {
        $book = Book::findOrFail($id);

        // Hapus gambar dari storage jika ada
        if ($book->gambar_cover) {
            Storage::disk('public')->delete($book->gambar_cover);
        }

        $book->delete();
        session()->flash('success', 'Buku berhasil dihapus.');
    }

    /**
     * Render view
     */
    public function render()
    {
        $books = Book::latest()->paginate(10);
        
        return view('livewire.admin.books.list-books', [
            'books' => $books
        ]);
    }
}
