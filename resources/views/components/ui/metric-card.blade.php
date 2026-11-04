@props([
    'title',
    'value',
    'change' => null,
    'changeType' => 'up', // up, down, neutral
    'period' => 'vs last admission cycle',
    'icon' => null,
    'iconColor' => 'brand', // brand, success, warning, error, lavender
])

@php
    $iconBg = match($iconColor) {
        'success' => 'bg-success-50 text-success-600 border border-success-200/60',
        'warning' => 'bg-warning-50 text-warning-600 border border-warning-200/60',
        'error' => 'bg-error-50 text-error-600 border border-error-200/60',
        'lavender' => 'bg-lavender-50 text-lavender-600 border border-lavender-200/60',
        default => 'bg-brand-50 text-brand-600 border border-brand-200/60',
    };

    $changeBadge = match($changeType) {
        'up' => 'text-success-700 bg-success-50 border-success-200/70',
        'down' => 'text-error-700 bg-error-50 border-error-200/70',
        default => 'text-ink-600 bg-ink-100 border-ink-200',
    };

    $changeIcon = match($changeType) {
        'up' => 'trending-up',
        'down' => 'trending-down',
        default => 'minus',
    };
@endphp

<x-ui.card hover="true" padding="p-5">
    <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-ink-500 font-sans tracking-wide uppercase">
            {{ $title }}
        </span>
        @if($icon)
            <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $iconBg }}">
                <x-icon :name="$icon" class="w-4.5 h-4.5" />
            </div>
        @endif
    </div>

    <div class="mt-3 flex items-baseline gap-2.5">
        <span class="text-2xl lg:text-3xl font-heading font-extrabold text-ink-950 tracking-tight">
            {{ $value }}
        </span>

        @if($change !== null)
            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[11px] font-semibold border {{ $changeBadge }}">
                <x-icon :name="$changeIcon" class="w-3 h-3" />
                <span>{{ $change }}</span>
            </span>
        @endif
    </div>

    @if($period)
        <p class="mt-1 text-xs text-ink-400 font-sans">
            {{ $period }}
        </p>
    @endif

    @if(trim($slot))
        <div class="mt-3 pt-3 border-t border-border-subtle">
            {{ $slot }}
        </div>
    @endif
</x-ui.card>

