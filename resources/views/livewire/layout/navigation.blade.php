<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

{{-- 
    CATATAN PENTING:
    Karena kita sudah membuat Navigation Bar yang lengkap dan "Sticky Glass" 
    langsung di dalam file 'resources/views/layouts/app.blade.php', 
    komponen ini sekarang bersifat REDUNDANT (Duplikat).
    
    Jika Anda melihat dua navbar di aplikasi Anda, HAPUS semua HTML di bawah ini 
    dan biarkan hanya blok PHP di atas.
    
    Namun, jika Anda ingin menggunakan komponen ini sebagai pengganti navbar di layout, 
    berikut adalah strukturnya yang sudah disesuaikan:
--}}

<nav x-data="{ open: false }" class="bg-[#FDF7FF]/80 backdrop-blur-md border-b border-[#E7E0EC] fixed w-full z-50 top-0">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate class="group flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#1D1B20] rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div class="leading-none">
                            <h1 class="font-serif-display font-bold text-xl text-[#1D1B20] tracking-tight group-hover:text-[#6750A4] transition-colors">Mercusuar</h1>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-[#6750A4]">Library</span>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ms-10 sm:flex sm:items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard*')" wire:navigate>
                        {{ __('Katalog') }}
                    </x-nav-link>
                    
                    @if(auth()->user()->role === \App\Enums\Role::Admin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" wire:navigate>
                            {{ __('Admin Panel') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('user.peminjaman')" :active="request()->routeIs('user.peminjaman')" wire:navigate>
                            {{ __('Peminjaman Saya') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3 py-1.5 border border-[#E7E0EC] text-sm font-medium rounded-full text-[#1D1B20] bg-white hover:bg-[#F3EDF7] transition ease-in-out duration-150 focus:outline-none focus:ring-2 focus:ring-[#6750A4] focus:ring-offset-2">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name" class="pl-1"></div>

                            <div class="w-8 h-8 rounded-full bg-[#E8DEF8] flex items-center justify-center text-[#6750A4] font-bold text-xs">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-[#49454F] hover:text-[#1D1B20] hover:bg-[#F3EDF7] focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white/95 backdrop-blur-xl border-b border-[#E7E0EC]">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard*')" wire:navigate>
                {{ __('Katalog') }}
            </x-responsive-nav-link>
            
            @if(auth()->user()->role === \App\Enums\Role::Admin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" wire:navigate>
                    {{ __('Admin Panel') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('user.peminjaman')" :active="request()->routeIs('user.peminjaman')" wire:navigate>
                    {{ __('Peminjaman Saya') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-[#E7E0EC]">
            <div class="px-4">
                <div class="font-medium text-base text-[#1D1B20]" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-[#49454F]">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>