@props([
    'variant' => 'primary', // primary, secondary, outline, ghost, destructive, soft
    'size' => 'md',        // xs, sm, md, lg
    'type' => 'button',
    'href' => null,
    'icon' => null,
    'iconRight' => null,
    'loading' => false,
    'disabled' => false,
])

@php
    $baseStyles = "inline-flex max-sm:min-h-11 max-sm:max-w-full whitespace-nowrap items-center justify-center font-medium font-sans rounded-lg transition-smooth focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 select-none shrink-0 cursor-pointer disabled:cursor-not-allowed disabled:opacity-50 disabled:pointer-events-none";

    $sizeStyles = match($size) {
        'xs' => 'text-xs px-2.5 py-1.5 gap-1.5 h-7',
        'sm' => 'text-xs px-3 py-1.5 gap-1.5 h-8',
        'lg' => 'text-base px-5 py-2.5 gap-2.5 h-11',
        default => 'text-sm px-4 py-2 gap-2 h-9.5',
    };

    $variantStyles = match($variant) {
        'primary' => 'bg-brand-600 text-white hover:bg-brand-700 active:bg-brand-800 shadow-xs focus-visible:ring-brand-500 border border-transparent',
        'secondary' => 'bg-ink-900 text-white hover:bg-ink-800 active:bg-ink-950 shadow-xs focus-visible:ring-ink-800 border border-transparent',
        'outline' => 'bg-surface text-ink-800 border border-border-default hover:bg-surface-subtle hover:border-border-strong active:bg-surface-muted shadow-xs focus-visible:ring-brand-500',
        'soft' => 'bg-brand-50 text-brand-700 hover:bg-brand-100 active:bg-brand-200 focus-visible:ring-brand-500 border border-brand-100',
        'ghost' => 'bg-transparent text-ink-700 hover:bg-surface-subtle hover:text-ink-950 active:bg-surface-muted focus-visible:ring-brand-500 border border-transparent',
        'destructive' => 'bg-error-500 text-white hover:bg-error-600 active:bg-error-700 shadow-xs focus-visible:ring-error-500 border border-transparent',
        'white', 'inverse' => 'bg-white text-ink-950 font-bold hover:bg-brand-50 hover:text-ink-950 active:bg-brand-100 shadow-elevated focus-visible:ring-white border border-transparent',
        'outline-white', 'outline-inverse' => 'bg-white/10 backdrop-blur-xs text-white font-semibold border border-white/30 hover:bg-white/20 hover:border-white/60 active:bg-white/25 focus-visible:ring-white',
        default => 'bg-brand-600 text-white hover:bg-brand-700 active:bg-brand-800 shadow-xs focus-visible:ring-brand-500 border border-transparent',
    };

    $classes = "$baseStyles $sizeStyles $variantStyles";
    $iconSize = match($size) {
        'xs' => 'w-3.5 h-3.5',
        'sm' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
        default => 'w-4 h-4',
    };
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if($disabled) aria-disabled="true" tabindex="-1" @endif>
        @if($loading)
            <x-icon name="loader-2" class="{{ $iconSize }} animate-spin" />
        @elseif($icon)
            <x-icon :name="$icon" class="{{ $iconSize }}" />
        @endif

        <span>{{ $slot }}</span>

        @if($iconRight && !$loading)
            <x-icon :name="$iconRight" class="{{ $iconSize }}" />
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if($disabled || $loading) disabled aria-disabled="true" @endif>
        @if($loading)
            <x-icon name="loader-2" class="{{ $iconSize }} animate-spin" />
        @elseif($icon)
            <x-icon :name="$icon" class="{{ $iconSize }}" />
        @endif

        <span>{{ $slot }}</span>

        @if($iconRight && !$loading)
            <x-icon :name="$iconRight" class="{{ $iconSize }}" />
        @endif
    </button>
@endif

