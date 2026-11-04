@props([
    'name',
    'id' => null,
    'label' => null,
    'description' => null,
    'checked' => false,
    'disabled' => false,
])

@php
    $id = $id ?? $name;
@endphp

<div 
    x-data="{ active: {{ $checked ? 'true' : 'false' }} }" 
    class="flex items-center justify-between gap-4 py-1"
>
    @if($label || $description)
        <div class="flex flex-col">
            <span class="text-xs font-semibold text-ink-900 font-sans">{{ $label }}</span>
            @if($description)
                <span class="text-[11px] text-ink-500 font-sans mt-0.5">{{ $description }}</span>
            @endif
        </div>
    @endif

    <button 
        type="button" 
        role="switch" 
        :aria-checked="active.toString()"
        @click="if(!{{ $disabled ? 'true' : 'false' }}) active = !active"
        :class="active ? 'bg-brand-600' : 'bg-ink-200'"
        class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
        @if($disabled) disabled @endif
    >
        <span 
            aria-hidden="true" 
            :class="active ? 'translate-x-5' : 'translate-x-0'"
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-xs ring-0 transition duration-200 ease-in-out"
        ></span>
    </button>
    <input type="hidden" name="{{ $name }}" :value="active ? '1' : '0'" />
</div>
