<div>
    {{-- SEO Meta --}}
    @push('seo')
        <title>Riwayat Peminjaman | Mercusuar Library</title>
    @endpush

    {{-- Assets (Fonts & GSAP) --}}
    @assets
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/ScrollTrigger.min.js"></script>
    <style>
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-sans-text { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-dots-pattern {
            background-image: radial-gradient(#E7E0EC 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
    @endassets

    <div class="min-h-screen bg-[#FDF7FF] font-sans-text text-[#1D1B20] pb-24 relative">
        
        {{-- HEADER SECTION --}}
        <div class="relative pt-28 pb-12 px-6 md:px-8 bg-dots-pattern border-b border-[#E7E0EC]/60">
            <div class="max-w-5xl mx-auto">
                <div class="gsap-header opacity-0 translate-y-5">
                    <span class="inline-block py-1 px-3 rounded-full bg-white border border-[#E7E0EC] text-[10px] font-bold uppercase tracking-[0.15em] text-[#6750A4] mb-4 shadow-sm">
                        Dashboard Anggota
                    </span>
                    <h1 class="font-serif-display text-4xl md:text-5xl text-[#1D1B20] mb-4 leading-tight">
                        Riwayat <span class="italic text-[#6750A4]">Sirkulasi</span> Buku.
                    </h1>
                    <p class="text-[#49454F] max-w-xl text-lg leading-relaxed">
                        Pantau status peminjaman, jadwal pengembalian, dan jejak literasi Anda di sini.
                    </p>
                </div>
            </div>
        </div>

        {{-- CONTENT SECTION --}}
        <div class="max-w-5xl mx-auto px-4 sm:px-6 md:px-8 mt-12 space-y-6">
            
            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="gsap-fade-up opacity-0 bg-[#E6F4EA] border border-[#C3EED4] text-[#146C2E] p-4 rounded-2xl flex items-center gap-3 shadow-sm mb-8">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="gsap-fade-up opacity-0 bg-[#F9DEDC] border border-[#F2B8B5] text-[#B3261E] p-4 rounded-2xl flex items-center gap-3 shadow-sm mb-8">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
            @endif

            {{-- LIST CARD --}}
            <div id="loans-list">
                @forelse ($peminjamans as $peminjaman)
                    @php
                        // Logika Warna & Teks Status
                        $status = $peminjaman->status;
                        $statusConfig = match($status) {
                            \App\Enums\StatusPeminjaman::Pending => ['bg' => 'bg-[#FFF8E1]', 'text' => 'text-[#F57C00]', 'border' => 'border-[#FFE0B2]', 'label' => 'Menunggu Konfirmasi'],
                            \App\Enums\StatusPeminjaman::Disetujui, \App\Enums\StatusPeminjaman::Diproses => ['bg' => 'bg-[#E3F2FD]', 'text' => 'text-[#1565C0]', 'border' => 'border-[#BBDEFB]', 'label' => 'Sedang Diproses'],
                            \App\Enums\StatusPeminjaman::Diantar => ['bg' => 'bg-[#E0F7FA]', 'text' => 'text-[#006064]', 'border' => 'border-[#B2EBF2]', 'label' => 'Sedang Diantar'],
                            \App\Enums\StatusPeminjaman::Diterima => ['bg' => 'bg-[#E6F4EA]', 'text' => 'text-[#137333]', 'border' => 'border-[#C3EED4]', 'label' => 'Sedang Dipinjam'],
                            \App\Enums\StatusPeminjaman::Overdue => ['bg' => 'bg-[#F9DEDC]', 'text' => 'text-[#B3261E]', 'border' => 'border-[#F2B8B5]', 'label' => 'Terlambat'],
                            \App\Enums\StatusPeminjaman::Ditolak => ['bg' => 'bg-[#F5F5F5]', 'text' => 'text-[#616161]', 'border' => 'border-[#E0E0E0]', 'label' => 'Ditolak'],
                            default => ['bg' => 'bg-[#F5F5F5]', 'text' => 'text-[#49454F]', 'border' => 'border-[#E0E0E0]', 'label' => 'Dikembalikan'],
                        };

                        // URL Gambar Aman
                        $coverUrl = 'https://placehold.co/400x600/F3EDF7/6750A4?text=' . urlencode($peminjaman->book->judul);
                        if ($peminjaman->book->gambar_cover) {
                            $coverUrl = Str::startsWith($peminjaman->book->gambar_cover, ['http', 'https']) 
                                ? $peminjaman->book->gambar_cover 
                                : asset('storage/' . $peminjaman->book->gambar_cover);
                        }
                    @endphp

                    {{-- CARD ITEM --}}
                    <div class="gsap-card opacity-0 group relative bg-white rounded-[24px] p-5 border border-[#E7E0EC] shadow-sm hover:shadow-[0_8px_30px_rgba(103,80,164,0.08)] hover:border-[#D0BCFF] transition-all duration-300 mb-6 overflow-hidden">
                        
                        {{-- Decorative Sidebar Line --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $status == \App\Enums\StatusPeminjaman::Overdue ? 'bg-[#B3261E]' : ($status == \App\Enums\StatusPeminjaman::Diterima ? 'bg-[#146C2E]' : 'bg-[#E7E0EC]') }}"></div>

                        <div class="flex flex-col md:flex-row gap-6 items-start pl-4">
                            
                            {{-- 1. Cover Image (Floating) --}}
                            <div class="relative shrink-0 w-full md:w-auto flex justify-center md:block">
                                <div class="w-24 aspect-[2/3] rounded-xl overflow-hidden shadow-md border border-[#E7E0EC] group-hover:scale-105 transition-transform duration-500 bg-[#F3EDF7]">
                                    <img 
                                        src="{{ $coverUrl }}" 
                                        alt="{{ $peminjaman->book->judul }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >
                                </div>
                            </div>

                            {{-- 2. Info Details --}}
                            <div class="flex-grow w-full">
                                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-3">
                                    <div>
                                        {{-- Status Badge --}}
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} {{ $statusConfig['border'] }} mb-2">
                                            @if($status == \App\Enums\StatusPeminjaman::Overdue)
                                                <svg class="w-3 h-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            @else
                                                <span class="w-1.5 h-1.5 rounded-full {{ str_replace('text-', 'bg-', $statusConfig['text']) }}"></span>
                                            @endif
                                            {{ $statusConfig['label'] }}
                                        </span>

                                        <h3 class="font-serif-display text-2xl text-[#1D1B20] leading-tight group-hover:text-[#6750A4] transition-colors">
                                            <a href="{{ route('book.detail', $peminjaman->book_id) }}">
                                                {{ $peminjaman->book->judul }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-[#49454F] font-medium mt-1">oleh {{ $peminjaman->book->penulis }}</p>
                                    </div>

                                    {{-- Action Buttons (Desktop Position) --}}
                                    <div class="hidden md:block">
                                        @if($status === \App\Enums\StatusPeminjaman::Diantar)
                                            <button wire:click="confirmReceipt({{ $peminjaman->id }})"
                                                class="flex items-center gap-2 bg-[#146C2E] hover:bg-[#0F5522] text-white px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wide shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Terima Buku
                                            </button>
                                        @elseif($status === \App\Enums\StatusPeminjaman::Dikembalikan)
                                            <a href="{{ route('book.detail', $peminjaman->book_id) }}" 
                                               class="flex items-center gap-2 bg-white border border-[#79747E] text-[#6750A4] hover:bg-[#F3EDF7] px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-wide transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Beri Ulasan
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                {{-- Timeline / Dates --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4 bg-[#FDF7FF] rounded-xl p-4 border border-[#E7E0EC]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#E8DEF8] flex items-center justify-center text-[#6750A4]">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div>
                                            <p class="text-[10px] text-[#49454F] uppercase tracking-wider font-bold">Tanggal Booking</p>
                                            <p class="text-sm font-medium text-[#1D1B20]">{{ $peminjaman->tgl_booking->format('d F Y, H:i') }}</p>
                                        </div>
                                    </div>

                                    @if($peminjaman->tgl_jatuh_tempo)
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full {{ $status == \App\Enums\StatusPeminjaman::Overdue ? 'bg-[#F9DEDC] text-[#B3261E]' : 'bg-[#E6F4EA] text-[#146C2E]' }} flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-[#49454F] uppercase tracking-wider font-bold">Batas Pengembalian</p>
                                                <p class="text-sm font-bold {{ $status == \App\Enums\StatusPeminjaman::Overdue ? 'text-[#B3261E]' : 'text-[#1D1B20]' }}">
                                                    {{ \Carbon\Carbon::parse($peminjaman->tgl_jatuh_tempo)->format('d F Y') }}
                                                    @if($status == \App\Enums\StatusPeminjaman::Overdue)
                                                        <span class="text-[10px] bg-[#B3261E] text-white px-1.5 rounded ml-1">LEWAT</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-3 opacity-50">
                                            <div class="w-8 h-8 rounded-full bg-[#F5F5F5] flex items-center justify-center text-[#49454F]">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                            </div>
                                            <div>
                                                <p class="text-[10px] text-[#49454F] uppercase tracking-wider font-bold">Batas Pengembalian</p>
                                                <p class="text-sm text-[#49454F]">Menunggu Konfirmasi</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Action Buttons (Mobile Position) --}}
                                <div class="md:hidden mt-4 pt-4 border-t border-[#E7E0EC] flex justify-end">
                                    @if($status === \App\Enums\StatusPeminjaman::Diantar)
                                        <button wire:click="confirmReceipt({{ $peminjaman->id }})"
                                            class="w-full flex justify-center items-center gap-2 bg-[#146C2E] text-white px-4 py-3 rounded-xl text-sm font-bold uppercase tracking-wide shadow-sm">
                                            Terima Buku
                                        </button>
                                    @elseif($status === \App\Enums\StatusPeminjaman::Dikembalikan)
                                        <a href="{{ route('book.detail', $peminjaman->book_id) }}" 
                                           class="w-full flex justify-center items-center gap-2 bg-white border border-[#79747E] text-[#6750A4] px-4 py-3 rounded-xl text-sm font-bold uppercase tracking-wide">
                                            Beri Ulasan
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- EMPTY STATE --}}
                    <div class="py-24 text-center gsap-fade-up opacity-0">
                        <div class="w-24 h-24 bg-[#F3EDF7] rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                            <svg class="w-10 h-10 text-[#6750A4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="font-serif-display text-2xl text-[#1D1B20] mb-2">Belum Ada Peminjaman</h3>
                        <p class="text-[#49454F] mb-8 max-w-sm mx-auto">
                            Anda belum memiliki riwayat peminjaman buku. Mulai petualangan literasi Anda sekarang.
                        </p>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-[#1D1B20] text-white rounded-full text-sm font-bold hover:bg-[#6750A4] transition-all shadow-lg transform hover:-translate-y-1">
                            <span>Jelajahi Katalog</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        function initLoansAnim() {
            if (typeof gsap !== 'undefined') {
                // Header Entry
                gsap.fromTo('.gsap-header', 
                    { y: 30, opacity: 0 }, 
                    { y: 0, opacity: 1, duration: 1, ease: 'power3.out' }
                );

                // Misc Elements
                gsap.fromTo('.gsap-fade-up', 
                    { y: 20, opacity: 0 },
                    { y: 0, opacity: 1, duration: 0.8, delay: 0.3, ease: 'power2.out' }
                );

                // Card Stagger
                if (typeof ScrollTrigger !== 'undefined') {
                    ScrollTrigger.batch(".gsap-card", {
                        onEnter: batch => gsap.fromTo(batch, 
                            { opacity: 0, y: 50 },
                            { opacity: 1, y: 0, stagger: 0.1, duration: 0.8, ease: "power2.out", overwrite: true }
                        ),
                        once: true
                    });
                } else {
                    gsap.fromTo('.gsap-card', { opacity: 0, y: 50 }, { opacity: 1, y: 0, stagger: 0.1, duration: 0.8 });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', initLoansAnim);
        document.addEventListener('livewire:navigated', initLoansAnim);
    </script>
</div>