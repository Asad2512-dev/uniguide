@props([
    'id' => 'modal',
    'title' => null,
    'description' => null,
    'maxWidth' => 'max-w-lg', // max-w-sm, max-w-md, max-w-lg, max-w-xl, max-w-2xl
])

<div 
    x-data="{ show: false }" 
    x-show="show" 
    x-modal-focus="show"
    x-cloak
    @open-modal.window="if ($event.detail === '{{ $id }}') show = true"
    @close-modal.window="if ($event.detail === '{{ $id }}') show = false"
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 md:py-12"
    aria-labelledby="{{ $id }}-title" 
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

    <!-- Dialog Panel -->
    <div class="flex min-h-full items-center justify-center p-2 text-center sm:p-0">
        <div 
            x-show="show" 
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.stop
            class="relative transform overflow-hidden rounded-2xl bg-surface border border-border-default text-left shadow-modal transition-all w-full {{ $maxWidth }}"
        >
            @if($title)
                <div class="px-6 pt-5 pb-4 border-b border-border-subtle flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-heading font-bold text-ink-950" id="{{ $id }}-title">
                            {{ $title }}
                        </h3>
                        @if($description)
                            <p class="mt-0.5 text-xs text-ink-500 font-sans">
                                {{ $description }}
                            </p>
                        @endif
                    </div>
                    <button 
                        type="button" 
                        @click="show = false" 
                        class="rounded-lg p-1 text-ink-400 hover:text-ink-700 hover:bg-surface-subtle transition-colors focus:outline-none focus:ring-2 focus:ring-brand-500"
                        aria-label="Close dialog"
                    >
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>
            @endif

            <div class="px-6 py-5">
                {{ $slot }}
            </div>

            @if(isset($footer))
                <div class="px-6 py-3.5 bg-surface-subtle/50 border-t border-border-subtle flex flex-wrap items-center justify-end gap-2.5">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

