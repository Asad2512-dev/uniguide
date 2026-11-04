@props([
    'sortable' => false,
    'sorted' => null, // 'asc', 'desc', null
    'align' => 'left', // left, center, right
])

@php
    $alignClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<th scope="col" {{ $attributes->merge(['class' => "px-5 py-3.5 bg-surface-subtle border-b border-border-default text-xs font-semibold uppercase tracking-wider text-ink-500 font-sans $alignClass"]) }}>
    @if($sortable)
        <button type="button" class="inline-flex items-center gap-1.5 hover:text-ink-900 group cursor-pointer focus:outline-none">
            <span>{{ $slot }}</span>
            <span class="text-ink-400 group-hover:text-ink-700">
                @if($sorted === 'asc')
                    <x-icon name="chevron-up" class="w-3.5 h-3.5 text-brand-600" />
                @elseif($sorted === 'desc')
                    <x-icon name="chevron-down" class="w-3.5 h-3.5 text-brand-600" />
                @else
                    <x-icon name="sliders-horizontal" class="w-3 h-3 opacity-50 group-hover:opacity-100" />
                @endif
            </span>
        </button>
    @else
        {{ $slot }}
    @endif
</th>

