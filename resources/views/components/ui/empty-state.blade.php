@props([
    'icon' => 'search',
    'title' => 'No results found',
    'description' => 'Try adjusting your search query, filter criteria, or exploring different provinces.',
    'actionLabel' => null,
    'actionHref' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center p-8 md:p-12 text-center rounded-2xl border border-dashed border-border-default bg-surface/50']) }}>
    <div class="w-12 h-12 rounded-2xl bg-surface-subtle border border-border-default flex items-center justify-center text-ink-400 mb-4 shadow-2xs">
        <x-icon :name="$icon" class="w-6 h-6" />
    </div>

    <h3 class="font-heading font-bold text-base md:text-lg text-ink-950">
        {{ $title }}
    </h3>

    <p class="mt-1.5 text-xs md:text-sm text-ink-500 font-sans max-w-sm leading-relaxed">
        {{ $description }}
    </p>

    @if($actionLabel)
        <div class="mt-5">
            <x-ui.button variant="primary" size="sm" :href="$actionHref">
                {{ $actionLabel }}
            </x-ui.button>
        </div>
    @elseif(isset($action))
        <div class="mt-5">
            {{ $action }}
        </div>
    @endif
</div>

