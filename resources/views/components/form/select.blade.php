@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [], // associative array ['value' => 'Label'] or simple array
    'selected' => null,
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
    'helperText' => null,
    'error' => null,
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
        </div>
    @endif

    <div class="relative rounded-lg shadow-2xs">
        <select 
            name="{{ $name }}" 
            id="{{ $id }}" 
            @if($required) required @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'aria-invalid' => $hasError ? 'true' : null,
                'aria-describedby' => $hasError ? $id . '-error' : ($helperText ? $id . '-help' : null),
                'class' => 'block min-h-11 min-w-0 w-full appearance-none rounded-lg font-sans text-sm text-ink-900 bg-surface pl-3.5 pr-10 py-2 border transition-smooth focus:outline-none focus:ring-2 focus:ring-offset-0 disabled:bg-surface-subtle disabled:text-ink-400 disabled:cursor-not-allowed ' .
                ($hasError 
                    ? 'border-error-500 focus:border-error-500 focus:ring-error-500/20' 
                    : 'border-border-default hover:border-border-strong focus:border-brand-500 focus:ring-brand-500/20'
                )
            ]) }}
        >
            @if($placeholder)
                <option value="" disabled {{ old($name, $selected) === null ? 'selected' : '' }}>
                    {{ $placeholder }}
                </option>
            @endif

            @if(is_array($options) && count($options) > 0)
                @foreach($options as $val => $optLabel)
                    @php
                        $value = is_string($val) ? $val : $optLabel;
                        $text = is_string($val) ? $optLabel : $val;
                        $isSelected = old($name, $selected) == $value;
                    @endphp
                    <option value="{{ $value }}" {{ $isSelected ? 'selected' : '' }}>
                        {{ $text }}
                    </option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>

        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-ink-400">
            <x-icon name="chevron-down" class="w-4 h-4" />
        </div>
    </div>

    @if($hasError)
        <p id="{{ $id }}-error" class="mt-1.5 text-xs text-error-600 flex items-center gap-1 font-sans" role="alert">
            <x-icon name="alert-circle" class="w-3.5 h-3.5 shrink-0" />
            <span>{{ $error }}</span>
        </p>
    @elseif($helperText)
        <p id="{{ $id }}-help" class="mt-1.5 text-xs text-ink-500 font-sans">
            {{ $helperText }}
        </p>
    @endif
</div>

