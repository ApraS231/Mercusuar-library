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
        
        <livewire:layout.navigation />

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