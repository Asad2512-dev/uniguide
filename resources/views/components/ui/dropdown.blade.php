@props([
    'align' => 'right', // left, right
    'width' => 'w-56',
])

@php
    $alignmentClasses = match($align) {
        'left' => 'left-0 origin-top-left',
        default => 'right-0 origin-top-right',
    };
@endphp

<div 
    x-data="{ open: false }" 
    @click.outside="open = false" 
    @keydown.escape.window="open = false"
    class="relative inline-block text-left"
>
    <div @click="open = !open">
        {{ $trigger }}
    </div>

    <div 
        x-show="open" 
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute z-50 mt-2 {{ $width }} {{ $alignmentClasses }} rounded-xl border border-border-default bg-surface p-1.5 shadow-dropdown focus:outline-none"
    >
        {{ $content }}
    </div>
</div>
