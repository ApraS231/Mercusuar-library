<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Mercusuar Library') }}</title>

    @stack('seo')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/ScrollTrigger.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-sans-text { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #FDF7FF; }
        ::-webkit-scrollbar-thumb { background: #E7E0EC; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6750A4; }
    </style>
</head>
<body class="font-sans-text antialiased bg-[#FDF7FF] text-[#1D1B20] selection:bg-[#6750A4] selection:text-white overflow-x-hidden">

    <div class="min-h-screen flex flex-col">
        
        <nav class="fixed top-0 w-full z-50 transition-all duration-300 bg-[#FDF7FF]/80 backdrop-blur-xl border-b border-[#E7E0EC]"
             x-data="{ scrolled: false }"
             @scroll.window="scrolled = (window.pageYOffset > 20)">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-[#1D1B20] text-white rounded-full flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div class="leading-none">
                            <span class="block font-serif-display font-bold text-xl text-[#1D1B20]">Mercusuar</span>
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#6750A4]">Library</span>
                        </div>
                    </a>

                    <div class="flex items-center gap-6">
                        <div class="hidden md:flex items-center gap-6 text-sm font-medium text-[#49454F]">
                            <a href="{{ route('dashboard') }}" class="hover:text-[#6750A4] transition-colors {{ request()->routeIs('dashboard') ? 'text-[#6750A4] font-bold' : '' }}">Katalog</a>
                            <a href="{{ route('user.peminjaman') }}" class="hover:text-[#6750A4] transition-colors {{ request()->routeIs('user.peminjaman') ? 'text-[#6750A4] font-bold' : '' }}">Peminjaman Saya</a>
                            @if(auth()->user()->role === \App\Enums\Role::Admin)
                                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-[#1D1B20] text-white rounded-full hover:bg-[#6750A4] transition-colors shadow-md">Admin Panel</a>
                            @endif
                        </div>

                        <div class="relative ml-3" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3 p-1 pr-3 rounded-full border border-[#E7E0EC] bg-white hover:shadow-md transition-all">
                                <div class="w-8 h-8 rounded-full bg-[#E8DEF8] flex items-center justify-center text-[#6750A4] font-bold text-xs">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-xs font-bold text-[#1D1B20]">{{ Auth::user()->name }}</p>
                                </div>
                                <svg class="w-4 h-4 text-[#49454F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl py-2 border border-[#E7E0EC]" style="display: none;">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-[#49454F] hover:bg-[#F3EDF7] hover:text-[#6750A4]">Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-[#B3261E] hover:bg-[#FFF8F6]">Log Out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-grow pt-20">
            {{ $slot }}
        </main>

        <footer class="py-8 text-center border-t border-[#E7E0EC] mt-auto bg-white/50">
            <p class="font-serif-display text-lg text-[#1D1B20]">Mercusuar Library</p>
            <p class="text-xs text-[#49454F] mt-1">&copy; {{ date('Y') }} All rights reserved.</p>
        </footer>
    </div>
</body>
</html>