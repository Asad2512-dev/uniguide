@props([
    'variant' => 'outline', // outline, ghost, soft, primary
    'size' => 'md',        // xs, sm, md, lg
    'icon' => 'plus',
    'label' => 'Action',
    'type' => 'button',
    'href' => null,
])

@php
    $baseStyles = "inline-flex items-center justify-center font-sans rounded-lg transition-smooth focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 shrink-0 cursor-pointer disabled:cursor-not-allowed disabled:opacity-50";

    $sizeStyles = match($size) {
        'xs' => 'w-7 h-7 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'lg' => 'w-11 h-11 text-base',
        default => 'w-9.5 h-9.5 text-sm',
    };

    $iconDimensions = match($size) {
        'xs' => 'w-3.5 h-3.5',
        'sm' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
        default => 'w-4.5 h-4.5',
    };

    $variantStyles = match($variant) {
        'primary' => 'bg-brand-600 text-white hover:bg-brand-700 active:bg-brand-800 shadow-xs focus-visible:ring-brand-500 border border-transparent',
        'soft' => 'bg-brand-50 text-brand-700 hover:bg-brand-100 active:bg-brand-200 focus-visible:ring-brand-500 border border-brand-100',
        'ghost' => 'bg-transparent text-ink-600 hover:bg-surface-subtle hover:text-ink-900 active:bg-surface-muted focus-visible:ring-brand-500 border border-transparent',
        default => 'bg-surface text-ink-700 border border-border-default hover:bg-surface-subtle hover:border-border-strong active:bg-surface-muted shadow-xs focus-visible:ring-brand-500',
    };

    $classes = "$baseStyles $sizeStyles $variantStyles";
@endphp

@if($href)
    <a href="{{ $href }}" aria-label="{{ $label }}" title="{{ $label }}" {{ $attributes->merge(['class' => $classes]) }}>
        <x-icon :name="$icon" class="{{ $iconDimensions }}" />
    </a>
@else
    <button type="{{ $type }}" aria-label="{{ $label }}" title="{{ $label }}" {{ $attributes->merge(['class' => $classes]) }}>
        <x-icon :name="$icon" class="{{ $iconDimensions }}" />
    </button>
@endif
