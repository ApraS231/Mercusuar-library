<div class="min-h-screen bg-[#FDF7FF] text-[#1D1B20] font-sans pb-12">
    
    {{-- Tambahkan Library Flatpickr (Date Picker Mewah) --}}
    @assets
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        /* Custom CSS untuk mengubah warna Flatpickr jadi Ungu MD3 */
        .flatpickr-calendar { border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); border: none; }
        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
            background: #6750A4 !important;
            border-color: #6750A4 !important;
        }
        .flatpickr-months .flatpickr-month { background: #F3EDF7; color: #1D1B20; fill: #1D1B20; border-radius: 16px 16px 0 0; padding-top: 10px; }
        .flatpickr-current-month .flatpickr-monthDropdown-months { font-weight: 500; }
        .flatpickr-weekdays { background: #F3EDF7; }
        span.flatpickr-weekday { color: #49454F; font-weight: 600; }
        .flatpickr-time input:hover, .flatpickr-time .flatpickr-am-pm:hover, .flatpickr-time input:focus, .flatpickr-time .flatpickr-am-pm:focus { background: #E8DEF8; }
    </style>
    @endassets

    {{-- Breadcrumb --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <nav class="flex items-center text-sm font-medium text-[#49454F]">
            <a href="{{ route('dashboard') }}" class="hover:text-[#6750A4] transition-colors">Katalog</a>
            <svg class="w-4 h-4 mx-2 text-[#CAC4D0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-[#1D1B20] truncate">{{ $book->judul }}</span>
        </nav>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-[32px] shadow-[0_2px_12px_rgba(0,0,0,0.04)] border border-[#E7E0EC] overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                
                {{-- LEFT: Image Section --}}
                <div class="lg:col-span-5 bg-[#F3EDF7] p-8 lg:p-12 flex flex-col items-center justify-center relative">
                    <div class="relative w-4/5 mx-auto shadow-2xl rounded-lg rotate-[-2deg] hover:rotate-0 transition-all duration-500">
                        <img 
                            src="{{ $book->gambar_cover ? asset('storage/' . $book->gambar_cover) : 'https://placehold.co/400x600/E8DEF8/6750A4?text=No+Cover' }}" 
                            alt="Cover" 
                            class="w-full h-auto rounded-lg"
                        >
                    </div>
                    
                    {{-- Floating Stats --}}
                    <div class="mt-10 grid grid-cols-2 gap-4 w-full max-w-xs">
                        <div class="bg-white/60 backdrop-blur rounded-2xl p-4 text-center border border-white/50 shadow-sm">
                            <p class="text-xs font-bold text-[#6750A4] uppercase tracking-wider">Stok</p>
                            <p class="text-2xl font-medium text-[#1D1B20]">{{ $book->stok_tersedia }}</p>
                        </div>
                        <div class="bg-white/60 backdrop-blur rounded-2xl p-4 text-center border border-white/50 shadow-sm">
                            <p class="text-xs font-bold text-[#6750A4] uppercase tracking-wider">Rating</p>
                            <div class="flex justify-center items-center gap-1 mt-1">
                                <span class="text-2xl font-medium text-[#1D1B20]">{{ number_format($book->reviews->avg('rating') ?? 0, 1) }}</span>
                                <svg class="w-4 h-4 text-[#FFD700] fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Detail & Booking --}}
                <div class="lg:col-span-7 p-8 lg:p-12 flex flex-col">
                    
                    <div class="mb-8">
                        @if ($book->category)
                            <span class="inline-block py-1 px-3 rounded-full bg-[#E8DEF8] text-[#1D1B20] text-xs font-medium mb-4">
                                {{ $book->category->nama_kategori }}
                            </span>
                        @endif
                        <h1 class="text-3xl md:text-4xl font-medium text-[#1D1B20] mb-2 tracking-tight">{{ $book->judul }}</h1>
                        <p class="text-lg text-[#49454F]">oleh <span class="text-[#1D1B20]">{{ $book->penulis }}</span></p>
                    </div>

                    {{-- Tabs / Description --}}
                    <div class="mb-10">
                        <h3 class="text-sm font-bold text-[#1D1B20] uppercase tracking-wide mb-3">Sinopsis</h3>
                        <p class="text-[#49454F] leading-relaxed whitespace-pre-wrap">{{ $book->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>

                    {{-- Booking Form --}}
                    <div class="mt-auto bg-[#FEF7FF] rounded-[24px] p-6 border border-[#E7E0EC]">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-medium text-[#1D1B20]">Pinjam Buku Ini</h3>
                            @if (session('error'))
                                <span class="text-xs font-medium text-[#B3261E] bg-[#F9DEDC] px-3 py-1 rounded-lg">{{ session('error') }}</span>
                            @endif
                        </div>

                        @if ($book->stok_tersedia > 0)
                            <form wire:submit="bookNow" class="space-y-5">
                                {{-- Alamat --}}
                                <div class="relative">
                                    <textarea wire:model="alamat_pengantaran" rows="2" 
                                        class="block px-4 pb-2.5 pt-5 w-full text-sm text-[#1D1B20] bg-white border border-[#79747E] rounded-2xl appearance-none focus:outline-none focus:ring-2 focus:ring-[#6750A4] focus:border-transparent peer transition-all"
                                        placeholder=" "></textarea>
                                    <label class="absolute text-sm text-[#49454F] duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-[#6750A4] bg-white px-1">
                                        Alamat Pengantaran
                                    </label>
                                </div>
                                @error('alamat_pengantaran') <span class="text-xs text-[#B3261E] ml-4">{{ $message }}</span> @enderror

                                {{-- Jadwal (FLATPICKR IMPLEMENTATION) --}}
                                <div class="relative" 
                                     x-data="{ 
                                        init() {
                                            flatpickr(this.$refs.picker, {
                                                enableTime: true,
                                                dateFormat: 'Y-m-d H:i',
                                                minDate: 'today',
                                                time_24hr: true,
                                                disableMobile: true, // Paksa tampilan Flatpickr di Mobile juga agar mewah
                                                onChange: (selectedDates, dateStr, instance) => {
                                                    @this.set('usulan_jadwal', dateStr);
                                                }
                                            });
                                        }
                                     }"
                                     wire:ignore> {{-- wire:ignore penting agar Livewire tidak me-reset elemen ini --}}
                                    
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-20">
                                        <svg class="w-5 h-5 text-[#6750A4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    
                                    <input type="text" x-ref="picker" wire:model="usulan_jadwal"
                                        class="block px-4 pb-2.5 pt-5 pl-12 w-full text-sm text-[#1D1B20] bg-white border border-[#79747E] rounded-2xl appearance-none focus:outline-none focus:ring-2 focus:ring-[#6750A4] focus:border-transparent peer transition-all cursor-pointer"
                                        placeholder=" ">
                                    
                                    <label class="absolute text-sm text-[#49454F] duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-12 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 peer-focus:text-[#6750A4] bg-white px-1 pointer-events-none">
                                        Usulan Jadwal (Opsional)
                                    </label>
                                </div>
                                @error('usulan_jadwal') <span class="text-xs text-[#B3261E] ml-4">{{ $message }}</span> @enderror

                                <button type="submit" 
                                    class="w-full bg-[#6750A4] hover:bg-[#5F4999] text-white font-medium rounded-full py-3.5 shadow-sm hover:shadow-md transition-all duration-300 flex justify-center items-center gap-2">
                                    <span wire:loading.remove wire:target="bookNow">Konfirmasi Peminjaman</span>
                                    <span wire:loading wire:target="bookNow" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Memproses...
                                    </span>
                                </button>
                            </form>
                        @else
                            <div class="w-full bg-[#E7E0EC] text-[#49454F] font-medium rounded-full py-3.5 text-center cursor-not-allowed">
                                Stok Habis
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Review Section --}}
        <div class="max-w-4xl mx-auto mt-12">
            <h3 class="text-2xl font-normal text-[#1D1B20] mb-6">Ulasan Pembaca</h3>
            
            @if ($sudahPernahPinjam && !$sudahReview)
                <div class="bg-white p-6 rounded-[24px] border border-[#E7E0EC] shadow-sm mb-8">
                    <h4 class="text-base font-medium mb-4">Tulis Ulasan Anda</h4>
                    <form wire:submit="addReview">
                        <div class="flex gap-4 mb-4">
                            @for($i=5; $i>=1; $i--)
                                <label class="cursor-pointer group">
                                    <input type="radio" wire:model="newReviewRating" value="{{ $i }}" class="sr-only peer">
                                    <span class="text-2xl text-[#E7E0EC] peer-checked:text-[#FFD700] group-hover:text-[#FFD700] transition-colors">★</span>
                                </label>
                            @endfor
                        </div>
                        <textarea wire:model="newReviewComment" rows="2" class="w-full bg-[#F9F9F9] border-0 rounded-xl p-4 focus:ring-2 focus:ring-[#6750A4] placeholder-[#49454F]/50" placeholder="Bagikan pendapat Anda..."></textarea>
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="bg-[#1D1B20] text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-[#6750A4] transition-colors">Kirim</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="space-y-4">
                @forelse ($book->reviews as $review)
                    <div class="bg-white p-6 rounded-[24px] border border-[#F3EDF7]">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#E8DEF8] flex items-center justify-center text-[#6750A4] font-medium text-sm">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-[#1D1B20]">{{ $review->user->name }}</p>
                                    <p class="text-xs text-[#49454F]">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex text-[#FFD700]">
                                @for($j=0; $j<$review->rating; $j++) ★ @endfor
                            </div>
                        </div>
                        <p class="mt-3 text-[#49454F] text-sm leading-relaxed pl-14">{{ $review->komentar }}</p>
                    </div>
                @empty
                    <p class="text-center text-[#49454F] py-8">Belum ada ulasan untuk buku ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>