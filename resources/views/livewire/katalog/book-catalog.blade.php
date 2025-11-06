<div>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Katalog Buku
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">

            {{-- My Loans Section --}}
            <section class="bg-purple-card p-6 rounded-2xl shadow-lg">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Buku yang Sedang Dipinjam</h2>
                @if ($myLoans->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach ($myLoans as $loan)
                            <div class="flex items-start space-x-4 p-4 bg-white rounded-xl shadow-md transform hover:scale-105 transition-transform duration-300">
                                <img src="{{ $loan->book->gambar_cover ? asset('storage/' . $loan->book->gambar_cover) : 'https://placehold.co/80x120/e2e8f0/64748b?text=No+Image' }}"
                                     alt="Cover {{ $loan->book->judul }}"
                                     class="w-20 h-28 object-cover rounded-lg shadow-sm flex-shrink-0">
                                <div class="flex-1">
                                    <a href="{{ route('book.detail', $loan->book->id) }}" class="font-bold text-gray-800 hover:text-purple-primary transition-colors line-clamp-2">{{ $loan->book->judul }}</a>
                                    <p class="text-sm text-gray-600 mt-1">Jatuh tempo:</p>
                                    <p class="text-sm font-semibold text-red-600">{{ \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d F Y') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('user.peminjaman') }}" class="inline-block mt-6 text-purple-primary hover:text-purple-hover font-semibold text-sm transition-colors">
                        Lihat Semua Peminjaman &rarr;
                    </a>
                @else
                    <div class="text-center py-8 px-6 bg-white rounded-xl shadow-sm">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="mt-2 text-lg font-semibold text-gray-900">Belum Ada Pinjaman</h3>
                        <p class="mt-1 text-sm text-gray-500">Jelajahi katalog di bawah dan pinjam buku pertama Anda!</p>
                    </div>
                @endif
            </section>

            {{-- Book Catalog Section --}}
            <section>
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Katalog Buku</h2>
                    {{-- Search Bar --}}
                    <div class="relative w-1/3">
                        <input
                            wire:model.live.debounce.300ms="search"
                            type="text"
                            placeholder="Cari buku..."
                            class="w-full rounded-full border-gray-300 shadow-sm focus:border-purple-primary focus:ring focus:ring-purple-primary focus:ring-opacity-50 pl-10 pr-4 py-2 transition-shadow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="animate-spin h-5 w-5 text-purple-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Grid Daftar Buku & Loading State --}}
                <div
                    wire:loading.class.delay="opacity-50"
                    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 transition-opacity">
                @forelse ($books as $book)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden transform hover:-translate-y-2 transition-all duration-300 hover:shadow-2xl">
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
