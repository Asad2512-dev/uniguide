@props([
    'tagline' => true,
    'size' => 'md', // sm, md, lg
    'withBadge' => true,
    'badgeText' => 'PAKISTAN',
    'href' => route('preview.public'),
])

@php
    $logoSize = match($size) {
        'sm' => 'sm',
        'lg' => 'lg',
        default => 'md',
    };

    $textSize = match($size) {
        'sm' => 'text-base',
        'lg' => 'text-2xl',
        default => 'text-lg',
    };

    $taglineSize = match($size) {
        'sm' => 'text-[9px]',
        'lg' => 'text-[11px]',
        default => 'text-[10px]',
    };
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex shrink-0 items-center gap-2.5 whitespace-nowrap group focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 rounded-lg py-0.5 transition-smooth']) }}>
    <x-brand.logo :size="$logoSize" class="transition-transform duration-300 group-hover:scale-105" />
    <div class="flex flex-col">
        <div class="flex items-center gap-1.5 leading-none">
            <span class="font-heading font-extrabold tracking-tight text-ink-950 {{ $textSize }}">
                Uni<span class="text-brand-600">Guide</span>
            </span>
            @if($withBadge)
                <span data-country-badge class="inline-flex items-center px-1.5 py-0.5 rounded text-xs sm:text-[9px] font-semibold tracking-wider uppercase bg-brand-50 text-brand-700 border border-brand-200/60 leading-none">
                    {{ $badgeText }}
                </span>
            @endif
        </div>
        @if($tagline)
        <span class="font-sans font-medium text-ink-500 tracking-normal mt-0.5 {{ $taglineSize }}">
            National Higher Education Portal
        </span>
        @endif
    </div>
</a>

