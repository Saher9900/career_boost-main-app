{{-- Primary action button: filled teal, darkening to the logo's deeper shade
     on hover/focus/active. --}}
<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#138A9E] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#0E6378] focus:bg-[#0E6378] active:bg-[#0E6378] focus:outline-none focus:ring-2 focus:ring-[#138A9E] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
