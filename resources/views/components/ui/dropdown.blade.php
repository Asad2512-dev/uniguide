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
    x-id="['dropdown-panel']"
    @click.outside="open = false" 
    @keydown.escape="if (open) { $event.stopPropagation(); open = false; $refs.trigger.querySelector('button').focus() }"

    class="relative inline-block text-left"
>
    <div x-ref="trigger" @click="open = !open"
        @keydown.arrow-down.prevent="open = true; $nextTick(() => $refs.panel.querySelector('a, button')?.focus())"
        x-init="const button = $el.querySelector('button'); if (button) { button.setAttribute('aria-controls', $id('dropdown-panel')); $watch('open', value => button.setAttribute('aria-expanded', String(value))); button.setAttribute('aria-expanded', 'false') }">
        {{ $trigger }}
    </div>

    <div 
        @keydown.arrow-down.prevent="const items = [...$refs.panel.querySelectorAll('a, button')].filter(item => item.getClientRects().length); items[(items.indexOf(document.activeElement) + 1) % items.length]?.focus()"
        @keydown.arrow-up.prevent="const items = [...$refs.panel.querySelectorAll('a, button')].filter(item => item.getClientRects().length); items[(items.indexOf(document.activeElement) - 1 + items.length) % items.length]?.focus()"
        @focusout="setTimeout(() => { if (!$root.contains(document.activeElement)) open = false }, 0)"
        x-ref="panel"
        :id="$id('dropdown-panel')"
        x-show="open" 
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute z-50 max-w-[calc(100vw-2rem)] max-h-[70dvh] overflow-y-auto mt-2 {{ $width }} {{ $alignmentClasses }} rounded-xl border border-border-default bg-surface p-1.5 shadow-dropdown focus:outline-none"
    >
        {{ $content }}
    </div>
</div>

