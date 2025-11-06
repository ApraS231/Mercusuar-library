<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Perpustakaan Mercusuar</title>
    <!-- Memuat aset Vite (Tailwind CSS, JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Memuat style Livewire -->
    @livewireStyles
</head>
<body class="bg-purple-bg font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white p-6 shadow-2xl flex flex-col">
            <div class="flex items-center space-x-3 mb-10">
                <svg class="w-10 h-10 text-purple-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <h1 class="text-2xl font-bold">Perpus Mercusuar</h1>
            </div>
            <nav class="flex-1 space-y-2">
                
                {{-- Link Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg transition-colors @if(request()->routeIs('admin.dashboard')) bg-purple-primary text-white shadow-lg @else text-gray-300 hover:bg-purple-hover hover:text-white @endif">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="font-semibold">Dashboard</span>
                </a>
                
                {{-- Link Manajemen Buku --}}
                <a href="{{ route('admin.books.index') }}" 
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg transition-colors @if(request()->routeIs('admin.books.index')) bg-purple-primary text-white shadow-lg @else text-gray-300 hover:bg-purple-hover hover:text-white @endif">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="font-semibold">Manajemen Buku</span>
                </a>
                
                {{-- Link Manajemen Peminjaman --}}
                <a href="{{ route('admin.transactions.index') }}"
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg transition-colors @if(request()->routeIs('admin.transactions.index')) bg-purple-primary text-white shadow-lg @else text-gray-300 hover:bg-purple-hover hover:text-white @endif">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h4M8 7a2 2 0 012-2h4a2 2 0 012 2v8a2 2 0 01-2 2H8M2 8h20M2 16h20"></path></svg>
                    <span class="font-semibold">Peminjaman</span>
                </a>

                {{-- Link Manajemen User --}}
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center space-x-3 px-4 py-2 rounded-lg transition-colors @if(request()->routeIs('admin.users.index')) bg-purple-primary text-white shadow-lg @else text-gray-300 hover:bg-purple-hover hover:text-white @endif">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m0 0A5.965 5.965 0 0112 13a5.965 5.965 0 013 1.803"></path></svg>
                    <span class="font-semibold">Manajemen User</span>
                </a>

            </nav>

            {{-- Tombol Logout --}}
            <div class="mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 w-full text-left px-4 py-2 rounded-lg text-gray-300 hover:bg-red-600 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="font-semibold">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Konten Utama -->
        <main class="flex-1 p-8 overflow-y-auto">
            <!-- Menampilkan pesan error (jika ada, dari middleware) -->
            @if (session('error'))
                <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Ini adalah tempat komponen Livewire akan dimuat -->
            {{ $slot }}
        </main>
    </div>

    <!-- Memuat script Livewire -->
    @livewireScripts
</body>
</html>
