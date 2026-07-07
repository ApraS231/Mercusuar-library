<div>
    @section('title', 'Pemantauan Anggota')

    <div class="min-h-screen font-sans-text text-[#1D1B20] pb-12">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="font-serif-display text-3xl md:text-4xl text-[#1D1B20]">Pemantauan Keanggotaan</h1>
            <p class="text-sm text-[#49454F] mt-1">Pantau keaktifan anggota, status peminjaman, dan keterlambatan pengembalian buku.</p>
        </div>

        {{-- Filter & Search Bar --}}
        <div class="bg-white border border-[#E7E0EC] p-4 rounded-[24px] shadow-sm mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
            {{-- Search --}}
            <div class="relative w-full md:w-1/3">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-[#49454F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Cari nama atau email..." 
                    class="w-full bg-[#F3EDF7] border-none rounded-full py-2.5 pl-10 pr-4 text-sm focus:ring-2 focus:ring-[#6750A4] placeholder-[#49454F]/60 transition-shadow"
                >
            </div>
            
            {{-- Filter Status Akun --}}
            <div class="w-full md:w-auto min-w-[200px]">
                <select wire:model.live="filterStatus" class="w-full bg-white border border-[#79747E] rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-[#6750A4] focus:border-[#6750A4] transition-shadow cursor-pointer">
                    <option value="all">Semua Status Akun</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Tabel Anggota --}}
        <div class="bg-white border border-[#E7E0EC] rounded-[28px] shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#E7E0EC]">
                    <thead class="bg-[#F3EDF7]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider rounded-l-xl">Profil Anggota</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Status Akun</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Buku Dipinjam</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Terlambat (Overdue)</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Total Transaksi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider rounded-r-xl">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-[#E7E0EC]">
                        @forelse ($users as $user)
                            <tr class="hover:bg-[#FDF7FF] transition-colors group">
                                {{-- Profil --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-[#E8DEF8] flex items-center justify-center text-[#6750A4] font-bold text-sm shadow-sm">
                                            {{ substr($user->Nama_Pengguna, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-[#1D1B20]">{{ $user->Nama_Pengguna }}</div>
                                            <div class="text-xs text-[#49454F]">{{ $user->Email_Pengguna }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status Akun --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusEnum = $user->Status_Akun_Pengguna;
                                        $badgeClass = $statusEnum == \App\Enums\StatusAkun::Dibatasi
                                            ? 'bg-[#FFF8E1] text-[#F57C00] border-[#FFE0B2]'
                                            : 'bg-[#E6F4EA] text-[#146C2E] border-[#C3EED4]';
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $badgeClass }}">
                                        {{ $statusEnum->name }}
                                    </span>
                                </td>

                                {{-- Buku Sedang Dipinjam --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-[#1D1B20] font-medium">
                                    {{ $user->active_loans_count }} / 3
                                </td>

                                {{-- Terlambat --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if ($user->overdue_loans_count > 0)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-[#F9DEDC] text-[#B3261E] border border-[#F2B8B5] text-xs font-bold rounded-full">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#B3261E] animate-pulse"></span>
                                            {{ $user->overdue_loans_count }} Buku Telat
                                        </span>
                                    @else
                                        <span class="text-xs text-[#49454F]/50 italic">-</span>
                                    @endif
                                </td>

                                {{-- Total Transaksi --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-[#49454F]">
                                    {{ $user->total_loans_count }}
                                </td>

                                {{-- Tanggal Join --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#49454F]">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-[#F3EDF7] rounded-full flex items-center justify-center mb-4 text-[#49454F]">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <p class="text-[#1D1B20] font-medium">Anggota tidak ditemukan</p>
                                        <p class="text-[#49454F] text-sm mt-1">Coba gunakan kata kunci pencarian lain.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Link Paginasi --}}
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-[#E7E0EC] bg-[#FDF7FF]">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
