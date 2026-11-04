@props([
    'menuKey' => 'student', // student, university_admin, national_admin
    'roleLabel' => 'Student Portal',
    'userName' => 'Ahmad Raza Khan',
    'userRole' => 'FSc Pre-Engineering',
    'userAvatar' => 'AR',
])

@php
    $menuData = config("navigation.$menuKey", []);
    $mainItems = $menuData['main'] ?? [];
    $secondaryItems = $menuData['secondary'] ?? [];
@endphp

<aside 
    x-data="{ collapsed: false }" 
    :class="collapsed ? 'w-20' : 'w-64'"
    class="hidden md:flex flex-col shrink-0 bg-surface border-r border-border-default h-screen sticky top-0 transition-all duration-300 z-30 select-none"
>
    <!-- Top Branding & Shell Identity -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-border-default shrink-0">
        <a href="{{ route('preview.public') }}" class="flex items-center gap-2.5 overflow-hidden focus:outline-none">
            <x-brand.logo size="sm" />
            <div x-show="!collapsed" x-cloak class="flex flex-col whitespace-nowrap">
                <span class="font-heading font-extrabold text-sm text-ink-950 tracking-tight leading-none">
                    Uni<span class="text-brand-600">Guide</span>
                </span>
                <span class="text-[10px] font-semibold text-brand-700 tracking-wider uppercase font-sans mt-0.5">
                    {{ $roleLabel }}
                </span>
            </div>
        </a>

        <!-- Collapse Toggle -->
        <button 
            type="button" 
            @click="collapsed = !collapsed"
            class="text-ink-400 hover:text-ink-700 p-1 rounded-md hover:bg-surface-subtle transition-colors"
            :title="collapsed ? 'Expand sidebar' : 'Collapse sidebar'"
            aria-label="Toggle sidebar width"
        >
            <x-icon name="chevron-left" class="w-4 h-4 transition-transform duration-200" ::class="collapsed ? 'rotate-180' : ''" />
        </button>
    </div>

    <!-- Scrollable Nav List -->
    <div class="flex-1 overflow-y-auto p-3 space-y-6">
        <!-- Main Section -->
        <div>
            <div x-show="!collapsed" class="px-2 mb-2 text-[10px] font-bold text-ink-400 uppercase tracking-wider font-sans">
                Main Workspace
            </div>
            <nav class="space-y-1" aria-label="Sidebar Main Navigation">
                @foreach($mainItems as $item)
                    @php
                        $isActive = request()->routeIs($item['active'] ?? $item['route'] ?? '');
                        $badgeColor = match($item['badge_color'] ?? '') {
                            'success' => 'bg-success-50 text-success-700 border-success-200',
                            'warning' => 'bg-warning-50 text-warning-700 border-warning-200',
                            'error' => 'bg-error-50 text-error-700 border-error-200',
                            default => 'bg-brand-50 text-brand-700 border-brand-200',
                        };
                    @endphp
                    <a 
                        href="{{ route($item['route']) }}" 
                        :title="collapsed ? '{{ $item['label'] }}' : ''"
                        class="flex items-center justify-between px-2.5 py-2 rounded-xl text-xs font-semibold font-sans transition-all duration-150 {{ $isActive ? 'bg-brand-50 text-brand-700 font-bold shadow-2xs' : 'text-ink-600 hover:text-ink-950 hover:bg-surface-subtle' }}"
                    >
                        <div class="flex items-center gap-2.5 min-w-0">
                            <x-icon :name="$item['icon']" class="w-4.5 h-4.5 shrink-0 {{ $isActive ? 'text-brand-600' : 'text-ink-400' }}" />
                            <span x-show="!collapsed" class="truncate">{{ $item['label'] }}</span>
                        </div>

                        @if(isset($item['badge']))
                            <span 
                                x-show="!collapsed" 
                                class="px-1.5 py-0.2 rounded-full text-[10px] font-mono font-bold border {{ $badgeColor }}"
                            >
                                {{ $item['badge'] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        <!-- Secondary Section -->
        @if(count($secondaryItems) > 0)
            <div>
                <div x-show="!collapsed" class="px-2 mb-2 text-[10px] font-bold text-ink-400 uppercase tracking-wider font-sans">
                    Governance & Tools
                </div>
                <nav class="space-y-1" aria-label="Sidebar Secondary Navigation">
                    @foreach($secondaryItems as $item)
                        @php
                            $isActive = request()->routeIs($item['active'] ?? $item['route'] ?? '');
                            $badgeColor = match($item['badge_color'] ?? '') {
                                'success' => 'bg-success-50 text-success-700 border-success-200',
                                'warning' => 'bg-warning-50 text-warning-700 border-warning-200',
                                'error' => 'bg-error-50 text-error-700 border-error-200',
                                default => 'bg-brand-50 text-brand-700 border-brand-200',
                            };
                        @endphp
                        <a 
                            href="{{ route($item['route']) }}" 
                            :title="collapsed ? '{{ $item['label'] }}' : ''"
                            class="flex items-center justify-between px-2.5 py-2 rounded-xl text-xs font-semibold font-sans transition-all duration-150 {{ $isActive ? 'bg-brand-50 text-brand-700 font-bold shadow-2xs' : 'text-ink-600 hover:text-ink-950 hover:bg-surface-subtle' }}"
                        >
                            <div class="flex items-center gap-2.5 min-w-0">
                                <x-icon :name="$item['icon']" class="w-4.5 h-4.5 shrink-0 {{ $isActive ? 'text-brand-600' : 'text-ink-400' }}" />
                                <span x-show="!collapsed" class="truncate">{{ $item['label'] }}</span>
                            </div>

                            @if(isset($item['badge']))
                                <span 
                                    x-show="!collapsed" 
                                    class="px-1.5 py-0.2 rounded-full text-[10px] font-mono font-bold border {{ $badgeColor }}"
                                >
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>
        @endif
    </div>

    <!-- User Profile Footer -->
    <div class="p-3 border-t border-border-default shrink-0 bg-surface-subtle/50">
        <div class="flex items-center gap-3 p-1 rounded-xl">
            <div class="w-9 h-9 rounded-xl bg-brand-600 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-xs">
                {{ $userAvatar }}
            </div>
            <div x-show="!collapsed" class="flex-1 min-w-0">
                <p class="text-xs font-bold text-ink-950 truncate font-heading">{{ $userName }}</p>
                <p class="text-[11px] text-ink-500 truncate font-sans">{{ $userRole }}</p>
            </div>
        </div>
    </div>
</aside>
