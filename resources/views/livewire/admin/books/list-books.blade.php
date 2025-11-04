<div>
    {{-- Judul Halaman --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Manajemen Buku</h1>
        
        {{-- Tombol Tambah Buku Baru --}}
        <button wire:click="create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
            + Tambah Buku Baru
        </button>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Data Buku --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok (Tersedia/Total)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($books as $book)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ $book->gambar_cover ? Storage::url($book->gambar_cover) : 'https://placehold.co/48x64/e2e8f0/adb5bd?text=No+Img' }}" 
                                 alt="Cover" class="w-12 h-16 object-cover rounded shadow">
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $book->judul }}</div>
                            <div class="text-xs text-gray-500">ISBN: {{ $book->isbn ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ $book->penulis ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-green-600">{{ $book->stok_tersedia }}</span>
                            <span class="text-sm text-gray-500">/ {{ $book->stok_total }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="edit({{ $book->id }})" class="text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out">Edit</button>
                            <button wire:click="delete({{ $book->id }})" 
                                    wire:confirm="Anda yakin ingin menghapus buku '{{ $book->judul }}'? Tindakan ini tidak bisa dibatalkan."
                                    class="text-red-600 hover:text-red-900 ml-4 transition duration-150 ease-in-out">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data buku ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- Link Paginasi --}}
        <div class="p-4 border-t bg-gray-50">
            {{ $books->links() }}
        </div>
    </div>

    {{-- ====================================== --}}
    {{-- == MODAL UNTUK TAMBAH / EDIT BUKU == --}}
    {{-- ====================================== --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-data @click.self="$wire.closeModal()">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
                
                {{-- Form --}}
                <form wire:submit.prevent="save">
                    <!-- Modal Header -->
                    <div class="sticky top-0 bg-white px-6 py-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $bookId ? 'Edit Buku' : 'Tambah Buku Baru' }}
                        </h3>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Judul -->
                            <div>
                                <label for="judul" class="block text-sm font-medium text-gray-700">Judul Buku <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="judul" id="judul" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('judul') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Penulis -->
                            <div>
                                <label for="penulis" class="block text-sm font-medium text-gray-700">Penulis</label>
                                <input type="text" wire:model="penulis" id="penulis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('penulis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Penerbit -->
                            <div>
                                <label for="penerbit" class="block text-sm font-medium text-gray-700">Penerbit</label>
                                <input type="text" wire:model="penerbit" id="penerbit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('penerbit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <!-- ISBN -->
                            <div>
                                <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                                <input type="text" wire:model="isbn" id="isbn" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('isbn') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <!-- Stok Total -->
                            <div>
                                <label for="stok_total" class="block text-sm font-medium text-gray-700">Stok Total <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="stok_total" id="stok_total" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('stok_total') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea wire:model="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Gambar Cover -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gambar Cover</label>
                            <input type="file" wire:model="gambar_cover_baru" id="gambar_cover_baru" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('gambar_cover_baru') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                            <!-- Preview -->
                            <div class="mt-4">
                                @if ($gambar_cover_baru)
                                    <span class="block text-sm font-medium text-gray-500 mb-2">Preview Upload:</span>
                                    <img src="{{ $gambar_cover_baru->temporaryUrl() }}" class="w-32 h-48 object-cover rounded shadow">
                                @elseif ($gambar_cover_lama)
                                    <span class="block text-sm font-medium text-gray-500 mb-2">Gambar Saat Ini:</span>
                                    <img src="{{ Storage::url($gambar_cover_lama) }}" class="w-32 h-48 object-cover rounded shadow">
                                @else
                                    <span class="block text-sm font-medium text-gray-500 mb-2">Tidak ada gambar.</span>
                                    <div class="w-32 h-48 rounded bg-gray-100 flex items-center justify-center">
                                        <span class="text-gray-400">No Image</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="sticky bottom-0 bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                        <button type="button" wire:click="closeModal" class="py-2 px-4 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Batal
                        </button>
                        <button type="submit" class="py-2 px-4 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                wire:loading.attr="disabled" wire:target="save, gambar_cover_baru">
                            <span wire:loading.remove wire:target="save, gambar_cover_baru">
                                Simpan
                            </span>
                            <span wire:loading wire:target="save, gambar_cover_baru">
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
