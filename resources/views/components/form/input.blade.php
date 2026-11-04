@props([
    'name',
    'id' => null,
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'value' => null,
    'required' => false,
    'disabled' => false,
    'helperText' => null,
    'error' => null,
    'icon' => null,
    'iconRight' => null,
])

@php
    $id = $id ?? $name;
    $hasError = !empty($error);
@endphp

<div class="w-full">
    @if($label)
        <div class="flex items-center justify-between mb-1.5">
            <label for="{{ $id }}" class="block text-xs font-semibold text-ink-800 tracking-wide font-sans">
                {{ $label }}
                @if($required)
                    <span class="text-error-500 ml-0.5" aria-hidden="true">*</span>
                @endif
            </label>
            @if(!$required)
                <span class="text-[11px] text-ink-400 font-sans">Optional</span>
            @endif
        </div>
    @endif

    <div class="relative rounded-lg shadow-2xs">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-ink-400">
                <x-icon :name="$icon" class="w-4 h-4" />
            </div>
        @endif

        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $id }}" 
            placeholder="{{ $placeholder }}"
            value="{{ old($name, $value) }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => 'block w-full rounded-lg font-sans text-sm text-ink-900 bg-surface placeholder:text-ink-400 transition-smooth focus:outline-none focus:ring-2 focus:ring-offset-0 disabled:bg-surface-subtle disabled:text-ink-400 disabled:cursor-not-allowed ' . 
                ($icon ? 'pl-9 ' : 'pl-3.5 ') . 
                ($iconRight ? 'pr-9 ' : 'pr-3.5 ') . 
                'py-2 border ' . 
                ($hasError 
                    ? 'border-error-500 focus:border-error-500 focus:ring-error-500/20' 
                    : 'border-border-default hover:border-border-strong focus:border-brand-500 focus:ring-brand-500/20'
                )
            ]) }}
        />

        @if($iconRight)
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-ink-400">
                <x-icon :name="$iconRight" class="w-4 h-4" />
            </div>
        @endif
    </div>

    @if($hasError)
        <p class="mt-1.5 text-xs text-error-600 flex items-center gap-1 font-sans" role="alert">
            <x-icon name="alert-circle" class="w-3.5 h-3.5 shrink-0" />
            <span>{{ $error }}</span>
        </p>
    @elseif($helperText)
        <p class="mt-1.5 text-xs text-ink-500 font-sans">
            {{ $helperText }}
        </p>
    @endif
</div>

