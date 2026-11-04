@props([
    'tabs' => [], // array of ['id' => 'tab1', 'label' => 'Overview', 'icon' => 'layout-dashboard', 'badge' => '3']
    'default' => null,
    'variant' => 'underline', // underline, pills
])

@php
    $defaultTab = $default ?? ($tabs[0]['id'] ?? '');
@endphp

<div 
    x-data="{ currentTab: '{{ $defaultTab }}' }" 
    class="w-full"
>
    <div class="{{ $variant === 'underline' ? 'border-b border-border-default' : 'p-1 bg-surface-subtle rounded-xl inline-flex' }}">
        <nav class="flex space-x-2 md:space-x-6 overflow-x-auto scrollbar-none" aria-label="Tabs">
            @foreach($tabs as $tab)
                <button 
                    type="button" 
                    @click="currentTab = '{{ $tab['id'] }}'; $dispatch('tab-change', '{{ $tab['id'] }}')"
                    :class="currentTab === '{{ $tab['id'] }}' 
                        ? '{{ $variant === 'underline' ? 'border-brand-600 text-brand-700 font-semibold' : 'bg-surface text-ink-950 font-semibold shadow-xs' }}' 
                        : '{{ $variant === 'underline' ? 'border-transparent text-ink-500 hover:text-ink-800 hover:border-ink-300 font-medium' : 'text-ink-600 hover:text-ink-900 font-medium' }}'"
                    class="group inline-flex items-center gap-2 py-3 px-1 text-sm font-sans transition-smooth cursor-pointer whitespace-nowrap {{ $variant === 'underline' ? 'border-b-2 -mb-px' : 'rounded-lg px-3.5 py-1.5' }}"
                >
                    @if(isset($tab['icon']))
                        <x-icon :name="$tab['icon']" class="w-4 h-4 shrink-0 transition-colors" ::class="currentTab === '{{ $tab['id'] }}' ? 'text-brand-600' : 'text-ink-400 group-hover:text-ink-600'" />
                    @endif
                    <span>{{ $tab['label'] }}</span>
                    @if(isset($tab['badge']))
                        <span 
                            :class="currentTab === '{{ $tab['id'] }}' ? 'bg-brand-50 text-brand-700' : 'bg-ink-100 text-ink-500'"
                            class="px-2 py-0.5 rounded-full text-xs font-mono font-medium"
                        >
                            {{ $tab['badge'] }}
                        </span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    @if(trim($slot))
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</div>

