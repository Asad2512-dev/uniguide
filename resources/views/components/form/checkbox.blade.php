@props([
    'name',
    'id' => null,
    'label' => null,
    'description' => null,
    'checked' => false,
    'disabled' => false,
    'value' => '1',
])

@php
    $id = $id ?? $name . '_' . uniqid();
@endphp

<label for="{{ $id }}" class="relative flex items-start gap-2.5 cursor-pointer select-none group {{ $disabled ? 'opacity-50 cursor-not-allowed' : '' }}">
    <div class="flex items-center h-5">
        <input 
            type="checkbox" 
            name="{{ $name }}" 
            id="{{ $id }}" 
            value="{{ $value }}"
            @if($checked) checked @endif
            @if($disabled) disabled @endif
            {{ $attributes->merge([
                'class' => 'w-4 h-4 rounded border-border-default text-brand-600 focus:ring-2 focus:ring-brand-500/20 focus:ring-offset-0 transition-smooth cursor-pointer disabled:cursor-not-allowed'
            ]) }}
        />
    </div>
    @if($label || $description)
        <div class="text-xs font-sans leading-tight">
            @if($label)
                <span class="font-medium text-ink-900 group-hover:text-brand-700 transition-colors">{{ $label }}</span>
            @endif
            @if($description)
                <p class="text-ink-500 text-xs sm:text-[11px] mt-0.5">{{ $description }}</p>
            @endif
        </div>
    @endif
</label>

