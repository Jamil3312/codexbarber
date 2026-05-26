@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-700 bg-gray-800 text-gray-200 rounded-xl shadow-inner transition-colors duration-200 py-3']) !!} 
    style="--tw-ring-color: var(--primary-color); --tw-border-opacity: 1; border-color: rgba(55, 65, 81, var(--tw-border-opacity));"
    onfocus="this.style.borderColor='var(--primary-color)';"
    onblur="this.style.borderColor='rgba(55, 65, 81, 1)'">
