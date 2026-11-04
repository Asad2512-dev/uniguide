@props([
    'align' => 'left', // left, center, right
    'class' => '',
])

@php
    $alignClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<td {{ $attributes->merge(['class' => "px-5 py-3.5 text-xs md:text-sm font-sans text-ink-800 align-middle $alignClass $class"]) }}>
    {{ $slot }}
</td>

