@props([
    'label',
    'value' => null,
    'count' => null,
    'active' => false,
    'removable' => false,
])

<button 
    type="button" 
    {{ $attributes->merge([
        'class' => 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium font-sans transition-smooth cursor-pointer border select-none ' . 
        ($active 
            ? 'bg-brand-50 text-brand-700 border-brand-300 shadow-2xs font-semibold' 
            : 'bg-surface text-ink-700 border-border-default hover:border-border-strong hover:bg-surface-subtle'
        )
    ]) }}
>
    <span>{{ $label }}</span>
    @if($count !== null)
        <span class="inline-flex items-center justify-center px-1.5 py-0.2 rounded-full text-xs sm:text-[10px] {{ $active ? 'bg-brand-600 text-white font-bold' : 'bg-ink-100 text-ink-600' }}">
            {{ $count }}
        </span>
    @endif
    @if($removable)
        <span class="text-ink-600 hover:text-ink-700 -mr-0.5" aria-hidden="true">
            <x-icon name="x" class="w-3 h-3" />
        </span>
    @endif
</button>

