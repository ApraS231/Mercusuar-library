<div>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Katalog Buku
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Search Bar --}}
            <div class="mb-6">
                <input 
                    wire:model.live.debounce.300ms="search"
                    type="text" 
                    placeholder="Cari berdasarkan judul atau penulis..." 
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            {{-- Grid Daftar Buku --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse ($books as $book)
                    <a href="{{ route('book.detail', $book->id) }}" class="block bg-white shadow-lg rounded-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                        {{-- Gambar Cover --}}
                        <img 
                            src="{{ $book->gambar_cover ? asset('storage/' . $book->gambar_cover) : 'https://placehold.co/400x600/e2e8f0/64748b?text=Perpus+Mercusuar' }}" 
                            alt="Cover {{ $book->judul }}" 
                            class="w-full h-64 object-cover">
                        
                        <div class="p-4">
                            {{-- Judul --}}
                            <h3 class="text-lg font-bold text-gray-900 truncate" title="{{ $book->judul }}">
                                {{ $book->judul }}
                            </h3>
                            
                            {{-- Penulis --}}
                            <p class="text-sm text-gray-600 mb-2">{{ $book->penulis ?? 'Tanpa Penulis' }}</p>
                            
                            {{-- Info Stok --}}
                            <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                Stok Tersedia: {{ $book->stok_tersedia }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">Tidak ada buku yang ditemukan untuk "{{ $this->search }}".</p>
                    </div>
                @endforelse
            </div>

            {{-- Paginasi --}}
            <div class="mt-8">
                {{ $books->links() }}
            </div>

        </div>
    </div>
</div>
