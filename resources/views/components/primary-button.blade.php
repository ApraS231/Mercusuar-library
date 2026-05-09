<button {{ $attributes->merge(['type' => 'submit', 'class' => '
    inline-flex items-center justify-center 
    px-6 py-2.5 
    bg-[#6750A4] 
    border border-transparent 
    rounded-full 
    font-medium text-sm text-white 
    shadow-sm hover:shadow-md 
    focus:bg-[#6750A4]/90 focus:outline-none focus:ring-2 focus:ring-[#6750A4] focus:ring-offset-2 
    active:bg-[#6750A4] active:scale-[0.98] 
    transition-all duration-200 ease-in-out
']) }}>
    {{ $slot }}
</button>