<?php

namespace App\Livewire\User;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // Menggunakan layout user (bawaan Breeze)
class BookCatalog extends Component
{
    use WithPagination;

    public $search = '';

    // Reset paginasi saat searching
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $books = Book::query()
            // Hanya tampilkan buku yang stoknya ada
            ->where('stok_tersedia', '>', 0) 
            // Logika pencarian
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('judul', 'like', '%'.$this->search.'%')
                      ->orWhere('penulis', 'like', '%'.$this->search.'%');
                });
            })
            ->latest('created_at')
            ->paginate(12); // Tampilkan 12 buku per halaman

        return view('livewire.book.show-katalog', [
            'books' => $books
        ]);
    }
}
