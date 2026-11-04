@props([
    'title',
    'description' => null,
    'badge' => null,
    'badgeVariant' => 'brand',
    'breadcrumbs' => null,
])

<div {{ $attributes->merge(['class' => 'pb-6 mb-6 border-b border-border-default']) }}>
    @if($breadcrumbs)
        <div class="mb-3">
            {{ $breadcrumbs }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2.5">
                <h1 class="text-2xl md:text-3xl font-heading font-bold text-ink-950 tracking-tight">
                    {{ $title }}
                </h1>
                @if($badge)
                    <x-ui.badge :variant="$badgeVariant" size="sm">
                        {{ $badge }}
                    </x-ui.badge>
                @endif
            </div>

            @if($description)
                <p class="text-sm text-ink-600 max-w-3xl font-sans leading-relaxed">
                    {{ $description }}
                </p>
            @endif
        </div>

        @if(isset($actions))
            <div class="flex items-center gap-2.5 shrink-0">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>

