@props([
    'variant' => 'neutral', // brand, success, warning, error, neutral, lavender, mint, outline
    'size' => 'md',        // sm, md
    'dot' => false,
    'pulse' => false,
    'icon' => null,
])

@php
    $baseStyles = "inline-flex items-center font-medium font-sans rounded-full tracking-wide select-none";

    $sizeStyles = match($size) {
        'sm' => 'text-[11px] px-2 py-0.5 gap-1 leading-tight',
        default => 'text-xs px-2.5 py-1 gap-1.5 leading-tight',
    };

    $variantStyles = match($variant) {
        'brand' => 'bg-brand-50 text-brand-700 border border-brand-200/70',
        'success' => 'bg-success-50 text-success-700 border border-success-200/70',
        'warning' => 'bg-warning-50 text-warning-700 border border-warning-200/70',
        'error' => 'bg-error-50 text-error-700 border border-error-200/70',
        'lavender' => 'bg-lavender-50 text-lavender-600 border border-lavender-100',
        'mint' => 'bg-mint-50 text-mint-600 border border-mint-100',
        'outline' => 'bg-surface text-ink-700 border border-border-default',
        default => 'bg-ink-100 text-ink-700 border border-ink-200/60',
    };

    $dotColor = match($variant) {
        'brand' => 'bg-brand-600',
        'success' => 'bg-success-500',
        'warning' => 'bg-warning-500',
        'error' => 'bg-error-500',
        'lavender' => 'bg-lavender-500',
        'mint' => 'bg-mint-500',
        default => 'bg-ink-500',
    };

    $classes = "$baseStyles $sizeStyles $variantStyles";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="relative flex h-1.5 w-1.5 shrink-0">
            @if($pulse)
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $dotColor }} opacity-75"></span>
            @endif
            <span class="relative inline-flex rounded-full h-1.5 w-1.5 {{ $dotColor }}"></span>
        </span>
    @elseif($icon)
        <x-icon :name="$icon" class="w-3 h-3 shrink-0" />
    @endif
    <span>{{ $slot }}</span>
</span>

