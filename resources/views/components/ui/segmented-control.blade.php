@props([
    'options' => [], // array of ['id' => 'all', 'label' => 'All Universities', 'count' => 248]
    'default' => null,
    'name' => 'segment',
])

@php
    $defaultSelected = $default ?? ($options[0]['id'] ?? '');
@endphp

<div 
    x-data="{ active: '{{ $defaultSelected }}' }" 
    class="inline-flex items-center p-1 bg-surface-subtle border border-border-default rounded-xl shadow-2xs"
>
    @foreach($options as $opt)
        <button 
            type="button" 
            @click="active = '{{ $opt['id'] }}'; $dispatch('segment-change', { name: '{{ $name }}', value: '{{ $opt['id'] }}' })"
            :class="active === '{{ $opt['id'] }}' 
                ? 'bg-surface text-ink-950 font-semibold shadow-xs border-border-default/60' 
                : 'text-ink-600 hover:text-ink-900 border-transparent hover:bg-surface/50'"
            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-sans transition-all duration-200 border cursor-pointer select-none"
        >
            <span>{{ $opt['label'] }}</span>
            @if(isset($opt['count']))
                <span 
                    :class="active === '{{ $opt['id'] }}' ? 'bg-brand-50 text-brand-700 font-bold' : 'bg-ink-100 text-ink-500'"
                    class="px-1.5 py-0.2 rounded-full text-[10px] font-mono leading-none"
                >
                    {{ $opt['count'] }}
                </span>
            @endif
        </button>
    @endforeach
</div>

