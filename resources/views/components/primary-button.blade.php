<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-3 border border-transparent rounded-xl font-bold text-lg text-gray-900 uppercase tracking-widest shadow-lg transition-all ease-in-out duration-150 transform hover:-translate-y-0.5 w-full']) }}
        style="background-color: var(--primary-color); box-shadow: 0 5px 15px var(--primary-glow);">
    {{ $slot }}
</button>
