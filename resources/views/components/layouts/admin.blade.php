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
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white p-6 shadow-lg flex flex-col">
            <h1 class="text-2xl font-bold mb-8">Perpus Mercusuar</h1>
            <nav class="flex-1 space-y-2">
                
                {{-- Link Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center space-x-2 px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('admin.dashboard')) bg-gray-900 font-bold @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4h.01M12 12h.01M15 12h.01M9 12h.01M12 15h.01M15 15h.01M9 15h.01"></path></svg>
                    <span>Dashboard</span>
                </a>
                
                {{-- Link Manajemen Buku --}}
                <a href="{{ route('admin.books.index') }}" 
                   class="flex items-center space-x-2 px-4 py-2 rounded hover:bg-gray-700 @if(request()->routeIs('admin.books.index')) bg-gray-900 font-bold @endif">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>Manajemen Buku</span>
                </a>
                
                {{-- Tambahkan Link Manajemen User & Peminjaman di sini nanti --}}
                {{-- <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Manajemen Peminjaman</a> --}}
                {{-- <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Manajemen User</a> --}}

            </nav>

            {{-- Tombol Logout --}}
            <div class="mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-2 w-full text-left px-4 py-2 rounded hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Logout</span>
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
