@props([
    'text',
    'position' => 'top', // top, bottom
])

@php
    $posClasses = match($position) {
        'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-1.5',
        default => 'bottom-full left-1/2 -translate-x-1/2 mb-1.5',
    };
@endphp

<div 
    x-data="{ show: false }" 
    @mouseenter="show = true" 
    @mouseleave="show = false"
    @focusin="show = true" 
    @focusout="show = false"
    class="relative inline-block"
>
    {{ $slot }}

    <div 
        x-show="show" 
        x-cloak
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute {{ $posClasses }} z-50 px-2.5 py-1 text-[11px] font-sans font-medium text-white bg-ink-950 rounded-md shadow-lg whitespace-nowrap pointer-events-none"
        role="tooltip"
    >
        {{ $text }}
    </div>
</div>

