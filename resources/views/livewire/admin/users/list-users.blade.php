<div class="min-h-screen font-sans-text text-[#1D1B20] pb-12" 
     x-data="{}" 
     x-init="$wire.on('refresh-page', () => { location.reload() })">
    
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-serif-display text-3xl md:text-4xl text-[#1D1B20]">Manajemen Anggota</h1>
        <p class="text-sm text-[#49454F] mt-1">Kelola hak akses pengguna dan status akun anggota.</p>
    </div>

    {{-- Notifikasi --}}
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
        
        {{-- Filter Role --}}
        <div class="w-full md:w-auto min-w-[200px]">
            <select wire:model.live="filterRole" class="w-full bg-white border border-[#79747E] rounded-xl py-2.5 px-4 text-sm focus:ring-2 focus:ring-[#6750A4] focus:border-[#6750A4] transition-shadow cursor-pointer">
                <option value="all">Semua Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
    </div>

    {{-- Tabel User --}}
    <div class="bg-white border border-[#E7E0EC] rounded-[28px] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#E7E0EC]">
                <thead class="bg-[#F3EDF7]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Profil Anggota</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Role Access</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#1D1B20] uppercase tracking-wider">Status Akun</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#E7E0EC]">
                    @forelse ($users as $user)
                        <tr class="hover:bg-[#FDF7FF] transition-colors group">
                            
                            {{-- Kolom User --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar Initials --}}
                                    <div class="h-10 w-10 rounded-full bg-[#E8DEF8] flex items-center justify-center text-[#6750A4] font-bold text-sm shadow-sm">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-[#1D1B20]">{{ $user->name }}</div>
                                        <div class="text-xs text-[#49454F]">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Tanggal --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-[#49454F] font-medium">{{ $user->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-[#49454F]/60">{{ $user->created_at->diffForHumans() }}</div>
                            </td>

                            {{-- Kolom Role (Select) --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative w-32">
                                    <select 
                                        wire:change="updateRole({{ $user->id }}, $event.target.value)"
                                        class="appearance-none w-full text-xs font-bold py-1.5 pl-3 pr-8 rounded-lg border cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 transition-colors
                                        {{ $user->id == auth()->id() ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : '' }}
                                        {{ $user->role == \App\Enums\Role::Admin ? 'bg-[#F9DEDC] text-[#B3261E] border-[#F2B8B5] focus:ring-[#B3261E]' : 'bg-[#E3F2FD] text-[#1565C0] border-[#BBDEFB] focus:ring-[#1565C0]' }}"
                                        {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                        
                                        @foreach($roles as $role)
                                            <option value="{{ $role->value }}" @if($user->role == $role) selected @endif>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Status (Select) --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="relative w-36">
                                    <select 
                                        wire:change="updateStatus({{ $user->id }}, $event.target.value)"
                                        class="appearance-none w-full text-xs font-bold py-1.5 pl-3 pr-8 rounded-lg border cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 transition-colors
                                        {{ $user->id == auth()->id() ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : '' }}
                                        {{ $user->status_akun == \App\Enums\StatusAkun::Dibatasi ? 'bg-[#FFF8E1] text-[#F57C00] border-[#FFE0B2] focus:ring-[#F57C00]' : 'bg-[#E6F4EA] text-[#146C2E] border-[#C3EED4] focus:ring-[#146C2E]' }}"
                                        {{ $user->id == auth()->id() ? 'disabled' : '' }}>

                                        @foreach($statuses as $status)
                                            <option value="{{ $status->value }}" @if($user->status_akun == $status) selected @endif>
                                                {{ $status->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-[#F3EDF7] rounded-full flex items-center justify-center mb-4 text-[#49454F]">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <p class="text-[#1D1B20] font-medium">User tidak ditemukan</p>
                                    <p class="text-[#49454F] text-sm mt-1">Coba gunakan kata kunci pencarian lain.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Link Paginasi --}}
        <div class="px-6 py-4 border-t border-[#E7E0EC] bg-[#FDF7FF]">
            {{ $users->links() }}
        </div>
    </div>
</div>