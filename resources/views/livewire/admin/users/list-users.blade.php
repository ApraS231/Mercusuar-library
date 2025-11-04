<div x-data="{}" x-init="$wire.on('refresh-page', () => { location.reload() })">
    <h1 class="text-3xl font-bold mb-6">Manajemen User</h1>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-6 shadow">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-6 shadow">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-6 flex justify-between items-center">
        <div class="w-1/3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama atau email..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>
        
        <div class="w-1/4">
            <select wire:model.live="filterRole" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option value="all">Semua Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
    </div>

    <!-- Tabel User -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Bergabung</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Akun</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $user->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select 
                                    wire:change="updateRole({{ $user->id }}, $event.target.value)"
                                    class="rounded-md border-gray-300 shadow-sm text-sm 
                                           {{ $user->role == \App\Enums\Role::Admin ? 'font-bold text-red-600' : 'text-gray-700' }}
                                           {{ $user->id == auth()->id() ? 'bg-gray-200 cursor-not-allowed' : '' }}"
                                    {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                    
                                    @foreach($roles as $role)
                                        <option value="{{ $role->value }}" @if($user->role == $role) selected @endif>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select 
                                    wire:change="updateStatus({{ $user->id }}, $event.target.value)"
                                    class="rounded-md border-gray-300 shadow-sm text-sm 
                                           {{ $user->status_akun == \App\Enums\StatusAkun::Dibatasi ? 'font-bold text-red-600' : 'text-green-600' }}
                                           {{ $user->id == auth()->id() ? 'bg-gray-200 cursor-not-allowed' : '' }}"
                                    {{ $user->id == auth()->id() ? 'disabled' : '' }}>

                                    @foreach($statuses as $status)
                                        <option value="{{ $status->value }}" @if($user->status_akun == $status) selected @endif>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data user ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Link Paginasi --}}
        <div class="p-4 border-t bg-gray-50">
            {{ $users->links() }}
        </div>
    </div>
</div>
