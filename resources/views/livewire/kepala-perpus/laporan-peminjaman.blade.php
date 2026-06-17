<div>
    @section('title', 'Laporan Peminjaman')

    <div class="min-h-screen font-sans-text text-[#1D1B20] pb-12">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <h1 class="font-serif-display text-3xl md:text-4xl text-[#1D1B20]">Laporan Sirkulasi Buku</h1>
                <p class="text-sm text-[#49454F] mt-1">Pantau performa, statistik, dan riwayat sirkulasi pustaka.</p>
            </div>
            <button wire:click="exportCSV" 
                class="inline-flex items-center justify-center gap-2 bg-[#6750A4] text-white hover:bg-[#5F4999] px-6 py-3 rounded-full text-sm font-bold shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                <span>Ekspor CSV</span>
            </button>
        </div>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            {{-- Total Pinjam --}}
            <div class="bg-white border border-[#E7E0EC] rounded-[24px] p-6 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#F3EDF7] flex items-center justify-center text-[#6750A4]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#49454F] uppercase tracking-wider">Total Transaksi</p>
                    <p class="text-2xl font-bold text-[#1D1B20] mt-1">{{ $stats['total'] }}</p>
                </div>
            </div>

            {{-- Pinjaman Aktif --}}
            <div class="bg-white border border-[#E7E0EC] rounded-[24px] p-6 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#E3F2FD] flex items-center justify-center text-[#1565C0]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#49454F] uppercase tracking-wider">Sedang Dipinjam</p>
                    <p class="text-2xl font-bold text-[#1D1B20] mt-1">{{ $stats['aktif'] }}</p>
                </div>
            </div>

            {{-- Selesai --}}
            <div class="bg-white border border-[#E7E0EC] rounded-[24px] p-6 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#E6F4EA] flex items-center justify-center text-[#137333]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#49454F] uppercase tracking-wider">Selesai Kembali</p>
                    <p class="text-2xl font-bold text-[#1D1B20] mt-1">{{ $stats['selesai'] }}</p>
                </div>
            </div>

            {{-- Overdue --}}
            <div class="bg-white border border-[#E7E0EC] rounded-[24px] p-6 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-[#F9DEDC] flex items-center justify-center text-[#B3261E]">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-[#49454F] uppercase tracking-wider">Terlambat (Overdue)</p>
                    <p class="text-2xl font-bold text-[#1D1B20] mt-1">{{ $stats['overdue'] }}</p>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="bg-white border border-[#E7E0EC] rounded-[28px] p-6 shadow-sm mb-6">
            <h3 class="text-base font-bold text-[#1D1B20] mb-4">Filter Laporan</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Search --}}
                <div class="relative">
                    <input type="text" 
                        wire:model.live.debounce.500ms="search"
                        class="w-full bg-[#FEF7FF] border border-[#79747E] rounded-2xl py-3 px-4 pl-11 text-sm text-[#1D1B20] focus:outline-none focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all placeholder-[#49454F]/50" 
                        placeholder="Cari buku atau anggota...">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#6750A4]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>

                {{-- Tanggal Mulai --}}
                <div>
                    <input type="date" 
                        wire:model.live="startDate"
                        class="w-full bg-[#FEF7FF] border border-[#79747E] rounded-2xl py-3 px-4 text-sm text-[#1D1B20] focus:outline-none focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all"
                        title="Tanggal Mulai">
                </div>

                {{-- Tanggal Selesai --}}
                <div>
                    <input type="date" 
                        wire:model.live="endDate"
                        class="w-full bg-[#FEF7FF] border border-[#79747E] rounded-2xl py-3 px-4 text-sm text-[#1D1B20] focus:outline-none focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all"
                        title="Tanggal Akhir">
                </div>
            </div>
        </div>

        {{-- Tabel Laporan --}}
        <div class="bg-white border border-[#E7E0EC] rounded-[28px] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#E7E0EC]">
                    <thead class="bg-[#F3EDF7]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Buku</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Anggota</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Tgl Pinjam</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Tgl Disetujui</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Tgl Kembali</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#E7E0EC]">
                        @forelse ($peminjamans as $peminjaman)
                            <tr class="hover:bg-[#FDF7FF] transition-colors">
                                {{-- Buku --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-lg bg-[#F3EDF7] flex items-center justify-center text-[#6750A4] flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-[#1D1B20]">{{ $peminjaman->book->judul ?? 'Buku Dihapus' }}</div>
                                            <div class="text-xs text-[#49454F]">{{ $peminjaman->book->penulis ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Anggota --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-[#1D1B20]">{{ $peminjaman->user->name ?? 'User Dihapus' }}</div>
                                    <div class="text-xs text-[#49454F]">{{ $peminjaman->user->email ?? '-' }}</div>
                                </td>

                                {{-- Tanggal Pinjam (Booking) --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#1D1B20]">
                                    {{ $peminjaman->tgl_booking ? $peminjaman->tgl_booking->format('d M Y') : '-' }}
                                </td>

                                {{-- Tanggal Disetujui --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#1D1B20]">
                                    {{ $peminjaman->tgl_disetujui ? $peminjaman->tgl_disetujui->format('d M Y') : '-' }}
                                </td>

                                {{-- Tanggal Selesai --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#1D1B20]">
                                    {{ $peminjaman->tgl_selesai ? $peminjaman->tgl_selesai->format('d M Y') : '-' }}
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap">
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
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $badgeClass }}">
                                        {{ $statusEnum->value }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-[#F3EDF7] rounded-full flex items-center justify-center mb-4 text-[#49454F]">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                        </div>
                                        <p class="text-[#1D1B20] font-medium">Tidak ada data</p>
                                        <p class="text-[#49454F] text-sm mt-1">Tidak ada laporan peminjaman yang cocok dengan filter Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Link Paginasi --}}
            @if ($peminjamans->hasPages())
                <div class="p-6 border-t border-[#E7E0EC] bg-[#FDF7FF]">
                    {{ $peminjamans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
