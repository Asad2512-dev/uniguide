<header 
    x-data="{ mobileMenuOpen: false }"
    x-init="$watch('mobileMenuOpen', v => document.body.classList.toggle('overflow-hidden', v))"
    @keydown.escape.window="mobileMenuOpen = false"
    class="sticky top-0 z-40 w-full glass-header border-b border-border-default/80 transition-all duration-200"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-18">
            <!-- Brand Wordmark -->
            <div class="flex items-center gap-8">
                <x-brand.wordmark size="md" :href="route('home')" />

                <!-- Desktop Public Links -->
                <nav class="hidden lg:flex items-center space-x-1" aria-label="Main Navigation">
                    @foreach(config('navigation.public', []) as $item)
                        @php
                            $targetUrl = route($item['route']) . (!empty($item['fragment']) ? '#' . $item['fragment'] : '');
                            $isActive = request()->routeIs($item['route']);
                        @endphp
                        <a 
                            href="{{ $targetUrl }}" 
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold font-sans transition-smooth {{ $isActive ? 'text-brand-700 bg-brand-50 shadow-2xs' : 'text-ink-700 hover:text-brand-600 hover:bg-surface-subtle' }}"
                        >
                            <x-icon :name="$item['icon']" class="w-4 h-4 {{ $isActive ? 'text-brand-600' : 'text-ink-400' }}" />
                            <span>{{ $item['label'] }}</span>
                            @if(isset($item['badge']))
                                <span class="px-1.5 py-0.2 rounded text-[10px] font-mono font-bold bg-brand-50 text-brand-700 border border-brand-200/60">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>

            <!-- Right Actions: Search trigger, Portal Switchers, CTAs -->
            <div class="flex items-center gap-2.5">
                <!-- Global Quick Search Trigger -->
                <button 
                    type="button" 
                    @click="$store.commandPalette.toggle()"
                    class="hidden sm:inline-flex items-center gap-2.5 px-3 py-1.5 rounded-lg border border-border-default bg-surface hover:bg-surface-subtle text-xs font-sans text-ink-500 shadow-2xs transition-smooth cursor-pointer"
                    aria-label="Search universities and programs (Press Cmd+K)"
                >
                    <x-icon name="search" class="w-3.5 h-3.5 text-ink-400" />
                    <span>Search portal…</span>
                    <kbd class="px-1.5 py-0.5 text-[10px] font-mono font-medium text-ink-400 bg-surface-subtle border border-border-default rounded">
                        ⌘K
                    </kbd>
                </button>

                <!-- Shell / Role Quick Preview Dropdown -->
                <x-ui.dropdown align="right" width="w-60">
                    <x-slot:trigger>
                        <button 
                            type="button" 
                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-border-default bg-surface text-xs font-semibold text-ink-800 hover:bg-surface-subtle transition-smooth shadow-2xs cursor-pointer"
                        >
                            <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                            <span class="hidden md:inline">Portal Views</span>
                            <x-icon name="chevron-down" class="w-3.5 h-3.5 text-ink-400" />
                        </button>
                    </x-slot:trigger>
                    <x-slot:content>
                        <div class="px-3 py-2 text-[10px] font-semibold text-ink-400 uppercase tracking-wider font-sans border-b border-border-subtle">
                            Switch Shell Workspace
                        </div>
                        <a href="{{ route('preview.public') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle hover:text-brand-600 rounded-lg">
                            <x-icon name="compass" class="w-4 h-4 text-ink-400" />
                            <span>Public Discovery Shell</span>
                        </a>
                        <a href="{{ route('preview.student') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle hover:text-brand-600 rounded-lg">
                            <x-icon name="graduation-cap" class="w-4 h-4 text-ink-400" />
                            <span>Student Dashboard Shell</span>
                        </a>
                        <a href="{{ route('preview.university_admin') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle hover:text-brand-600 rounded-lg">
                            <x-icon name="building-2" class="w-4 h-4 text-ink-400" />
                            <span>University Admin Shell</span>
                        </a>
                        <a href="{{ route('preview.national_admin') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle hover:text-brand-600 rounded-lg">
                            <x-icon name="shield-check" class="w-4 h-4 text-ink-400" />
                            <span>HEC National Command Shell</span>
                        </a>
                        <div class="my-1 border-t border-border-subtle"></div>
                        <a href="{{ route('design-system') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans font-semibold text-brand-700 hover:bg-brand-50 rounded-lg">
                            <x-icon name="sparkles" class="w-4 h-4 text-brand-600" />
                            <span>Design System Showcase</span>
                        </a>
                    </x-slot:content>
                </x-ui.dropdown>

                <!-- Sign In / Get Started -->
                <div class="hidden sm:flex items-center gap-2">
                    <x-ui.button variant="ghost" size="sm" :href="route('auth.login')">
                        Sign In
                    </x-ui.button>
                    <x-ui.button variant="primary" size="sm" :href="route('auth.register')" iconRight="arrow-right">
                        Create Account
                    </x-ui.button>
                </div>

                <!-- Mobile Hamburger Toggle -->
                <button 
                    type="button" 
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    :aria-expanded="mobileMenuOpen.toString()"
                    aria-controls="mobile-nav-drawer"
                    class="lg:hidden p-2 rounded-lg text-ink-600 hover:text-ink-950 hover:bg-surface-subtle transition-colors cursor-pointer"
                    aria-label="Toggle navigation menu"
                >
                    <x-icon name="menu" class="w-5 h-5" />
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Drawer Overlay -->
    <div 
        id="mobile-nav-drawer"
        role="dialog"
        aria-modal="true"
        aria-label="Mobile Navigation"
        x-show="mobileMenuOpen" 
        x-cloak 
        class="lg:hidden fixed inset-0 z-50 bg-ink-950/40 backdrop-blur-xs" 
        @click="mobileMenuOpen = false"
    >
        <div 
            class="fixed inset-y-0 right-0 w-80 max-w-full bg-surface p-6 shadow-modal flex flex-col justify-between"
            @click.stop
        >
            <div class="space-y-6">
                <div class="flex items-center justify-between pb-4 border-b border-border-default">
                    <x-brand.wordmark size="sm" :href="route('home')" />
                    <button type="button" @click="mobileMenuOpen = false" class="p-1 text-ink-400 hover:text-ink-700 cursor-pointer" aria-label="Close mobile navigation menu">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <div class="space-y-1">
                    @foreach(config('navigation.public', []) as $item)
                        @php
                            $targetUrl = route($item['route']) . (!empty($item['fragment']) ? '#' . $item['fragment'] : '');
                            $isActive = request()->routeIs($item['route']);
                        @endphp
                        <a 
                            href="{{ $targetUrl }}" 
                            @click="mobileMenuOpen = false"
                            class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-semibold font-sans transition-colors {{ $isActive ? 'bg-brand-50 text-brand-700 font-bold' : 'text-ink-800 hover:bg-surface-subtle hover:text-brand-600' }}"
                        >
                            <span class="flex items-center gap-2.5">
                                <x-icon :name="$item['icon']" class="w-4 h-4 {{ $isActive ? 'text-brand-600' : 'text-ink-400' }}" />
                                <span>{{ $item['label'] }}</span>
                            </span>
                            @if(isset($item['badge']))
                                <span class="px-1.5 py-0.2 rounded text-[10px] font-mono bg-brand-50 text-brand-700">
                                    {{ $item['badge'] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-2 pt-6 border-t border-border-default">
                <x-ui.button variant="outline" size="md" :href="route('auth.login')" class="w-full">
                    Student & Admin Login
                </x-ui.button>
                <x-ui.button variant="primary" size="md" :href="route('auth.register')" class="w-full">
                    Register for Free
                </x-ui.button>
            </div>
        </div>
    </div>
</header>
