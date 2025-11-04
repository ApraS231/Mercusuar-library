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

            {{-- Grid Daftar Buku & Loading State --}}
            <div
                wire:loading.class.delay="opacity-50"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 transition-opacity">
                @forelse ($books as $book)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden transform hover:-translate-y-1 transition-transform duration-300">
                        <a href="{{ route('book.detail', $book->id) }}" class="block">
                            {{-- Gambar Cover --}}
                            <img
                                src="{{ $book->gambar_cover ? asset('storage/' . $book->gambar_cover) : 'https://placehold.co/400x600/e2e8f0/64748b?text=No+Image' }}"
                                alt="Cover {{ $book->judul }}"
                                class="w-full h-64 object-cover">
                        </a>
                        
                        <div class="p-4">
                            {{-- Kategori --}}
                            @if ($book->category)
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold mb-2 px-2.5 py-0.5 rounded-full">
                                    {{ $book->category->nama_kategori }}
                                </span>
                            @endif

                            {{-- Judul --}}
                            <h3 class="text-lg font-bold text-gray-900 truncate" title="{{ $book->judul }}">
                                <a href="{{ route('book.detail', $book->id) }}" class="hover:text-indigo-600 transition-colors">
                                    {{ $book->judul }}
                                </a>
                            </h3>
                            
                            {{-- Penulis --}}
                            <p class="text-sm text-gray-600 mb-2">{{ $book->penulis ?? 'Tanpa Penulis' }}</p>
                            
                            {{-- Info Stok --}}
                            <span class="inline-block {{ $book->stok_tersedia > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                Stok: {{ $book->stok_tersedia }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900">Hasil tidak ditemukan</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if ($this->search)
                                Kami tidak dapat menemukan buku yang cocok dengan pencarian "{{ $this->search }}".
                            @else
                                Belum ada buku di katalog.
                            @endif
                        </p>
                        <div class="mt-6">
                            <button
                                wire:click="$set('search', '')"
                                type="button"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Hapus Pencarian
                            </button>
                        </div>
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
