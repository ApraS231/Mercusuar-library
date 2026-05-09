<button {{ $attributes->merge(['type' => 'button', 'class' => '
    inline-flex items-center justify-center 
    px-6 py-2.5 
    bg-transparent 
    border border-[#79747E] 
    rounded-full 
    font-medium text-sm text-[#6750A4] 
    hover:bg-[#6750A4]/10 
    focus:outline-none focus:ring-2 focus:ring-[#6750A4] focus:ring-offset-2 
    disabled:opacity-25 
    transition-all duration-200 ease-in-out
']) }}>
    {{ $slot }}
</button>