@props([
    'id' => 'drawer',
    'title' => null,
    'description' => null,
    'position' => 'right', // right, left
    'width' => 'max-w-md',
])

@php
    $sideClasses = match($position) {
        'left' => 'left-0',
        default => 'right-0',
    };

    $enterStart = match($position) {
        'left' => '-translate-x-full',
        default => 'translate-x-full',
    };
@endphp

<div 
    x-data="{ show: false }" 
    x-show="show" 
    x-cloak
    @open-drawer.window="if ($event.detail === '{{ $id }}') show = true"
    @close-drawer.window="if ($event.detail === '{{ $id }}') show = false"
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-hidden"
    role="dialog" 
    aria-modal="true"
>
    <!-- Backdrop Blur -->
    <div 
        x-show="show" 
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-ink-950/40 backdrop-blur-xs transition-opacity" 
        @click="show = false"
        aria-hidden="true"
    ></div>

    <!-- Slide-over panel -->
    <div class="fixed inset-y-0 {{ $sideClasses }} flex max-w-full">
        <div 
            x-show="show" 
            x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="{{ $enterStart }}"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-250"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="{{ $enterStart }}"
            class="w-screen {{ $width }} bg-surface border-l border-border-default shadow-modal flex flex-col justify-between"
            @click.stop
        >
            <!-- Drawer Header -->
            <div class="px-6 py-4 border-b border-border-default flex items-center justify-between">
                <div>
                    @if($title)
                        <h3 class="text-base font-heading font-bold text-ink-950">{{ $title }}</h3>
                    @endif
                    @if($description)
                        <p class="text-xs text-ink-500 font-sans mt-0.5">{{ $description }}</p>
                    @endif
                </div>
                <button 
                    type="button" 
                    @click="show = false" 
                    class="rounded-lg p-1 text-ink-400 hover:text-ink-700 hover:bg-surface-subtle transition-colors"
                    aria-label="Close drawer"
                >
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>

            <!-- Drawer Body -->
            <div class="flex-1 overflow-y-auto px-6 py-5">
                {{ $slot }}
            </div>

            <!-- Drawer Footer -->
            @if(isset($footer))
                <div class="px-6 py-4 bg-surface-subtle/60 border-t border-border-default flex items-center justify-end gap-2.5">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
