@props([
    'menuKey' => 'student',
    'roleLabel' => 'Portal',
])

@php
    $menuData = config("navigation.$menuKey", []);
    $mainItems = $menuData['main'] ?? [];
    $secondaryItems = $menuData['secondary'] ?? [];
@endphp

<div 
    x-data="{ open: false }" 
    @toggle-mobile-sidebar.window="open = !open"
    @keydown.escape.window="open = false"
    x-modal-focus="open"
    @resize.window="if (window.innerWidth >= 768) open = false"
    x-show="open" 
    x-cloak
    role="dialog"
    aria-modal="true"
    aria-label="Mobile Navigation Drawer"
    class="md:hidden fixed inset-0 z-50 bg-ink-950/40 backdrop-blur-xs"
    @click="open = false"
>
    <div 
        x-show="open" 
        x-transition:enter="transform transition ease-in-out duration-250"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in-out duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 w-72 max-w-full bg-surface p-4 [--safe-bottom-padding:1rem] safe-area-top safe-area-bottom shadow-modal flex flex-col justify-between overflow-y-auto overscroll-contain"
        @click.stop
    >
        <div class="space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-border-default">
                <x-brand.wordmark size="sm" />
                <button type="button" @click="open = false" class="p-3 text-ink-400 hover:text-ink-700 cursor-pointer" aria-label="Close navigation menu">
                    <x-icon name="x" class="w-5 h-5" />
                </button>
            </div>

            <nav class="space-y-1" aria-label="Mobile Drawer Navigation">
                @foreach(['Workspace' => $mainItems, 'Account & Tools' => $secondaryItems] as $section => $items)
                <p class="px-3 pt-4 pb-2 text-xs font-semibold uppercase tracking-wider text-ink-500">{{ $section }}</p>
                @foreach($items as $item)
                    @php
                        $isActive = request()->routeIs($item['active'] ?? $item['route'] ?? '');
                    @endphp
                    <a 
                        href="{{ route($item['route']) }}" 
                        @click="open = false"
                        @if($isActive) aria-current="page" @endif
                        class="flex items-center justify-between min-h-11 px-3 py-2 rounded-lg text-sm font-semibold font-sans {{ $isActive ? 'bg-brand-50 text-brand-700 font-bold' : 'text-ink-700 hover:bg-surface-subtle' }}"
                    >
                        <span class="flex items-center gap-2.5">
                            <x-icon :name="$item['icon']" class="w-4 h-4 {{ $isActive ? 'text-brand-600' : 'text-ink-400' }}" />
                            <span>{{ $item['label'] }}</span>
                        </span>
                        @if(isset($item['badge']))
                            <span class="px-1.5 py-0.2 rounded text-xs sm:text-[10px] bg-brand-50 text-brand-700">
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
                @endforeach
            </nav>
        </div>

        <div class="pt-4 border-t border-border-default space-y-2">
            <a href="{{ route('design-system') }}" class="flex items-center gap-2 min-h-11 px-3 py-2 rounded-lg text-sm font-semibold text-brand-700 bg-brand-50">
                <x-icon name="sparkles" class="w-4 h-4" />
                <span>Design System Catalog</span>
            </a>
            <a href="{{ route('home') }}" class="flex items-center gap-2 min-h-11 px-3 py-2 rounded-lg text-sm font-semibold text-ink-600 hover:bg-surface-subtle">
                <x-icon name="arrow-left" class="w-4 h-4" />
                <span>Return to Public Portal</span>
            </a>
        </div>
    </div>
</div>

