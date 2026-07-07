<div class="min-h-screen font-sans-text text-[#1D1B20] pb-8">
    
    {{-- Header Halaman --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-serif-display text-3xl md:text-4xl text-[#1D1B20]">Kategori Buku</h1>
            <p class="text-sm text-[#49454F] mt-1">Kelola kategori atau genre buku yang tersedia di perpustakaan.</p>
        </div>
        
        {{-- Tombol Tambah --}}
        <button wire:click="create" 
            class="inline-flex items-center gap-2 bg-[#6750A4] hover:bg-[#6750A4]/90 text-white px-6 py-3 rounded-full font-medium shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Kategori</span>
        </button>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-[#E6F4EA] border border-[#C3EED4] text-[#146C2E] px-4 py-3 rounded-2xl mb-6 flex items-center gap-3 shadow-sm" role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-[#F9DEDC] border border-[#F2B8B5] text-[#B3261E] px-4 py-3 rounded-2xl mb-6 flex items-center gap-3 shadow-sm" role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Filter & Search --}}
    <div class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-80">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kategori..." 
                   class="w-full rounded-xl border-[#E7E0EC] focus:border-[#6750A4] focus:ring-2 focus:ring-[#6750A4]/20 py-2.5 pl-10 pr-4 text-sm text-[#1D1B20] bg-white placeholder-[#49454F]/50 transition-all border">
            <div class="absolute left-3.5 top-3 text-[#49454F]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Tabel Data Kategori (Card Style) --}}
    <div class="bg-white border border-[#E7E0EC] rounded-[28px] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-[#F3EDF7] border-b border-[#E7E0EC]">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Jumlah Buku Terelasi</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E7E0EC]">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-[#FDF7FF] transition-colors group">
                            <td class="px-6 py-4 align-middle">
                                <div class="font-bold text-[#1D1B20] text-base">{{ $category->Nama_kategori }}</div>
                            </td>
                            <td class="px-6 py-4 align-middle whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#E8DEF8] text-[#1D1B20]">
                                    {{ $category->books_count }} buku
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right align-middle">
                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $category->Id_kategori }})" class="p-2 text-[#6750A4] hover:bg-[#E8DEF8] rounded-full transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="delete({{ $category->Id_kategori }})" 
                                            wire:confirm="Hapus kategori '{{ $category->Nama_kategori }}'? Semua buku yang terkait dengan kategori ini akan diset menjadi tanpa kategori."
                                            class="p-2 text-[#B3261E] hover:bg-[#F9DEDC] rounded-full transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-[#F3EDF7] rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-[#49454F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <p class="text-[#1D1B20] font-medium text-lg">Belum ada kategori</p>
                                    <p class="text-[#49454F] text-sm mt-1">Mulai dengan menambahkan kategori baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Link Paginasi --}}
        <div class="px-6 py-4 border-t border-[#E7E0EC] bg-[#FDF7FF]">
            {{ $categories->links() }}
        </div>
    </div>

    {{-- ================= MODAL ================= --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" x-data @keydown.escape.window="$wire.closeModal()">
            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" @click="$wire.closeModal()"></div>

            {{-- Modal Card --}}
            <div class="relative bg-white rounded-[28px] shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden animate-fade-in-up">
                
                <div class="px-8 py-6 border-b border-[#E7E0EC] flex justify-between items-center bg-white sticky top-0 z-10">
                    <div>
                        <h3 class="text-2xl font-serif-display text-[#1D1B20]">
                            {{ $categoryId ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
                        </h3>
                        <p class="text-sm text-[#49454F]">Tentukan nama kategori buku.</p>
                    </div>
                    <button wire:click="closeModal" class="p-2 hover:bg-[#F3EDF7] rounded-full transition-colors text-[#49454F]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto custom-scrollbar">
                    <form wire:submit.prevent="save" class="space-y-6">
                        <div>
                            <label class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2">Nama Kategori *</label>
                            <input type="text" wire:model="nama_kategori" 
                                   class="w-full rounded-xl border-[#79747E] focus:border-[#6750A4] focus:ring-2 focus:ring-[#6750A4]/20 text-[#1D1B20] placeholder-[#49454F]/50 transition-all py-3 px-4 border" 
                                   placeholder="Contoh: Fiksi, Sains, Sejarah">
                            @error('nama_kategori') <p class="text-[#B3261E] text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                        </div>
                    </form>
                </div>

                <div class="px-8 py-4 border-t border-[#E7E0EC] bg-[#FDF7FF] flex justify-end gap-4 sticky bottom-0 z-10">
                    <button wire:click="closeModal" class="px-6 py-2.5 rounded-full border border-[#79747E] text-[#6750A4] font-bold text-sm hover:bg-[#F3EDF7] transition-colors border">
                        Batal
                    </button>
                    <button wire:click="save" 
                            wire:loading.attr="disabled"
                            class="px-6 py-2.5 rounded-full bg-[#6750A4] text-white font-bold text-sm hover:bg-[#6750A4]/90 shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <span wire:loading.remove wire:target="save">Simpan Data</span>
                        <span wire:loading wire:target="save">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }
        
        /* Custom Scrollbar untuk Modal */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E7E0EC; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #CAC4D0; }
    </style>
</div>
