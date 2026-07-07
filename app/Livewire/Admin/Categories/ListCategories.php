<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Database\QueryException;

#[Layout('components.layouts.admin')]
class ListCategories extends Component
{
    use WithPagination;

    // --- PROPERTI ---
    public $showModal = false;
    public $categoryId; // Null = Create, Ada Isi = Edit

    public $nama_kategori = '';
    public $search = '';

    /**
     * Aturan validasi dinamis
     */
    public function rules()
     {
         return [
             'nama_kategori' => 'required|string|max:10|unique:categories,Nama_kategori,' . $this->categoryId . ',Id_kategori',
         ];
     }

    /**
     * Nama atribut untuk pesan error
     */
    protected $validationAttributes = [
        'nama_kategori' => 'nama kategori',
    ];

    /**
     * Reset paginasi saat searching
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Reset semua field form menjadi kosong
     */
    public function resetFields()
    {
        $this->reset([
            'categoryId',
            'nama_kategori',
        ]);
        $this->resetErrorBag();
    }

    /**
     * Buka modal tambah kategori
     */
    public function create()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    /**
     * Buka modal edit kategori
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        $this->categoryId = $id;
        $this->nama_kategori = $category->Nama_kategori;

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
            'Nama_kategori' => $this->nama_kategori,
        ];

        $isNewCategory = !$this->categoryId;

        // Simpan ke Database
        Category::updateOrCreate(['Id_kategori' => $this->categoryId], $data);

        $this->closeModal();
        session()->flash('success', $isNewCategory ? 'Kategori berhasil ditambahkan.' : 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori
     */
    public function delete($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            session()->flash('success', 'Kategori berhasil dihapus.');

        } catch (QueryException $e) {
            // Sebagai antisipasi jika ada foreign key constraint lain yang membatasi
            if ($e->getCode() == "23000") {
                session()->flash('error', 'GAGAL: Kategori tidak dapat dihapus karena masih terelasi dengan data lain.');
            } else {
                session()->flash('error', 'Terjadi kesalahan sistem saat menghapus kategori.');
            }
        }
    }

    /**
     * Render View
     */
    public function render()
    {
        // Ambil data kategori dengan count buku dan filter pencarian
        $categories = Category::withCount('books')
            ->when($this->search, function ($query) {
                $query->where('Nama_kategori', 'like', '%' . $this->search . '%');
            })
            ->latest('created_at')
            ->paginate(10);

        return view('livewire.admin.categories.list-categories', [
            'categories' => $categories,
        ]);
    }
}
