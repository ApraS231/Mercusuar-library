<div class="min-h-screen font-sans-text text-[#1D1B20] pb-12">
    
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-serif-display text-3xl md:text-4xl text-[#1D1B20]">Manajemen Peminjaman</h1>
        <p class="text-sm text-[#49454F] mt-1">Pantau dan kelola sirkulasi buku perpustakaan.</p>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-[#E6F4EA] border border-[#C3EED4] text-[#146C2E] px-4 py-3 rounded-2xl mb-6 flex items-center gap-3 shadow-sm animate-fade-in-up" role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Filter Chips (Scrollable) --}}
    <div class="mb-6 overflow-x-auto pb-2 no-scrollbar">
        <div class="flex space-x-3 min-w-max">
            @foreach ($statuses as $status)
                <button 
                    wire:click="setFilter('{{ $status }}')"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border
                           {{ $filterStatus === $status
                                ? 'bg-[#6750A4] text-white border-[#6750A4] shadow-md'
                                : 'bg-white text-[#49454F] border-[#79747E] hover:bg-[#F3EDF7] hover:border-[#6750A4]' }}">
                    {{ $status }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Tabel Data Peminjaman --}}
    <div class="bg-white border border-[#E7E0EC] rounded-[28px] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E7E0EC]">
                <thead class="bg-[#F3EDF7]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Detail Buku</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#E7E0EC]">
                    @forelse ($peminjamans as $peminjaman)
                        <tr class="hover:bg-[#FDF7FF] transition-colors group">
                            
                            {{-- Kolom Buku --}}
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-lg bg-[#F3EDF7] flex items-center justify-center text-[#6750A4] flex-shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-[#1D1B20]">{{ $peminjaman->book->judul ?? 'Buku Dihapus' }}</div>
                                        <div class="text-xs text-[#49454F]">{{ $peminjaman->book->penulis ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Peminjam --}}
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                <div class="text-sm font-medium text-[#1D1B20]">{{ $peminjaman->user->name ?? 'User Dihapus' }}</div>
                                <div class="text-xs text-[#49454F]">{{ $peminjaman->user->email ?? '-' }}</div>
                                <div class="text-[10px] text-[#49454F]/70 mt-1 max-w-[150px] truncate" title="{{ $peminjaman->alamat_pengantaran }}">
                                    {{ $peminjaman->alamat_pengantaran }}
                                </div>
                            </td>

                            {{-- Kolom Tanggal --}}
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                <div class="text-sm text-[#1D1B20]">Booking: {{ $peminjaman->tgl_booking->format('d M Y') }}</div>
                                @if($peminjaman->tgl_jatuh_tempo)
                                    <div class="text-xs text-[#B3261E] font-medium mt-1">
                                        Due: {{ $peminjaman->tgl_jatuh_tempo->format('d M Y') }}
                                    </div>
                                @endif
                            </td>

                            {{-- Kolom Status (Badge) --}}
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                @php
                                    $statusEnum = $peminjaman->status;
                                    $badgeClass = match($statusEnum) {
                                        \App\Enums\StatusPeminjaman::Pending => 'bg-[#FFF8E1] text-[#F57C00] border-[#FFE0B2]',
                                        \App\Enums\StatusPeminjaman::Disetujui => 'bg-[#E3F2FD] text-[#1565C0] border-[#BBDEFB]',
                                        \App\Enums\StatusPeminjaman::Diantar => 'bg-[#E0F7FA] text-[#006064] border-[#B2EBF2]',
                                        \App\Enums\StatusPeminjaman::Diterima => 'bg-[#E6F4EA] text-[#137333] border-[#C3EED4]',
                                        \App\Enums\StatusPeminjaman::Overdue => 'bg-[#F9DEDC] text-[#B3261E] border-[#F2B8B5] font-bold',
                                        default => 'bg-[#F5F5F5] text-[#616161] border-[#E0E0E0]',
                                    };
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $badgeClass }}">
                                    {{ $statusEnum->value }}
                                </span>
                            </td>

                            {{-- Kolom Aksi --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium align-top">
                                <div class="flex justify-end gap-2">
                                    
                                    @if ($statusEnum === \App\Enums\StatusPeminjaman::Pending)
                                        <button wire:click="approve({{ $peminjaman->id }})" class="p-2 bg-[#E6F4EA] text-[#137333] rounded-full hover:bg-[#C3EED4] transition-colors" title="Setujui">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                        <button wire:click="reject({{ $peminjaman->id }})" class="p-2 bg-[#F9DEDC] text-[#B3261E] rounded-full hover:bg-[#F2B8B5] transition-colors" title="Tolak">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    
                                    @elseif ($statusEnum === \App\Enums\StatusPeminjaman::Disetujui)
                                        <button wire:click="markAsDelivered({{ $peminjaman->id }})" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#E3F2FD] text-[#1565C0] rounded-full hover:bg-[#BBDEFB] transition-colors text-xs font-bold" title="Tandai Diantar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                            Kirim
                                        </button>
                                    
                                    @elseif ($statusEnum === \App\Enums\StatusPeminjaman::Diantar)
                                        <span class="text-xs text-[#49454F] italic flex items-center gap-1">
                                            <svg class="w-4 h-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Menunggu User
                                        </span>
                                    
                                    @elseif ($statusEnum === \App\Enums\StatusPeminjaman::Diterima || $statusEnum === \App\Enums\StatusPeminjaman::Overdue)
                                        <button wire:click="confirmReturn({{ $peminjaman->id }})" 
                                                wire:confirm="Buku dikembalikan? Stok akan ditambah."
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#6750A4] text-white rounded-full hover:bg-[#5F4999] transition-colors text-xs font-bold shadow-md hover:shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Konfirmasi Kembali
                                        </button>
                                    
                                    @else
                                        <span class="text-xs text-[#49454F]/50 italic">Selesai</span>
                                    @endif
                                </div>
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
                                    <p class="text-[#49454F] text-sm mt-1">Belum ada peminjaman dengan status "{{ $filterStatus }}".</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Link Paginasi --}}
        <div class="p-6 border-t border-[#E7E0EC] bg-[#FDF7FF]">
            {{ $peminjamans->links() }}
        </div>
    </div>
</div>