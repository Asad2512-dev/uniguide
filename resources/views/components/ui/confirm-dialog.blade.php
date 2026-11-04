@props([
    'id' => 'confirm-dialog',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed? This action cannot be reversed.',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'variant' => 'destructive', // destructive, primary
])

<x-ui.modal :id="$id" maxWidth="max-w-md">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $variant === 'destructive' ? 'bg-error-50 text-error-600' : 'bg-brand-50 text-brand-600' }}">
            <x-icon :name="$variant === 'destructive' ? 'alert-triangle' : 'info'" class="w-5 h-5" />
        </div>
        <div>
            <h4 class="text-base font-heading font-bold text-ink-950">{{ $title }}</h4>
            <p class="mt-1 text-xs text-ink-600 font-sans leading-relaxed">{{ $message }}</p>
        </div>
    </div>

    <x-slot:footer>
        <x-ui.button variant="ghost" size="sm" @click="show = false">
            {{ $cancelText }}
        </x-ui.button>
        <x-ui.button :variant="$variant" size="sm" @click="$dispatch('confirmed-action', '{{ $id }}'); show = false">
            {{ $confirmText }}
        </x-ui.button>
    </x-slot:footer>
</x-ui.modal>
