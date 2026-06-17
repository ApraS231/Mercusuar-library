<div>
    @section('title', 'Dashboard Kepala Perpustakaan')

    <div class="min-h-screen font-sans-text text-[#1D1B20] pb-12">
        {{-- Welcome Header --}}
        <div class="mb-8 bg-gradient-to-r from-[#F3EDF7] to-[#E8DEF8] rounded-[32px] p-8 border border-[#E7E0EC] shadow-sm relative overflow-hidden">
            <div class="relative z-10">
                <span class="inline-block py-1 px-3 rounded-full bg-[#6750A4] text-white text-[10px] font-bold uppercase tracking-[0.15em] mb-4 shadow-sm">
                    Laporan Eksekutif
                </span>
                <h1 class="font-serif-display text-3xl md:text-4xl text-[#1D1B20] mb-2 leading-tight">
                    Selamat Datang, <span class="italic text-[#6750A4]">Kepala Perpustakaan</span>.
                </h1>
                <p class="text-[#49454F] max-w-xl text-sm leading-relaxed">
                    Pantau kinerja sirkulasi, statistik keterlambatan anggota, dan aktivitas peminjaman buku perpustakaan secara real-time.
                </p>
            </div>
            {{-- Decorative pattern --}}
            <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 flex items-center justify-end pr-8 pointer-events-none">
                <svg class="w-48 h-48 text-[#6750A4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
            </div>
        </div>

        {{-- Primary Grid Metrics --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Total Sirkulasi --}}
            <div class="bg-white border border-[#E7E0EC] rounded-[24px] p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#F3EDF7] flex items-center justify-center text-[#6750A4] group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#49454F] uppercase tracking-wider">Total Sirkulasi</p>
                        <p class="text-3xl font-bold text-[#1D1B20] mt-1">{{ $totalPeminjaman }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-[#E7E0EC] flex justify-between text-xs text-[#49454F]">
                    <span>Selesai: <strong>{{ $peminjamanSelesai }}</strong></span>
                    <span>Aktif: <strong>{{ $pinjamanAktif }}</strong></span>
                </div>
            </div>

            {{-- Rasio Terlambat --}}
            <div class="bg-white border border-[#E7E0EC] rounded-[24px] p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#FFF8E1] flex items-center justify-center text-[#F57C00] group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#49454F] uppercase tracking-wider">Rasio Terlambat</p>
                        <p class="text-3xl font-bold text-[#1D1B20] mt-1">{{ $rasioKeterlambatan }}%</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-[#E7E0EC]">
                    <div class="w-full bg-[#E7E0EC] rounded-full h-1.5 overflow-hidden">
                        <div class="bg-[#F57C00] h-1.5 rounded-full" style="width: {{ min(100, $rasioKeterlambatan) }}%"></div>
                    </div>
                </div>
            </div>

            {{-- Total Buku --}}
            <div class="bg-white border border-[#E7E0EC] rounded-[24px] p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#E8DEF8] flex items-center justify-center text-[#6750A4] group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#49454F] uppercase tracking-wider">Koleksi Buku</p>
                        <p class="text-3xl font-bold text-[#1D1B20] mt-1">{{ $jumlahBuku }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-[#E7E0EC] flex justify-between text-xs text-[#49454F]">
                    <span>Total Judul Buku</span>
                </div>
            </div>

            {{-- Total Anggota --}}
            <div class="bg-white border border-[#E7E0EC] rounded-[24px] p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#E3F2FD] flex items-center justify-center text-[#1565C0] group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.965 5.965 0 0112 13a5.965 5.965 0 013 1.803"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-[#49454F] uppercase tracking-wider">Anggota Aktif</p>
                        <p class="text-3xl font-bold text-[#1D1B20] mt-1">{{ $jumlahAnggota }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-[#E7E0EC] flex justify-between text-xs text-[#49454F]">
                    <span>Total Pembaca Terdaftar</span>
                </div>
            </div>
        </div>

        {{-- Main Layout (Grid of 2 Cols) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Left Side: Recent Loans (8 cols) --}}
            <div class="lg:col-span-8 bg-white border border-[#E7E0EC] rounded-[28px] p-6 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-[#1D1B20]">Aktivitas Peminjaman Terbaru</h2>
                        <p class="text-xs text-[#49454F] mt-0.5">5 transaksi peminjaman buku teranyar.</p>
                    </div>
                    <a href="{{ route('kepala-perpus.laporan') }}" class="text-xs font-bold text-[#6750A4] hover:text-[#5F4999] transition-colors flex items-center gap-1">
                        Lihat Semua
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-[#E7E0EC]">
                        <thead class="bg-[#F3EDF7]">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider rounded-l-xl">Buku</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Anggota</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider rounded-r-xl">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-[#E7E0EC]">
                            @forelse ($recentLoans as $peminjaman)
                                <tr class="hover:bg-[#FDF7FF] transition-colors">
                                    {{-- Buku --}}
                                    <td class="px-4 py-3.5 whitespace-nowrap text-sm font-bold text-[#1D1B20]">
                                        {{ $peminjaman->book->judul ?? 'Buku Dihapus' }}
                                    </td>
                                    {{-- Anggota --}}
                                    <td class="px-4 py-3.5 whitespace-nowrap text-sm text-[#49454F]">
                                        {{ $peminjaman->user->name ?? 'User Dihapus' }}
                                    </td>
                                    {{-- Tanggal --}}
                                    <td class="px-4 py-3.5 whitespace-nowrap text-xs text-[#49454F]">
                                        {{ $peminjaman->tgl_booking->format('d M Y') }}
                                    </td>
                                    {{-- Status --}}
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        @php
                                            $statusEnum = $peminjaman->status;
                                            $badgeClass = match($statusEnum) {
                                                \App\Enums\StatusPeminjaman::Pinjam => 'bg-[#FFF8E1] text-[#F57C00] border-[#FFE0B2]',
                                                \App\Enums\StatusPeminjaman::Disetujui => 'bg-[#E3F2FD] text-[#1565C0] border-[#BBDEFB]',
                                                \App\Enums\StatusPeminjaman::Selesai => 'bg-[#E6F4EA] text-[#137333] border-[#C3EED4]',
                                                \App\Enums\StatusPeminjaman::Overdue => 'bg-[#F9DEDC] text-[#B3261E] border-[#F2B8B5] font-bold',
                                                default => 'bg-[#F5F5F5] text-[#616161] border-[#E0E0E0]',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 inline-flex text-[10px] leading-4 font-semibold rounded-full border {{ $badgeClass }}">
                                            {{ $statusEnum->value }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-12 text-center text-sm text-[#49454F]">
                                        Belum ada aktivitas transaksi peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right Side: Quick Links & Summary Info (4 cols) --}}
            <div class="lg:col-span-4 flex flex-col gap-6">
                {{-- Quick Actions --}}
                <div class="bg-white border border-[#E7E0EC] rounded-[28px] p-6 shadow-sm">
                    <h3 class="text-base font-bold text-[#1D1B20] mb-4">Aksi Cepat</h3>
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('kepala-perpus.laporan') }}" 
                           class="flex items-center justify-between p-4 bg-[#F3EDF7] hover:bg-[#E8DEF8] rounded-2xl border border-[#E7E0EC] group transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-[#6750A4] shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <span class="text-sm font-bold text-[#1D1B20] group-hover:text-[#6750A4] transition-colors">Buka Laporan</span>
                            </div>
                            <svg class="w-4 h-4 text-[#49454F] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <a href="{{ route('kepala-perpus.users') }}" 
                           class="flex items-center justify-between p-4 bg-[#E3F2FD]/50 hover:bg-[#E3F2FD] rounded-2xl border border-[#E7E0EC] group transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-white flex items-center justify-center text-[#1565C0] shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.965 5.965 0 0112 13a5.965 5.965 0 013 1.803"/></svg>
                                </div>
                                <span class="text-sm font-bold text-[#1D1B20] group-hover:text-[#1565C0] transition-colors">Manajemen Anggota</span>
                            </div>
                            <svg class="w-4 h-4 text-[#49454F] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Status Ringkasan --}}
                <div class="bg-white border border-[#E7E0EC] rounded-[28px] p-6 shadow-sm">
                    <h3 class="text-base font-bold text-[#1D1B20] mb-4">Ringkasan Sirkulasi</h3>
                    <div class="space-y-3.5">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-[#49454F]">Pinjaman Terlambat (Late)</span>
                            <span class="font-bold text-[#B3261E] bg-[#F9DEDC] px-2.5 py-0.5 rounded-full">{{ $peminjamanOverdue }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-[#49454F]">Peminjaman Aktif</span>
                            <span class="font-bold text-[#1565C0] bg-[#E3F2FD] px-2.5 py-0.5 rounded-full">{{ $pinjamanAktif }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-[#49454F]">Peminjaman Selesai</span>
                            <span class="font-bold text-[#137333] bg-[#E6F4EA] px-2.5 py-0.5 rounded-full">{{ $peminjamanSelesai }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
