@props([
    'contextBadge' => 'HEC National Verified Platform',
    'badgeType' => 'success',
    'userName' => 'Ahmad Raza Khan',
    'userRole' => 'Applicant #UG-2026-8841',
    'avatarText' => 'AR',
])

<header class="h-16 bg-surface border-b border-border-default px-4 sm:px-6 flex items-center justify-between gap-4 sticky top-0 z-20">
    <!-- Left: Mobile Toggle & Context Badge -->
    <div class="flex items-center gap-3">
        <!-- Mobile Sidebar Trigger -->
        <button 
            type="button" 
            @click="$dispatch('toggle-mobile-sidebar')" 
            class="md:hidden p-2 rounded-lg text-ink-600 hover:text-ink-950 hover:bg-surface-subtle"
            aria-label="Open sidebar menu"
        >
            <x-icon name="menu" class="w-5 h-5" />
        </button>

        <!-- Authority / Context Badge -->
        <div class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold font-sans bg-surface-subtle border border-border-default text-ink-700">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>{{ $contextBadge }}</span>
        </div>
    </div>

    <!-- Center: Search input -->
    <div class="flex-1 max-w-md hidden md:block">
        <x-form.search id="topbar-search" placeholder="Quick search in this portal…" shortcut="⌘K" />
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-2.5">
        <!-- Shell Switcher -->
        <x-ui.dropdown align="right" width="w-56">
            <x-slot:trigger>
                <button type="button" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-800 transition-smooth shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                    <span class="hidden sm:inline">Role Shells</span>
                    <x-icon name="chevron-down" class="w-3.5 h-3.5 text-ink-400" />
                </button>
            </x-slot:trigger>
            <x-slot:content>
                <div class="px-3 py-2 text-[10px] font-semibold text-ink-400 uppercase tracking-wider font-sans border-b border-border-subtle">
                    Switch Active Shell
                </div>
                <a href="{{ route('preview.public') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle rounded-lg">
                    <x-icon name="compass" class="w-4 h-4 text-ink-400" />
                    <span>Public Discovery Shell</span>
                </a>
                <a href="{{ route('preview.student') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle rounded-lg">
                    <x-icon name="graduation-cap" class="w-4 h-4 text-ink-400" />
                    <span>Student Portal Shell</span>
                </a>
                <a href="{{ route('preview.university_admin') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle rounded-lg">
                    <x-icon name="building-2" class="w-4 h-4 text-ink-400" />
                    <span>University Admin Shell</span>
                </a>
                <a href="{{ route('preview.national_admin') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle rounded-lg">
                    <x-icon name="shield-check" class="w-4 h-4 text-ink-400" />
                    <span>HEC National Command Shell</span>
                </a>
                <div class="my-1 border-t border-border-subtle"></div>
                <a href="{{ route('design-system') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans font-semibold text-brand-700 hover:bg-brand-50 rounded-lg">
                    <x-icon name="sparkles" class="w-4 h-4 text-brand-600" />
                    <span>Design System Catalog</span>
                </a>
            </x-slot:content>
        </x-ui.dropdown>

        <!-- Notification Bell -->
        <button 
            type="button" 
            @click="$store.toasts.add({ title: 'Admissions Alert', message: 'NUST Merit list has been declared for Engineering programs.', type: 'info' })"
            class="relative p-2 rounded-lg text-ink-600 hover:text-ink-950 hover:bg-surface-subtle transition-colors"
            title="Notifications"
            aria-label="View notifications"
        >
            <x-icon name="bell" class="w-5 h-5" />
            <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-warning-500 ring-2 ring-surface"></span>
        </button>

        <!-- User Profile Dropdown -->
        <x-ui.dropdown align="right" width="w-52">
            <x-slot:trigger>
                <button type="button" class="flex items-center gap-2 focus:outline-none">
                    <div class="w-8 h-8 rounded-lg bg-brand-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                        {{ $avatarText }}
                    </div>
                </button>
            </x-slot:trigger>
            <x-slot:content>
                <div class="px-3 py-2.5 border-b border-border-subtle">
                    <p class="text-xs font-bold text-ink-950 truncate font-heading">{{ $userName }}</p>
                    <p class="text-[11px] text-ink-500 truncate font-sans">{{ $userRole }}</p>
                </div>
                <div class="p-1 space-y-0.5">
                    <a href="{{ route('design-system') }}" class="flex items-center gap-2 px-2.5 py-1.5 text-xs text-ink-700 hover:bg-surface-subtle rounded-md">
                        <x-icon name="settings" class="w-4 h-4 text-ink-400" />
                        <span>Profile & Preferences</span>
                    </a>
                    <a href="{{ route('preview.public') }}" class="flex items-center gap-2 px-2.5 py-1.5 text-xs text-error-600 hover:bg-error-50 rounded-md">
                        <x-icon name="arrow-left" class="w-4 h-4 text-error-500" />
                        <span>Sign Out</span>
                    </a>
                </div>
            </x-slot:content>
        </x-ui.dropdown>
    </div>
</header>
