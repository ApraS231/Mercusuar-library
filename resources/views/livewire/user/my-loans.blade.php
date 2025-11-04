<div>
    {{-- Header Halaman --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Peminjaman Saya
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Notifikasi Error --}}
            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded-lg mb-6 shadow-lg">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="divide-y divide-gray-200">
                    @forelse ($peminjamans as $peminjaman)
                        <div class="flex flex-col md:flex-row items-start md:items-center p-6 space-y-4 md:space-y-0 md:space-x-6">
                            
                            {{-- Gambar Cover --}}
                            <img 
                                src="{{ $peminjaman->book->gambar_cover ? asset('storage/' . $peminjaman->book->gambar_cover) : 'https://placehold.co/400x600/e2e8f0/64748b?text=Perpus+Mercusuar' }}" 
                                alt="Cover {{ $peminjaman->book->judul }}"
                                class="w-24 h-36 object-cover rounded-md shadow-md flex-shrink-0">

                            {{-- Info Peminjaman --}}
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900">{{ $peminjaman->book->judul }}</h3>
                                <p class="text-sm text-gray-600">{{ $peminjaman->book->penulis }}</p>
                                
                                {{-- Status Badge --}}
                                <div class="mt-2">
                                    @php
                                        $status = $peminjaman->status;
                                        $bgColor = 'bg-gray-100';
                                        $textColor = 'text-gray-800';
                                        
                                        switch ($status) {
                                            case \App\Enums\StatusPeminjaman::Pending:
                                                $bgColor = 'bg-yellow-100'; $textColor = 'text-yellow-800'; break;
                                            case \App\Enums\StatusPeminjaman::Disetujui:
                                            case \App\Enums\StatusPeminjaman::Diproses:
                                                $bgColor = 'bg-blue-100'; $textColor = 'text-blue-800'; break;
                                            case \App\Enums\StatusPeminjaman::Diantar:
                                                $bgColor = 'bg-cyan-100'; $textColor = 'text-cyan-800'; break;
                                            case \App\Enums\StatusPeminjaman::Diterima:
                                                $bgColor = 'bg-green-100'; $textColor = 'text-green-800'; break;
                                            case \App\Enums\StatusPeminjaman::Ditolak:
                                                $bgColor = 'bg-red-100'; $textColor = 'text-red-800'; break;
                                            case \App\Enums\StatusPeminjaman::Overdue:
                                                $bgColor = 'bg-red-200'; $textColor = 'text-red-900 font-bold'; break;
                                            case \App\Enums\StatusPeminjaman::Dikembalikan:
                                                $bgColor = 'bg-gray-200'; $textColor = 'text-gray-600'; break;
                                        }
                                    @endphp
                                    <span class="inline-block {{ $bgColor }} {{ $textColor }} text-xs font-semibold px-3 py-1 rounded-full">
                                        Status: {{ $status->value }}
                                    </span>
                                </div>

                                {{-- Info Tanggal --}}
                                <div class="text-sm text-gray-500 mt-3 space-y-1">
                                    <p>Tgl. Booking: {{ $peminjaman->tgl_booking->format('d M Y, H:i') }}</p>
                                    @if($peminjaman->tgl_jatuh_tempo)
                                        <p class="font-medium {{ $peminjaman->status == \App\Enums\StatusPeminjaman::Overdue ? 'text-red-600' : '' }}">
                                            Jatuh Tempo: {{ Carbon\Carbon::parse($peminjaman->tgl_jatuh_tempo)->format('d M Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Aksi --}}
                            <div class="flex-shrink-0 w-full md:w-auto">
                                {{-- Tombol Konfirmasi Penerimaan --}}
                                @if($peminjaman->status === \App\Enums\StatusPeminjaman::Diantar)
                                    <button 
                                        wire:click="confirmReceipt({{ $peminjaman->id }})"
                                        wire:loading.attr="disabled"
                                        wire:confirm="Apakah Anda yakin sudah menerima buku ini?"
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow transition-colors duration-300">
                                        Konfirmasi Penerimaan
                                    </button>
                                @endif

                                {{-- Tombol Beri Review --}}
                                @if($peminjaman->status === \App\Enums\StatusPeminjaman::Dikembalikan)
                                    <a href="{{ route('book.detail', $peminjaman->book_id) }}" 
                                       class="block w-full text-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg shadow transition-colors duration-300">
                                        Beri Review
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500">
                            <p class="text-lg">Anda belum memiliki riwayat peminjaman.</p>
                            <a href="{{ route('dashboard') }}" class="text-blue-600 hover:underline font-medium mt-2 inline-block">
                                Mulai cari buku di katalog
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
