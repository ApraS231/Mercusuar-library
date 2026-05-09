<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex font-sans-text bg-[#FDF7FF]">
    
    {{-- LOAD ASSETS --}}
    @assets
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    <style>
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-sans-text { font-family: 'DM Sans', sans-serif; }
        /* Scrollbar custom untuk form panjang */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E7E0EC; border-radius: 10px; }
    </style>
    @endassets

    <div class="flex-1 flex flex-col md:flex-row">
        
        {{-- BAGIAN KIRI: Hero Image (Sticky agar tetap diam saat form discroll) --}}
        <div class="hidden md:block md:w-1/2 lg:w-7/12 relative overflow-hidden bg-[#1D1B20]">
            {{-- Background Image --}}
            <div class="absolute inset-0 bg-cover bg-center scale-105 animate-slow-zoom" 
                 style="background-image: url('{{ asset('images/login-hero.jpg') }}'); opacity: 0.6;">
            </div>
            
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-[#6750A4] via-[#1D1B20]/50 to-transparent mix-blend-multiply"></div>
            
            {{-- Text Content with Glass Effect --}}
            <div class="absolute bottom-0 left-0 w-full p-12 lg:p-16 z-10">
                <div class="bg-white/10 backdrop-blur-md border border-white/10 p-8 rounded-[32px] gsap-fade-up opacity-0">
                    <h2 class="font-serif-display text-3xl lg:text-5xl font-bold text-white leading-tight mb-4">
                        Bergabunglah dengan<br>Komunitas Kami.
                    </h2>
                    <p class="text-white/80 font-sans-text text-lg leading-relaxed max-w-md">
                        Dapatkan akses tak terbatas ke ribuan koleksi buku dan literatur berkualitas.
                    </p>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: Form Register (Scrollable) --}}
        <div class="flex-1 md:w-1/2 lg:w-5/12 flex flex-col justify-center p-6 sm:p-12 bg-white relative h-screen overflow-y-auto custom-scroll">
            
            <div class="w-full max-w-md mx-auto space-y-8 gsap-form-in opacity-0 translate-x-10 my-auto">
                
                {{-- Header --}}
                <div class="text-center md:text-left">
                    <a href="/" wire:navigate class="inline-flex items-center gap-2 mb-6 group">
                        <div class="w-10 h-10 bg-[#6750A4] rounded-xl flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <span class="font-serif-display font-bold text-xl text-[#1D1B20]">Mercusuar</span>
                    </a>
                    <h2 class="font-serif-display text-4xl font-bold text-[#1D1B20] tracking-tight">
                        Buat Akun Baru
                    </h2>
                    <p class="mt-2 text-sm text-[#49454F]">
                        Lengkapi data diri Anda untuk memulai.
                    </p>
                </div>

                <form wire:submit="register" class="space-y-5">
                    
                    <div>
                        <label for="name" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#49454F] group-focus-within:text-[#6750A4] transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input wire:model="name" id="name" type="text" required autofocus autocomplete="name"
                                class="w-full pl-11 pr-4 py-3.5 bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all shadow-sm placeholder-[#49454F]/40"
                                placeholder="Masukkan nama lengkap">
                        </div>
                        @error('name') <p class="text-[#B3261E] text-xs mt-2 ml-2 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">Alamat Email</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#49454F] group-focus-within:text-[#6750A4] transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input wire:model="email" id="email" type="email" required autocomplete="username"
                                class="w-full pl-11 pr-4 py-3.5 bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all shadow-sm placeholder-[#49454F]/40"
                                placeholder="nama@email.com">
                        </div>
                        @error('email') <p class="text-[#B3261E] text-xs mt-2 ml-2 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">Password</label>
                        <div class="relative group" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#49454F] group-focus-within:text-[#6750A4] transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input wire:model="password" id="password" :type="show ? 'text' : 'password'" required autocomplete="new-password"
                                class="w-full pl-11 pr-12 py-3.5 bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all shadow-sm placeholder-[#49454F]/40"
                                placeholder="Minimal 8 karakter">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#49454F] hover:text-[#6750A4] transition-colors focus:outline-none">
                                <svg x-show="!show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="show" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.059 10.059 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.059 10.059 0 01-1.563 3.029m-5.858-.908a3 3 0 11-4.243-4.243m0 0L3 3m18 18l-3.59-3.59" /></svg>
                            </button>
                        </div>
                        @error('password') <p class="text-[#B3261E] text-xs mt-2 ml-2 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-[#49454F] uppercase tracking-wider mb-2 ml-1">Ulangi Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#49454F] group-focus-within:text-[#6750A4] transition-colors">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <input wire:model="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password"
                                class="w-full pl-11 pr-4 py-3.5 bg-[#FDF7FF] border border-[#E7E0EC] text-[#1D1B20] rounded-full focus:ring-2 focus:ring-[#6750A4] focus:border-transparent transition-all shadow-sm placeholder-[#49454F]/40"
                                placeholder="Ketik ulang password">
                        </div>
                        @error('password_confirmation') <p class="text-[#B3261E] text-xs mt-2 ml-2 font-medium">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tombol Register --}}
                    <div class="pt-4">
                        <button type="submit" 
                                class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-full shadow-md text-sm font-bold text-white bg-[#6750A4] hover:bg-[#6750A4]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6750A4] transition-all transform hover:-translate-y-0.5">
                            <span wire:loading.remove wire:target="register">Daftar Sekarang</span>
                            <span wire:loading wire:target="register" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>

                {{-- Footer Login Link --}}
                <div class="mt-8 pt-6 border-t border-[#E7E0EC] text-center">
                    <p class="text-sm text-[#49454F]">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" wire:navigate class="text-[#6750A4] font-bold hover:text-[#6750A4]/80 hover:underline ml-1 transition-all">
                            Masuk di sini
                        </a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    {{-- GSAP Animations --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap !== 'undefined') {
                // Animasi Text Hero (Fade Up)
                gsap.to('.gsap-fade-up', { 
                    opacity: 1, 
                    y: 0, 
                    duration: 1, 
                    delay: 0.2, 
                    ease: 'power3.out' 
                });

                // Animasi Form Register (Slide from Right)
                gsap.to('.gsap-form-in', { 
                    opacity: 1, 
                    x: 0, 
                    duration: 0.8, 
                    delay: 0.2, 
                    ease: 'power3.out' 
                });
            }
        });
    </script>
    <style>
        @keyframes slowZoom {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        .animate-slow-zoom { animation: slowZoom 20s ease-in-out infinite alternate; }
    </style>
</div>