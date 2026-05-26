@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-yellow-500 text-left text-base font-bold text-yellow-500 bg-gray-800 focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-400 hover:text-gray-200 hover:bg-gray-800 hover:border-gray-600 focus:outline-none focus:text-white focus:bg-gray-800 focus:border-gray-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
