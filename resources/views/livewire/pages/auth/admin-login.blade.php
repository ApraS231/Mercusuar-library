<?php

use App\Models\User;
use App\Enums\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Validation\ValidationException;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    /**
     * Handle Admin Login
     */
    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt([
            'Email_Pengguna' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = Auth::user();

        // Validasi khusus: Hanya Admin & Kepala Perpustakaan yang diizinkan lewat portal ini
        if ($user->Peran_Akses_Pengguna !== Role::Admin && $user->Peran_Akses_Pengguna !== Role::KepalaPerpus) {
            Auth::guard('web')->logout();
            Session::invalidate();
            Session::regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Akses ditolak: Akun Anda bukan akun Admin/Staf Perpustakaan. Silakan login via portal Anggota.',
            ]);
        }

        Session::regenerate();
        
        $defaultRoute = route('admin.dashboard', absolute: false);
        if ($user->Peran_Akses_Pengguna === Role::KepalaPerpus) {
            $defaultRoute = route('kepala-perpus.dashboard', absolute: false);
        }

        $this->redirectIntended(default: $defaultRoute, navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-[#121016] font-sans-text p-4 relative overflow-hidden">
    
    {{-- ASSETS --}}
    @assets
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-sans-text { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
    @endassets

    {{-- Background Decorative Glows --}}
    <div class="absolute top-1/4 -left-20 w-96 h-96 bg-[#6750A4]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-1/4 -right-20 w-96 h-96 bg-[#B3261E]/15 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative w-full max-w-md bg-[#1D1B20]/90 backdrop-blur-xl border border-white/10 rounded-[32px] p-8 sm:p-10 shadow-2xl">
        
        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-tr from-[#6750A4] to-[#D0BCFF] rounded-2xl flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <span class="inline-block px-3 py-1 bg-[#E8DEF8]/10 text-[#D0BCFF] text-[10px] font-bold uppercase tracking-widest rounded-full mb-2">
                Portal Staf & Administrator
            </span>
            <h1 class="font-serif-display font-bold text-3xl text-white">Login Admin</h1>
            <p class="text-xs text-[#CAC4D0] mt-1">Masuk dengan kredensial pengelola perpustakaan.</p>
        </div>

        {{-- Form --}}
        <form wire:submit="login" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-[#CAC4D0] uppercase tracking-wider mb-2">Email Administrator</label>
                <input wire:model="email" type="email" placeholder="admin@mercusuar.com" 
                    class="w-full bg-[#2B2831] border border-white/10 rounded-2xl px-5 py-3.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-[#D0BCFF] placeholder-[#CAC4D0]/40 transition-all">
                @error('email') <span class="text-[#F2B8B5] text-xs mt-1.5 block ml-1">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-[#CAC4D0] uppercase tracking-wider mb-2">Kata Sandi</label>
                <input wire:model="password" type="password" placeholder="••••••••" 
                    class="w-full bg-[#2B2831] border border-white/10 rounded-2xl px-5 py-3.5 text-sm text-white focus:outline-none focus:ring-2 focus:ring-[#D0BCFF] placeholder-[#CAC4D0]/40 transition-all">
                @error('password') <span class="text-[#F2B8B5] text-xs mt-1.5 block ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center cursor-pointer text-[#CAC4D0]">
                    <input wire:model="remember" type="checkbox" class="rounded border-white/20 bg-[#2B2831] text-[#D0BCFF] focus:ring-[#D0BCFF]">
                    <span class="ml-2">Ingat Sesi Saya</span>
                </label>
            </div>

            <button type="submit" 
                wire:loading.attr="disabled"
                class="w-full bg-gradient-to-r from-[#6750A4] to-[#4F378B] hover:from-[#5F4999] hover:to-[#381E72] text-white font-bold py-3.5 rounded-2xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2">
                <span wire:loading.remove wire:target="login">Masuk Portal Admin</span>
                <span wire:loading wire:target="login" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Memverifikasi...
                </span>
            </button>
        </form>

        {{-- Switch to Member Portal Link --}}
        <div class="mt-8 pt-6 border-t border-white/10 text-center">
            <p class="text-xs text-[#CAC4D0]">
                Bukan Administrator? 
                <a href="{{ route('login') }}" class="text-[#D0BCFF] font-bold hover:underline ml-1">
                    Login sebagai Anggota
                </a>
            </p>
        </div>

    </div>
</div>
