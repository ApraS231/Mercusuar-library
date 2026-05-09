<div>
    {{-- SEO Meta --}}
    @push('seo')
        <title>Katalog Pustaka | Mercusuar Library</title>
        <meta name="description" content="Jelajahi koleksi buku lengkap kami. Temukan inspirasi baru setiap hari.">
    @endpush

    {{-- ASSETS --}}
    @assets
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/ScrollTrigger.min.js"></script>
    <style>
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-sans-text { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Hide Scrollbar */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Background Pattern */
        .bg-dots {
            background-image: radial-gradient(#E7E0EC 1.5px, transparent 1.5px);
            background-size: 24px 24px;
        }
    </style>
    @endassets

    <div class="min-h-screen bg-[#FDF7FF] font-sans-text text-[#1D1B20] pb-24 relative">
        
        {{-- HERO SECTION --}}
        <div class="relative pt-12 pb-8 px-6 bg-dots">
            <div class="max-w-5xl mx-auto text-center">
                
                {{-- Hero Text --}}
                <div class="gsap-hero mb-8">
                    <span class="inline-block py-1 px-4 rounded-full bg-white border border-[#E7E0EC] text-[10px] font-bold uppercase tracking-widest text-[#6750A4] shadow-sm mb-4">
                        Perpustakaan Digital
                    </span>
                    <h1 class="font-serif-display text-4xl md:text-6xl text-[#1D1B20] leading-tight">
                        Eksplorasi <span class="italic text-[#6750A4]">Tanpa Batas.</span>
                    </h1>
                </div>

                {{-- SEARCH & FILTER WRAPPER --}}
                <div class="gsap-hero relative z-20 max-w-2xl mx-auto space-y-6">
                    
                    {{-- Search Bar --}}
                    <div class="relative group">
                        <div class="absolute -inset-1 bg-gradient-to-r from-[#D0BCFF] to-[#F3EDF7] rounded-full blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                        <div class="relative flex items-center bg-white rounded-full shadow-sm border border-[#E7E0EC] px-2 py-1 focus-within:ring-4 focus-within:ring-[#E8DEF8] transition-all">
                            <div class="pl-4 text-[#6750A4]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input 
                                wire:model.live.debounce.400ms="search"
                                type="text" 
                                class="w-full border-none focus:ring-0 text-[#1D1B20] placeholder-[#49454F]/50 h-12 px-4 bg-transparent"
                                placeholder="Cari judul, penulis, ISBN..."
                            >
                            <div wire:loading wire:target="search" class="pr-4">
                                <svg class="animate-spin w-5 h-5 text-[#6750A4]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Category Filter (Scrollable Chips) --}}
                    <div class="flex justify-center gap-2 overflow-x-auto no-scrollbar py-2 mask-linear-fade">
                        <button 
                            wire:click="$set('selectedCategory', '')"
                            class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-bold transition-all duration-300 {{ $selectedCategory == '' ? 'bg-[#1D1B20] text-white shadow-md' : 'bg-white border border-[#E7E0EC] text-[#49454F] hover:bg-[#F3EDF7]' }}">
                            Semua
                        </button>
                        @foreach ($categories as $cat)
                            <button 
                                wire:click="$set('selectedCategory', {{ $cat->id }})"
                                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ $selectedCategory == $cat->id ? 'bg-[#6750A4] text-white shadow-md' : 'bg-white border border-[#E7E0EC] text-[#49454F] hover:bg-[#F3EDF7] hover:text-[#6750A4]' }}">
                                {{ $cat->nama_kategori }}
                            </button>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        {{-- GRID CONTENT --}}
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 mt-8" id="catalog-section">
            
            {{-- Hasil Pencarian Info --}}
            <div class="flex justify-between items-end mb-6 pb-2 border-b border-[#E7E0EC] gsap-fade-up">
                <h2 class="font-serif-display text-2xl text-[#1D1B20]">Koleksi Buku</h2>
                <span class="text-xs font-bold text-[#6750A4] bg-[#E8DEF8] px-2 py-1 rounded-md">
                    {{ $books->total() }} Buku
                </span>
            </div>

            {{-- GRID --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-6 gap-y-10">
                @forelse ($books as $book)
                    @php
                        $isOutOfStock = $book->stok_tersedia <= 0;
                        // Logika Gambar Aman
                        $coverUrl = 'https://placehold.co/400x600/F3EDF7/6750A4?text=' . urlencode($book->judul);
                        if ($book->gambar_cover) {
                            $coverUrl = Str::startsWith($book->gambar_cover, ['http', 'https']) 
                                ? $book->gambar_cover 
                                : asset('storage/' . $book->gambar_cover);
                        }
                    @endphp

                    {{-- CARD ITEM --}}
                    <div class="gsap-card group relative flex flex-col h-full">
                        <a href="{{ route('book.detail', $book->id) }}" class="flex flex-col h-full relative block">
                            
                            {{-- Cover Image Container --}}
                            <div class="relative aspect-[2/3] rounded-2xl overflow-hidden bg-[#F3EDF7] shadow-sm mb-4 border border-[#E7E0EC] transition-all duration-500 ease-out group-hover:-translate-y-2 group-hover:shadow-xl {{ $isOutOfStock ? 'grayscale opacity-80' : '' }}">
                                
                                <img 
                                    src="{{ $coverUrl }}" 
                                    alt="{{ $book->judul }}"
                                    class="w-full h-full object-cover transition-transform duration-700 ease-in-out group-hover:scale-110"
                                    loading="lazy"
                                >
                                
                                {{-- Overlay Gradient (Hover) --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-[#1D1B20]/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                {{-- Badges --}}
                                <div class="absolute top-3 left-3 right-3 flex justify-between items-start">
                                    @if($book->category)
                                        <span class="px-2 py-1 rounded-md bg-white/95 backdrop-blur text-[9px] font-bold uppercase tracking-wider text-[#6750A4] shadow-sm">
                                            {{ $book->category->nama_kategori }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Status Overlay jika Habis --}}
                                @if($isOutOfStock)
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/10 backdrop-blur-[1px]">
                                        <span class="bg-[#1D1B20] text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-lg transform -rotate-6 border border-white/20">
                                            Menunggu Restock
                                        </span>
                                    </div>
                                @endif

                                {{-- Hover Action --}}
                                <div class="absolute bottom-4 left-4 right-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300 delay-75">
                                    <div class="bg-white text-[#1D1B20] text-center text-xs font-bold py-3 rounded-xl shadow-lg hover:bg-[#6750A4] hover:text-white transition-colors">
                                        {{ $isOutOfStock ? 'Lihat Detail' : 'Pinjam Sekarang' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Book Info --}}
                            <div class="flex-grow flex flex-col px-1">
                                <h3 class="font-serif-display text-lg text-[#1D1B20] leading-[1.3] mb-1 line-clamp-2 group-hover:text-[#6750A4] transition-colors">
                                    {{ $book->judul }}
                                </h3>
                                <p class="text-xs font-medium text-[#49454F] mb-2 line-clamp-1">
                                    {{ $book->penulis ?? 'Penulis Tidak Diketahui' }}
                                </p>
                                
                                {{-- Status Bar --}}
                                <div class="mt-auto pt-3 border-t border-[#E7E0EC] border-dashed flex items-center justify-between">
                                    @if(!$isOutOfStock)
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-[#146C2E] animate-pulse"></span>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#146C2E]">
                                                Tersedia
                                            </span>
                                        </div>
                                        <span class="text-[10px] font-bold text-[#1D1B20] bg-[#F3EDF7] px-1.5 py-0.5 rounded">
                                            {{ $book->stok_tersedia }} Unit
                                        </span>
                                    @else
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-[#B3261E]"></span>
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#B3261E]">
                                                Stok Habis
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    {{-- EMPTY STATE --}}
                    <div class="col-span-full flex flex-col items-center justify-center py-24 gsap-fade-up">
                        <div class="w-20 h-20 bg-[#F3EDF7] rounded-full flex items-center justify-center mb-6">
                            <svg class="w-10 h-10 text-[#49454F]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="font-serif-display text-2xl text-[#1D1B20] mb-2">Tidak Ada Hasil</h3>
                        <p class="text-[#49454F] text-sm mb-6 text-center max-w-md">
                            Kami tidak menemukan buku yang sesuai dengan filter atau pencarian Anda.
                        </p>
                        <button wire:click="$set('search', '')" class="px-6 py-2.5 bg-[#1D1B20] text-white rounded-full text-sm font-bold hover:bg-[#6750A4] transition-all shadow-md">
                            Reset Semua Filter
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="mt-16 gsap-fade-up">
                {{ $books->links() }}
            </div>
        </div>
    </div>

    {{-- SCRIPT ANIMASI (Optimized) --}}
    <script>
        function initCatalog() {
            if (typeof gsap !== 'undefined') {
                
                // Hero Animation
                gsap.fromTo('.gsap-hero', 
                    { y: 30, opacity: 0 },
                    { y: 0, opacity: 1, duration: 1, stagger: 0.15, ease: 'power3.out' }
                );

                // Misc Elements
                gsap.fromTo('.gsap-fade-up', 
                    { y: 20, opacity: 0 },
                    { y: 0, opacity: 1, duration: 0.8, delay: 0.3, ease: 'power2.out' }
                );

                // Card Grid Stagger (Batching)
                if (typeof ScrollTrigger !== 'undefined') {
                    ScrollTrigger.batch(".gsap-card", {
                        onEnter: batch => gsap.fromTo(batch, 
                            { opacity: 0, y: 50 },
                            { opacity: 1, y: 0, stagger: 0.05, duration: 0.6, ease: "power2.out", overwrite: true }
                        ),
                        once: true
                    });
                } else {
                    gsap.fromTo('.gsap-card', { opacity: 0, y: 50 }, { opacity: 1, y: 0, stagger: 0.05, duration: 0.6 });
                }
            }
        }

        // Init on Load
        document.addEventListener('DOMContentLoaded', initCatalog);
        
        // Init on Livewire Update (Search/Filter)
        document.addEventListener('livewire:navigated', initCatalog);
    </script>
</div>