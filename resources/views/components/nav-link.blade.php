@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-full bg-[#E8DEF8] text-sm font-bold text-[#1D1B20] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-4 py-2 rounded-full bg-transparent text-sm font-medium text-[#49454F] hover:text-[#1D1B20] hover:bg-[#F3EDF7] transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>