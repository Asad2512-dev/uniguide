<div 
    x-data 
    class="fixed bottom-4 right-4 z-50 flex flex-col gap-2.5 max-w-sm w-full pointer-events-none p-2 sm:p-0"
    role="region" 
    aria-label="Notifications"
>
    <template x-for="toast in $store.toasts.items" :key="toast.id">
        <div 
            x-show="toast.visible"
            x-transition:enter="transform ease-out duration-200 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto flex items-start gap-3 w-full rounded-xl bg-surface border p-3.5 shadow-dropdown"
            :class="{
                'border-success-200 bg-success-50/30': toast.type === 'success',
                'border-error-200 bg-error-50/30': toast.type === 'error',
                'border-warning-200 bg-warning-50/30': toast.type === 'warning',
                'border-border-default bg-surface': toast.type === 'info' || !toast.type
            }"
        >
            <!-- Icon -->
            <div class="shrink-0 mt-0.5">
                <template x-if="toast.type === 'success'">
                    <x-icon name="check-circle-2" class="w-4 h-4 text-success-600" />
                </template>
                <template x-if="toast.type === 'error'">
                    <x-icon name="alert-circle" class="w-4 h-4 text-error-600" />
                </template>
                <template x-if="toast.type === 'warning'">
                    <x-icon name="alert-triangle" class="w-4 h-4 text-warning-600" />
                </template>
                <template x-if="toast.type === 'info' || !toast.type">
                    <x-icon name="info" class="w-4 h-4 text-brand-600" />
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <p x-show="toast.title" x-text="toast.title" class="text-xs font-semibold text-ink-900 font-sans"></p>
                <p x-text="toast.message" class="text-xs text-ink-600 font-sans mt-0.5 leading-relaxed"></p>
            </div>

            <!-- Close Button -->
            <button 
                type="button" 
                @click="$store.toasts.remove(toast.id)"
                class="shrink-0 rounded p-1 text-ink-400 hover:text-ink-700 transition-colors cursor-pointer"
                aria-label="Dismiss notification"
            >
                <x-icon name="x" class="w-3.5 h-3.5" />
            </button>
        </div>
    </template>
</div>

