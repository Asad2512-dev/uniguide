@props([
    'status' => 'open', // open, closing_soon, closed, merit_announced, rolling, upcoming
    'deadline' => null,
    'size' => 'md',
])

@php
    $config = match($status) {
        'open' => [
            'label' => 'Admissions Open',
            'variant' => 'success',
            'pulse' => true,
            'icon' => 'check-circle-2',
        ],
        'closing_soon' => [
            'label' => 'Closing Soon',
            'variant' => 'warning',
            'pulse' => true,
            'icon' => 'clock',
        ],
        'merit_announced' => [
            'label' => 'Merit List Announced',
            'variant' => 'brand',
            'pulse' => false,
            'icon' => 'list-ordered',
        ],
        'upcoming' => [
            'label' => 'Admissions Upcoming',
            'variant' => 'lavender',
            'pulse' => false,
            'icon' => 'calendar',
        ],
        'rolling' => [
            'label' => 'Rolling Admissions',
            'variant' => 'mint',
            'pulse' => false,
            'icon' => 'refresh-cw',
        ],
        default => [
            'label' => 'Admissions Closed',
            'variant' => 'neutral',
            'pulse' => false,
            'icon' => 'x',
        ],
    };
@endphp

<div class="inline-flex items-center gap-2">
    <x-ui.badge :variant="$config['variant']" :size="$size" :dot="true" :pulse="$config['pulse']">
        {{ $config['label'] }}
    </x-ui.badge>
    @if($deadline)
        <span class="text-xs text-ink-500 font-sans">
            Deadline: <span class="font-semibold text-ink-800">{{ $deadline }}</span>
        </span>
    @endif
</div>

