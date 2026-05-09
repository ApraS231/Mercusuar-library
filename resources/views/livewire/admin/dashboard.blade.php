@section('title', 'Dashboard Overview')

<div class="min-h-screen pb-12 font-sans-text">
    
    {{-- Welcome Banner --}}
    <div class="bg-[#1D1B20] rounded-[32px] p-8 md:p-12 mb-10 relative overflow-hidden shadow-xl gsap-header opacity-0 translate-y-5">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-[#6750A4] opacity-20 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-[#D0BCFF] opacity-10 blur-2xl"></div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between md:items-end gap-6">
            <div>
                <p class="text-[#D0BCFF] font-bold text-xs uppercase tracking-widest mb-2">Admin Panel</p>
                <h1 class="font-serif-display text-3xl md:text-5xl text-white leading-tight">
                    Selamat Datang, <br>
                    <span class="text-[#D0BCFF] italic">{{ auth()->user()->name }}</span>
                </h1>
            </div>
            <div class="bg-white/10 backdrop-blur-md border border-white/10 px-6 py-3 rounded-full flex items-center gap-3">
                <div class="w-2 h-2 rounded-full bg-[#4ADE80] animate-pulse"></div>
                <span class="text-white text-sm font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Stat Cards Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <div class="gsap-card opacity-0 bg-white rounded-[24px] p-6 border border-[#E7E0EC] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 rounded-2xl bg-[#FFF8E1] flex items-center justify-center text-[#F57C00]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="bg-[#FFF8E1] text-[#F57C00] text-[10px] font-bold px-2 py-1 rounded-full uppercase">Pending</span>
            </div>
            <p class="text-[#49454F] text-sm font-medium">Menunggu Persetujuan</p>
            <h3 class="text-4xl font-serif-display text-[#1D1B20] mt-1">
                <span class="gsap-count" data-value="{{ $pendingLoans }}">0</span>
            </h3>
        </div>

        <div class="gsap-card opacity-0 bg-white rounded-[24px] p-6 border border-[#E7E0EC] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 rounded-2xl bg-[#E8DEF8] flex items-center justify-center text-[#6750A4]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <p class="text-[#49454F] text-sm font-medium">Total Koleksi Buku</p>
            <h3 class="text-4xl font-serif-display text-[#1D1B20] mt-1">
                <span class="gsap-count" data-value="{{ $jumlahBuku }}">0</span>
            </h3>
        </div>

        <div class="gsap-card opacity-0 bg-white rounded-[24px] p-6 border border-[#E7E0EC] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 rounded-2xl bg-[#E3F2FD] flex items-center justify-center text-[#1565C0]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            <p class="text-[#49454F] text-sm font-medium">Anggota Terdaftar</p>
            <h3 class="text-4xl font-serif-display text-[#1D1B20] mt-1">
                <span class="gsap-count" data-value="{{ $jumlahUserAktif }}">0</span>
            </h3>
        </div>

        <div class="gsap-card opacity-0 bg-[#FFF8F6] rounded-[24px] p-6 border border-[#F2B8B5] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 rounded-2xl bg-[#F9DEDC] flex items-center justify-center text-[#B3261E]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="bg-[#B3261E] text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase">Alert</span>
            </div>
            <p class="text-[#B3261E] text-sm font-bold">Terlambat (Overdue)</p>
            <h3 class="text-4xl font-serif-display text-[#B3261E] mt-1">
                <span class="gsap-count" data-value="{{ $jumlahOverdue }}">0</span>
            </h3>
        </div>

    </div>

    {{-- Section Bawah (Quick Actions & Info) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-[32px] border border-[#E7E0EC] p-8 shadow-sm gsap-fade-up opacity-0">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-serif-display text-[#1D1B20]">Akses Cepat</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('admin.books.index') }}" class="group flex items-center gap-4 p-4 rounded-2xl bg-[#FDF7FF] border border-[#E7E0EC] hover:border-[#6750A4] hover:shadow-sm transition-all">
                    <div class="w-12 h-12 rounded-full bg-[#E8DEF8] flex items-center justify-center text-[#6750A4] group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-[#1D1B20]">Input Buku Baru</h4>
                        <p class="text-xs text-[#49454F]">Tambah koleksi ke database</p>
                    </div>
                </a>
                <a href="{{ route('admin.transactions.index') }}" class="group flex items-center gap-4 p-4 rounded-2xl bg-[#FDF7FF] border border-[#E7E0EC] hover:border-[#6750A4] hover:shadow-sm transition-all">
                    <div class="w-12 h-12 rounded-full bg-[#E8DEF8] flex items-center justify-center text-[#6750A4] group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-[#1D1B20]">Verifikasi Peminjaman</h4>
                        <p class="text-xs text-[#49454F]">Cek request peminjaman masuk</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[32px] border border-[#E7E0EC] p-8 shadow-sm gsap-fade-up opacity-0 flex flex-col justify-center text-center">
            <div class="mb-4">
                <div class="w-16 h-16 mx-auto bg-[#E6F4EA] rounded-full flex items-center justify-center text-[#146C2E] mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="font-bold text-[#1D1B20] text-lg">Sistem Berjalan Normal</h3>
                <p class="text-xs text-[#49454F]">Tidak ada gangguan terdeteksi</p>
            </div>
            <div class="w-full h-px bg-[#E7E0EC] my-4"></div>
            <p class="text-[10px] text-[#49454F] uppercase tracking-widest">Mercusuar Library v1.0</p>
        </div>

    </div>

    {{-- Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', initDash);
        document.addEventListener('livewire:navigated', initDash);

        function initDash() {
            if (typeof gsap !== 'undefined') {
                gsap.to('.gsap-header', { opacity: 1, y: 0, duration: 1, ease: 'power3.out' });
                gsap.to('.gsap-card', { opacity: 1, y: 0, duration: 0.8, stagger: 0.1, delay: 0.2, ease: 'back.out(1.7)' });
                gsap.to('.gsap-fade-up', { opacity: 1, y: 0, duration: 0.8, stagger: 0.1, delay: 0.5, ease: 'power3.out' });

                gsap.utils.toArray(".gsap-count").forEach(el => {
                    gsap.to(el, {
                        innerHTML: el.getAttribute("data-value"),
                        duration: 1.5,
                        snap: { innerHTML: 1 },
                        ease: "power2.out",
                        delay: 0.4
                    });
                });
            }
        }
    </script>
</div>