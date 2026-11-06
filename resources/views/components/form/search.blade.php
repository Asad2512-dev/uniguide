@props([
    'name' => 'search',
    'id' => 'search-input',
    'placeholder' => 'Search universities, BS/MS programs, merit formulas...',
    'shortcut' => '⌘K',
    'value' => '',
])

<div 
    x-data="{ query: @js($value) }"
    class="relative w-full"
>
    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-ink-400">
        <x-icon name="search" class="w-4 h-4" />
    </div>

    <input 
        x-ref="searchInput"
        type="search" 
        name="{{ $name }}" 
        id="{{ $id }}"
        x-model="query"
        placeholder="{{ $placeholder }}"
        aria-label="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'block w-full rounded-lg font-sans text-sm text-ink-900 bg-surface pl-10 pr-16 py-2 border border-border-default hover:border-border-strong focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 placeholder:text-ink-400 transition-smooth shadow-2xs'
        ]) }}
    />

    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center gap-1.5">
        <button 
            type="button" 
            x-show="query.length > 0" 
            x-cloak
            @click="query = ''; $refs.searchInput.focus()"
            class="text-ink-400 hover:text-ink-700 p-0.5 rounded cursor-pointer transition-smooth"
            aria-label="Clear search"
        >
            <x-icon name="x" class="w-3.5 h-3.5" />
        </button>

        @if($shortcut)
            <kbd class="hidden sm:inline-flex items-center px-1.5 py-0.5 text-xs sm:text-[10px] font-mono font-medium text-ink-400 bg-surface-subtle border border-border-default rounded shadow-2xs select-none">
                {{ $shortcut }}
            </kbd>
        @endif
    </div>
</div>

