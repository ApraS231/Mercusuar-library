<div>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Buku: {{ $book->judul }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: Cover & Form Booking --}}
            <div class="md:col-span-1 space-y-6">
                
                {{-- Cover --}}
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <img src="{{ $book->gambar_cover ? asset('storage/' . $book->gambar_cover) : 'https://placehold.co/400x600/e2e8f0/64748b?text=Perpus+Mercusuar' }}" 
                         alt="Cover {{ $book->judul }}" 
                         class="w-full h-auto object-cover rounded-md shadow-md">
                    <div class="mt-4 text-center">
                        <span class="text-2xl font-bold text-green-600">Stok Tersedia: {{ $book->stok_tersedia }}</span>
                    </div>
                </div>

                {{-- FORM BOOKING (LOGIKA INTI) --}}
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Booking Buku Ini</h3>

                    {{-- Notifikasi Error Booking --}}
                    @if (session('error'))
                        <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if ($book->stok_tersedia > 0)
                        <form wire:submit="bookNow" class="space-y-4">
                            {{-- Alamat Pengantaran --}}
                            <div>
                                <label for="alamat_pengantaran" class="block text-sm font-medium text-gray-700">Alamat Pengantaran</label>
                                <textarea wire:model="alamat_pengantaran" id="alamat_pengantaran" rows="3"
                                          class="border-gray-300 focus:border-purple-primary focus:ring-purple-primary rounded-md shadow-sm mt-1 block w-full"
                                          placeholder="Masukkan alamat lengkap Anda..."></textarea>
                                <x-input-error :messages="$errors->get('alamat_pengantaran')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">Alamat ini diambil dari profil Anda. Anda bisa mengubahnya di sini khusus untuk peminjaman ini.</p>
                            </div>
                            
                            {{-- Usulan Jadwal --}}
                            <div>
                                <label for="usulan_jadwal" class="block text-sm font-medium text-gray-700">Usulan Jadwal Pengantaran (Opsional)</label>
                                <input type="datetime-local" wire:model="usulan_jadwal" id="usulan_jadwal"
                                       class="border-gray-300 focus:border-purple-primary focus:ring-purple-primary rounded-md shadow-sm mt-1 block w-full">
                                <x-input-error :messages="$errors->get('usulan_jadwal')" class="mt-2" />
                            </div>

                            {{-- Tombol Submit --}}
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="w-full bg-purple-primary hover:bg-purple-hover text-white font-bold py-3 px-4 rounded-lg shadow-lg transition-colors duration-300">
                                <span wire:loading.remove wire:target="bookNow">Booking Buku Ini</span>
                                <span wire:loading wire:target="bookNow">Memproses...</span>
                            </button>
                        </form>
                    @else
                        <p class="text-center text-lg font-medium text-red-600 bg-red-50 p-4 rounded-lg">
                            Stok buku saat ini habis.
                        </p>
                    @endif
                </div>

            </div>

            {{-- KOLOM KANAN: Detail & Review --}}
            <div class="md:col-span-2 space-y-6">
                
                {{-- Detail Buku --}}
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-3xl font-bold text-gray-900">{{ $book->judul }}</h2>
                    <p class="text-lg text-gray-700 mt-1">oleh {{ $book->penulis ?? 'N/A' }}</p>
                    <p class="text-md text-gray-500 mt-1">Penerbit: {{ $book->penerbit ?? 'N/A' }} | ISBN: {{ $book->isbn ?? 'N/A' }}</p>

                    <hr class="my-4">

                    <h4 class="text-lg font-semibold mb-2">Deskripsi</h4>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">
                        {{ $book->deskripsi ?? 'Tidak ada deskripsi.' }}
                    </p>
                </div>

                {{-- FORM & DAFTAR REVIEW --}}
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-2xl font-bold mb-4">Ulasan & Review</h3>
                    
                    {{-- Form Tambah Review --}}
                    @if ($sudahPernahPinjam && !$sudahReview)
                        <form wire:submit="addReview" class="mb-6 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-lg font-semibold mb-2">Beri review Anda</h4>
                            @if (session('error-review'))
                                <div class="bg-red-500 text-white p-3 rounded-lg mb-4 text-sm">
                                    {{ session('error-review') }}
                                </div>
                            @endif
                            @if (session('success-review'))
                                <div class="bg-green-500 text-white p-3 rounded-lg mb-4 text-sm">
                                    {{ session('success-review') }}
                                </div>
                            @endif

                            {{-- Rating Bintang --}}
                            <div class="mb-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                <select wire:model="newReviewRating" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="5">5 Bintang</option>
                                    <option value="4">4 Bintang</option>
                                    <option value="3">3 Bintang</option>
                                    <option value="2">2 Bintang</option>
                                    <option value="1">1 Bintang</option>
                                </select>
                            </div>
                            
                            {{-- Komentar --}}
                            <div>
                                <label for="newReviewComment" class="block text-sm font-medium text-gray-700">Komentar (Opsional)</label>
                                <textarea wire:model="newReviewComment" id="newReviewComment" rows="3"
                                          class="border-gray-300 focus:border-purple-primary focus:ring-purple-primary rounded-md shadow-sm mt-1 block w-full"
                                          placeholder="Bagaimana pendapat Anda tentang buku ini?"></textarea>
                                <x-input-error :messages="$errors->get('newReviewComment')" class="mt-2" />
                            </div>
                            <button type="submit" wire:loading.attr="disabled"
                                    class="mt-3 bg-purple-primary hover:bg-purple-hover text-white font-bold py-2 px-4 rounded-lg transition-colors duration-300">
                                Kirim Review
                            </button>
                        </form>
                    @elseif ($sudahReview)
                        <p class="mb-6 text-sm text-green-700 bg-green-50 p-3 rounded-lg">Terima kasih, Anda sudah memberi review untuk buku ini.</p>
                    @else
                        <p class="mb-6 text-sm text-gray-500 bg-gray-50 p-3 rounded-lg">Anda harus meminjam dan mengembalikan buku ini terlebih dahulu untuk memberikan review.</p>
                    @endif

                    <hr class="my-4">

                    {{-- Daftar Review --}}
                    <div class="space-y-4">
                        @forelse ($book->reviews as $review)
                            <div class="border-b border-gray-100 pb-4">
                                <div class="flex items-center mb-1">
                                    <span class="font-bold text-gray-900">{{ $review->user->name ?? 'User Anonim' }}</span>
                                    <span class="text-sm text-gray-500 ml-2">- {{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                {{-- Bintang Rating --}}
                                <div class="flex items-center">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg class="w-5 h-5 {{ $i < $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 7.09l6.572-.955L10 .48l2.939 5.655 6.572.955-4.756 4.455 1.123 6.545z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm font-medium text-gray-600">({{ $review->rating }}/5)</span>
                                </div>
                                <p class="text-gray-700 mt-2">{{ $review->komentar }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">Belum ada review untuk buku ini.</p>
                        @endforelse
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

