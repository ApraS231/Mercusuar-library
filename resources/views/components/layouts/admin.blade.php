<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Panel - Mercusuar Library' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif-display { font-family: 'Playfair Display', serif; }
        .font-sans-text { font-family: 'DM Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        
        /* Transisi halus untuk lebar sidebar */
        .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body class="font-sans-text antialiased bg-[#FDF7FF] text-[#1D1B20]" 
      x-data="{ 
          sidebarOpen: false, 
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
          toggleSidebar() {
              this.sidebarCollapsed = !this.sidebarCollapsed;
              localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
          }
      }">

    <div class="min-h-screen flex flex-col md:flex-row">
        
        <aside class="fixed inset-y-0 left-0 z-50 bg-white border-r border-[#E7E0EC] transform transition-all duration-300 ease-in-out md:translate-x-0 shadow-lg md:shadow-none"
               :class="[
                   sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                   sidebarCollapsed ? 'md:w-20' : 'md:w-72',
                   'w-72'
               ]">
            
            <div class="h-20 flex items-center border-b border-[#E7E0EC] transition-all duration-300"
                 :class="sidebarCollapsed ? 'justify-center px-0' : 'px-8 justify-between'">
                
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-10 h-10 bg-[#1D1B20] rounded-xl flex items-center justify-center text-white shrink-0 shadow-md">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div class="transition-opacity duration-200" :class="sidebarCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100 w-auto'">
                        <h1 class="font-serif-display font-bold text-lg text-[#1D1B20] leading-none whitespace-nowrap">Mercusuar</h1>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-[#6750A4] whitespace-nowrap">Admin Panel</p>
                    </div>
                </div>

                <button @click="toggleSidebar()" class="hidden md:flex p-1.5 rounded-lg text-[#49454F] hover:bg-[#F3EDF7] transition-colors" 
                        :class="sidebarCollapsed ? 'absolute -right-3 top-8 bg-white border border-[#E7E0EC] shadow-sm rounded-full w-6 h-6 flex items-center justify-center' : ''">
                    <svg class="w-4 h-4 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
            </div>

            <nav class="p-3 space-y-2 overflow-y-auto h-[calc(100vh-160px)] custom-scrollbar">
                
                <p class="px-4 mt-4 mb-2 text-[10px] font-bold text-[#49454F]/60 uppercase tracking-wider transition-all duration-300"
                   :class="sidebarCollapsed ? 'opacity-0 text-center' : 'opacity-100'">
                    Menu Utama
                </p>

                @php
                    $menuItems = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                        ['route' => 'admin.books.index', 'label' => 'Koleksi Buku', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['route' => 'admin.transactions.index', 'label' => 'Peminjaman', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                        ['route' => 'admin.users.index', 'label' => 'Anggota', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.965 5.965 0 0112 13a5.965 5.965 0 013 1.803'],
                    ];
                @endphp

                @foreach($menuItems as $item)
                    <a href="{{ route($item['route']) }}" wire:navigate 
                       class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 relative overflow-hidden
                              {{ request()->routeIs($item['route'].'*') 
                                 ? 'bg-[#E8DEF8] text-[#1D1B20] font-bold shadow-sm' 
                                 : 'text-[#49454F] hover:bg-[#F3EDF7] hover:text-[#1D1B20]' }}"
                       title="{{ $item['label'] }}"> <div class="w-6 h-6 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                        </div>
                        
                        <span class="whitespace-nowrap transition-all duration-300 origin-left"
                              :class="sidebarCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100 w-auto'">
                            {{ $item['label'] }}
                        </span>

                        @if(request()->routeIs($item['route'].'*'))
                            <div class="absolute right-3 w-1.5 h-1.5 rounded-full bg-[#6750A4]" 
                                 :class="sidebarCollapsed ? 'hidden' : 'block'"></div>
                        @endif
                    </a>
                @endforeach

            </nav>

            <div class="absolute bottom-0 w-full p-4 border-t border-[#E7E0EC] bg-white">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex w-full items-center gap-3 px-3 py-3 text-[#B3261E] hover:bg-[#FFF8F6] rounded-xl transition-colors font-medium group"
                            title="Keluar Aplikasi">
                        <div class="w-6 h-6 flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </div>
                        <span class="whitespace-nowrap transition-all duration-300"
                              :class="sidebarCollapsed ? 'opacity-0 w-0 hidden' : 'opacity-100 w-auto'">
                            Keluar
                        </span>
                    </button>
                </form>
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             x-transition.opacity
             class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 md:hidden" x-cloak></div>

        <main class="flex-1 min-w-0 overflow-hidden transition-all duration-300 ease-in-out bg-[#FDF7FF]"
              :class="sidebarCollapsed ? 'md:ml-20' : 'md:ml-72'">
            
            <header class="h-20 bg-[#FDF7FF]/80 backdrop-blur-xl border-b border-[#E7E0EC] sticky top-0 z-30 px-6 flex items-center justify-between">
                
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-[#49454F] hover:bg-[#F3EDF7] p-2 rounded-full transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div class="hidden md:flex flex-col">
                    <h2 class="text-xl font-serif-display font-bold text-[#1D1B20]">
                        @yield('title', 'Dashboard')
                    </h2>
                    <p class="text-xs text-[#49454F]">Administrator Area</p>
                </div>

                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-[#49454F] hover:bg-[#F3EDF7] rounded-full transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-[#B3261E] rounded-full border-2 border-[#FDF7FF]"></span>
                    </button>

                    <div class="h-8 w-px bg-[#E7E0EC]"></div>

                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-[#1D1B20] leading-tight">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-[#49454F] uppercase tracking-wider">Admin</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-[#E8DEF8] text-[#6750A4] flex items-center justify-center font-bold text-sm shadow-sm border-2 border-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 md:p-8 max-w-7xl mx-auto">
                {{ $slot }}
            </div>

        </main>
    </div>

</body>
</html>