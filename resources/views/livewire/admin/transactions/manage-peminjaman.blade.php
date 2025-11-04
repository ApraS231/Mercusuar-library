<div>
    <h1 class="text-3xl font-bold mb-6">Manajemen Peminjaman</h1>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter Tabs --}}
    <div class="mb-4 border-b border-gray-200">
        <nav class="flex flex-wrap -mb-px space-x-4" aria-label="Tabs">
            @foreach ($statuses as $status)
                <button 
                    wire:click="setFilter('{{ $status }}')"
                    class="px-3 py-2 font-medium text-sm rounded-t-lg transition
                           {{ $filterStatus === $status
                                ? 'border-b-2 border-blue-500 text-blue-600'
                                : 'border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $status }}
                </button>
            @endforeach
        </nav>
    </div>

    {{-- Tabel Data Peminjaman --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($peminjamans as $peminjaman)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $peminjaman->book->judul ?? 'Buku Dihapus' }}</div>
                                <div class="text-xs text-gray-500">{{ $peminjaman->book->penulis ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $peminjaman->user->name ?? 'User Dihapus' }}</div>
                                <div class="text-xs text-gray-500">{{ $peminjaman->user->email ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $peminjaman->tgl_booking->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700 max-w-xs truncate">{{ $peminjaman->alamat_pengantaran }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusEnum = $peminjaman->status;
                                    $badgeColor = match($statusEnum) {
                                        \App\Enums\StatusPeminjaman::Pending => 'bg-yellow-100 text-yellow-800',
                                        \App\Enums\StatusPeminjaman::Disetujui, \App\Enums\StatusPeminjaman::Diproses => 'bg-blue-100 text-blue-800',
                                        \App\Enums\StatusPeminjaman::Diantar => 'bg-cyan-100 text-cyan-800',
                                        \App\Enums\StatusPeminjaman::Diterima => 'bg-green-100 text-green-800',
                                        \App\Enums\StatusPeminjaman::Overdue => 'bg-red-200 text-red-900 font-bold',
                                        \App\Enums\StatusPeminjaman::Ditolak, \App\Enums\StatusPeminjaman::Dikembalikan => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeColor }}">
                                    {{ $statusEnum->value }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                {{-- Logika Tombol Aksi Sesuai Status --}}
                                @if ($statusEnum === \App\Enums\StatusPeminjaman::Pending)
                                    <button wire:click="approve({{ $peminjaman->id }})" class="text-green-600 hover:text-green-900">Setujui</button>
                                    <button wire:click="reject({{ $peminjaman->id }})" class="text-red-600 hover:text-red-900">Tolak</button>
                                
                                @elseif ($statusEnum === \App\Enums\StatusPeminjaman::Disetujui)
                                    <button wire:click="markAsDelivered({{ $peminjaman->id }})" class="text-blue-600 hover:text-blue-900">Tandai Diantar</button>
                                
                                @elseif ($statusEnum === \App\Enums\StatusPeminjaman::Diantar)
                                    <span class="text-xs text-gray-500 italic">(Menunggu Konfirmasi User)</span>
                                
                                @elseif ($statusEnum === \App\Enums\StatusPeminjaman::Diterima || $statusEnum === \App\Enums\StatusPeminjaman::Overdue)
                                    <button wire:click="confirmReturn({{ $peminjaman->id }})" 
                                            wire:confirm="Konfirmasi buku ini telah dikembalikan? Stok akan dikembalikan ke sistem."
                                            class="text-indigo-600 hover:text-indigo-900">Konfirmasi Pengembalian</button>
                                
                                @else
                                    <span class="text-xs text-gray-500 italic">(Selesai)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data peminjaman dengan status "{{ $filterStatus }}".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Link Paginasi --}}
        <div class="p-4 border-t bg-gray-50">
            {{ $peminjamans->links() }}
        </div>
    </div>
</div>
