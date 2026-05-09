<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Mercusuar Library') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .font-serif-display { font-family: 'Playfair Display', serif; }
            .font-sans-text { font-family: 'DM Sans', sans-serif; }
        </style>
    </head>
    <body class="font-sans-text text-gray-900 antialiased">
        
        {{-- 
           PERUBAHAN PENTING:
           Kita menghapus wrapper 'min-h-screen flex-col items-center pt-6...' bawaan Breeze.
           Sekarang layout ini hanya me-render $slot apa adanya, 
           sehingga desain Split Screen di halaman Login bisa tampil penuh.
        --}}
        
        <div class="min-h-screen bg-white">
            {{ $slot }}
        </div>

    </body>
</html>