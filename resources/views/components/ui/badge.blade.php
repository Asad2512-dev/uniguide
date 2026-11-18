@props([
    'variant' => 'neutral',
    'size' => 'md',
    'dot' => false,
    'pulse' => false,
    'icon' => null,
])
@php
    $color = match ($variant) {
        'success', 'mint' => 'text-success-700',
        'warning' => 'text-warning-700',
        'error' => 'text-error-700',
        'brand', 'lavender' => 'text-brand-700',
        default => 'text-ink-600',
    };
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 text-xs font-medium $color"]) }}>
    @if($icon)<x-icon :name="$icon" class="w-3 h-3 shrink-0" />@endif
    {{ $slot }}
</span>
