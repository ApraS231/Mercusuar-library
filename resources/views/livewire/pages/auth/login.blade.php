<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Illuminate\Validation\ValidationException;

new #[Layout('layouts.guest')] class extends Component
{
    // --- LOGIN PROPERTIES ---
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    // --- REGISTER PROPERTIES ---
    public string $reg_name = '';
    public string $reg_email = '';
    public string $reg_password = '';
    public string $reg_password_confirmation = '';

    /**
     * Handle Login
     */
    public function login(): void
    {
        $credentials = $this->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Handle Register
     */
    public function register(): void
    {
        $validated = $this->validate([
            'reg_name' => ['required', 'string', 'max:255'],
            'reg_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class.',email'],
            'reg_password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['reg_name'],
            'email' => $validated['reg_email'],
            'password' => Hash::make($validated['reg_password']),
        ]);

        event(new Registered($user));
        Auth::login($user);
        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-[#FDF7FF] font-sans-text p-4"
     x-data="{ isSignUp: false }">

    {{-- ASSETS --}}
    @assets
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    <style>
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-sans-text { font-family: 'DM Sans', sans-serif; }
        
        /* Container Utama */
        .container-auth {
            position: relative;
            width: 1000px;
            max-width: 100%;
            min-height: 600px;
            background: #fff;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(103, 80, 164, 0.2);
            overflow: hidden;
        }

        /* Form Containers */
        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        /* Overlay Container (The Glass) */
        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .overlay {
            background: url('{{ asset('images/login-hero.jpg') }}') no-repeat center center / cover;
            color: #FFFFFF;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        /* Glassmorphism Effect on Overlay */
        .glass-panel {
            position: absolute;
            inset: 0;
            background: rgba(103, 80, 164, 0.35); /* Ungu Transparan */
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left { transform: translateX(-20%); }
        .overlay-right { right: 0; transform: translateX(0); }

        /* Animation States when Active (Register Mode) */
        .container-auth.right-panel-active .sign-in-container { transform: translateX(100%); }
        .container-auth.right-panel-active .sign-up-container { transform: translateX(100%); opacity: 1; z-index: 5; }
        .container-auth.right-panel-active .overlay-container { transform: translateX(-100%); }
        .container-auth.right-panel-active .overlay { transform: translateX(50%); }
        .container-auth.right-panel-active .overlay-left { transform: translateX(0); }
        .container-auth.right-panel-active .overlay-right { transform: translateX(20%); }
        
        /* Custom Input Scrollbar */
        .custom-scroll::-webkit-scrollbar { width: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #E7E0EC; border-radius: 10px; }
    </style>
    @endassets

    <div class="container-auth" :class="{ 'right-panel-active': isSignUp }">
        
        {{-- 1. FORM REGISTER (Hidden by default via CSS) --}}
        <div class="form-container sign-up-container bg-white flex items-center justify-center">
            <div class="w-full px-10 py-8 h-full overflow-y-auto custom-scroll flex flex-col justify-center">
                <h1 class="font-serif-display font-bold text-3xl text-[#1D1B20] mb-2 text-center">Buat Akun Baru</h1>
                <p class="text-sm text-[#49454F] mb-6 text-center">Gabung komunitas literasi Mercusuar.</p>
                
                <form wire:submit="register" class="space-y-4">
                    {{-- Name --}}
                    <div>
                        <input wire:model="reg_name" type="text" placeholder="Nama Lengkap" 
                            class="w-full bg-[#F3EDF7] border-none rounded-full px-5 py-3 text-sm focus:ring-2 focus:ring-[#6750A4] placeholder-[#49454F]/60">
                        @error('reg_name') <span class="text-[#B3261E] text-xs ml-3">{{ $message }}</span> @enderror
                    </div>
                    
                    {{-- Email --}}
                    <div>
                        <input wire:model="reg_email" type="email" placeholder="Email Anda" 
                            class="w-full bg-[#F3EDF7] border-none rounded-full px-5 py-3 text-sm focus:ring-2 focus:ring-[#6750A4] placeholder-[#49454F]/60">
                        @error('reg_email') <span class="text-[#B3261E] text-xs ml-3">{{ $message }}</span> @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <input wire:model="reg_password" type="password" placeholder="Password" 
                            class="w-full bg-[#F3EDF7] border-none rounded-full px-5 py-3 text-sm focus:ring-2 focus:ring-[#6750A4] placeholder-[#49454F]/60">
                        @error('reg_password') <span class="text-[#B3261E] text-xs ml-3">{{ $message }}</span> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <input wire:model="reg_password_confirmation" type="password" placeholder="Ulangi Password" 
                            class="w-full bg-[#F3EDF7] border-none rounded-full px-5 py-3 text-sm focus:ring-2 focus:ring-[#6750A4] placeholder-[#49454F]/60">
                    </div>

                    <button type="submit" 
                        class="w-full bg-[#6750A4] text-white font-bold py-3 rounded-full hover:bg-[#5F4999] hover:shadow-lg transition-all transform hover:-translate-y-0.5 mt-2">
                        Daftar Sekarang
                    </button>
                </form>
            </div>
        </div>

        {{-- 2. FORM LOGIN (Visible by default) --}}
        <div class="form-container sign-in-container bg-white flex items-center justify-center">
            <div class="w-full px-10 flex flex-col justify-center">
                <div class="text-center mb-8">
                    <div class="w-12 h-12 bg-[#1D1B20] rounded-xl flex items-center justify-center text-white mx-auto mb-4 shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h1 class="font-serif-display font-bold text-3xl text-[#1D1B20]">Selamat Datang</h1>
                    <p class="text-sm text-[#49454F] mt-1">Masuk untuk melanjutkan membaca.</p>
                </div>

                <form wire:submit="login" class="space-y-5">
                    <div>
                        <input wire:model="email" type="email" placeholder="Email" 
                            class="w-full bg-[#F3EDF7] border-none rounded-full px-5 py-3.5 text-sm focus:ring-2 focus:ring-[#6750A4] placeholder-[#49454F]/60 transition-all">
                        @error('email') <span class="text-[#B3261E] text-xs ml-3 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div>
                        <input wire:model="password" type="password" placeholder="Password" 
                            class="w-full bg-[#F3EDF7] border-none rounded-full px-5 py-3.5 text-sm focus:ring-2 focus:ring-[#6750A4] placeholder-[#49454F]/60 transition-all">
                        @error('password') <span class="text-[#B3261E] text-xs ml-3 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-between text-xs px-2">
                        <label class="flex items-center cursor-pointer">
                            <input wire:model="remember" type="checkbox" class="rounded border-gray-300 text-[#6750A4] focus:ring-[#6750A4]">
                            <span class="ml-2 text-[#49454F]">Ingat Saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[#6750A4] font-bold hover:underline">Lupa Password?</a>
                        @endif
                    </div>

                    <button type="submit" 
                        class="w-full bg-[#6750A4] text-white font-bold py-3.5 rounded-full hover:bg-[#5F4999] hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        Masuk
                    </button>
                </form>
            </div>
        </div>

        {{-- 3. OVERLAY CONTAINER (Glassmorphism Slider) --}}
        <div class="overlay-container">
            <div class="overlay">
                <div class="glass-panel"></div> {{-- Lapisan Kaca --}}
                
                {{-- Panel Kiri (Muncul saat Register aktif) --}}
                <div class="overlay-panel overlay-left text-white z-10">
                    <h1 class="font-serif-display font-bold text-4xl mb-4">Sudah Punya Akun?</h1>
                    <p class="mb-8 text-white/90 font-light leading-relaxed">
                        Kembali masuk untuk mengakses koleksi buku Anda dan melanjutkan riwayat peminjaman.
                    </p>
                    <button @click="isSignUp = false" 
                        class="bg-transparent border-2 border-white text-white rounded-full px-10 py-3 font-bold uppercase tracking-wider hover:bg-white hover:text-[#6750A4] transition-all">
                        Login Di Sini
                    </button>
                </div>

                {{-- Panel Kanan (Muncul saat Login aktif) --}}
                <div class="overlay-panel overlay-right text-white z-10">
                    <h1 class="font-serif-display font-bold text-4xl mb-4">Halo, Pembaca!</h1>
                    <p class="mb-8 text-white/90 font-light leading-relaxed">
                        Belum terdaftar? Masukkan data diri Anda dan mulailah petualangan literasi bersama kami.
                    </p>
                    <button @click="isSignUp = true" 
                        class="bg-white text-[#6750A4] border-2 border-white rounded-full px-10 py-3 font-bold uppercase tracking-wider hover:bg-transparent hover:text-white transition-all shadow-lg">
                        Daftar Sekarang
                    </button>
                </div>
            </div>
        </div>

    </div>

    {{-- GSAP Enhancements --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap !== 'undefined') {
                // Animasi Awal Container (Zoom In)
                gsap.from('.container-auth', { 
                    duration: 1, 
                    scale: 0.9, 
                    opacity: 0, 
                    ease: 'power3.out' 
                });
            }
        });
    </script>
</div>