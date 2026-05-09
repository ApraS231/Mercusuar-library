<?php

namespace App\Livewire\Katalog;

use App\Models\Book;
use App\Models\Category; // Import Category
use App\Models\Peminjaman;
use App\Enums\StatusPeminjaman;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class BookCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = ''; // Fitur Baru: Filter Kategori

    // Reset halaman saat filter berubah
    public function updatingSearch() { $this->resetPage(); }
    public function updatingSelectedCategory() { $this->resetPage(); }

    public function render()
    {
        $books = Book::with('category')
            // PERUBAHAN: Menghapus where('stok_tersedia', '>', 0) agar semua buku muncul
            
            // Filter Kategori
            ->when($this->selectedCategory, function($q) {
                $q->where('category_id', $this->selectedCategory);
            })
            // Filter Pencarian
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhere('penulis', 'like', '%'.$this->search.'%')
                      ->orWhere('isbn', 'like', '%'.$this->search.'%');
                });
            })
            ->latest('created_at')
            ->paginate(12);

        // Ambil Kategori untuk Filter UI
        $categories = Category::has('books')->get();

        return view('livewire.katalog.book-catalog', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }
}