@props([
    'contextBadge' => 'UniGuide Administration',
    'badgeType' => 'success',
    'userName' => 'Ahmad Raza Khan',
    'userRole' => 'Applicant #UG-2026-8841',
    'avatarText' => 'AR',
])

<header class="safe-area-top h-[calc(4rem+env(safe-area-inset-top))] shrink-0 bg-surface border-b border-border-default px-4 sm:px-6 flex items-center justify-between gap-4 sticky top-0 z-20">
    <!-- Left: Mobile Toggle & Context Badge -->
    <div class="flex items-center gap-3">
        <!-- Mobile Sidebar Trigger -->
        <button 
            type="button" 
            @click="$dispatch('toggle-mobile-sidebar')" 
            class="md:hidden p-3 rounded-lg text-ink-600 hover:text-ink-950 hover:bg-surface-subtle"
            aria-label="Open sidebar menu"
        >
            <x-icon name="menu" class="w-5 h-5" />
        </button>

        <!-- Authority / Context Badge -->
        <div class="hidden min-[1440px]:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold font-sans bg-surface-subtle border border-border-default text-ink-700">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>{{ $contextBadge }}</span>
        </div>
    </div>

    <!-- Center: Search input -->
    <div class="min-w-0 flex-1 max-w-md hidden md:block">
        <x-form.search id="topbar-search" placeholder="Quick search in this portal…" shortcut="⌘K" />
    </div>

    <!-- Right Actions -->
    <div class="flex items-center gap-2.5">
        <!-- Shell Switcher -->
        <x-ui.dropdown align="right" width="w-56">
            <x-slot:trigger>
                <button type="button" aria-label="Switch workspace" class="inline-flex min-h-11 items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-800 transition-smooth shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-brand-600"></span>
                    <span class="hidden sm:inline">Workspaces</span>
                    <x-icon name="chevron-down" class="w-3.5 h-3.5 text-ink-400" />
                </button>
            </x-slot:trigger>
            <x-slot:content>
                <div class="px-3 py-2 text-xs sm:text-[10px] font-semibold text-ink-400 uppercase tracking-wider font-sans border-b border-border-subtle">
                    Switch Workspace
                </div>
                <a href="{{ route('home') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle rounded-lg">
                    <x-icon name="compass" class="w-4 h-4 text-ink-400" />
                    <span>Explore UniGuide</span>
                </a>
                <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle rounded-lg">
                    <x-icon name="graduation-cap" class="w-4 h-4 text-ink-400" />
                    <span>Student Portal</span>
                </a>
                <a href="{{ route('uni_admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle rounded-lg">
                    <x-icon name="building-2" class="w-4 h-4 text-ink-400" />
                    <span>University Administration</span>
                </a>
                <a href="{{ route('national_admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-sans text-ink-700 hover:bg-surface-subtle rounded-lg">
                    <x-icon name="shield-check" class="w-4 h-4 text-ink-400" />
                    <span>National Administration</span>
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

        {{-- User Profile Dropdown --}}
        <div x-data="{ showSignOutModal: false }" class="relative">
            <x-ui.dropdown align="right" width="w-52">
                <x-slot:trigger>
                    <button type="button" aria-label="Open account menu" class="flex min-h-11 min-w-11 items-center justify-center gap-2 focus:outline-none">
                        <div class="w-8 h-8 rounded-lg bg-brand-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                            {{ $avatarText }}
                        </div>
                    </button>
                </x-slot:trigger>
                <x-slot:content>
                    <div class="px-3 py-2.5 border-b border-border-subtle">
                        <p class="text-xs font-bold text-ink-950 truncate font-heading">{{ $userName }}</p>
                        <p class="text-xs sm:text-[11px] text-ink-500 truncate font-sans">{{ $userRole }}</p>
                    </div>
                    <div class="p-1 space-y-0.5">
                        <a href="{{ route(request()->routeIs('uni_admin.*') ? 'uni_admin.settings' : (request()->routeIs('national_admin.*') ? 'national_admin.settings' : 'student.settings')) }}" class="flex min-h-11 items-center gap-2 px-2.5 py-1.5 text-xs text-ink-700 hover:bg-surface-subtle rounded-md">
                            <x-icon name="settings" class="w-4 h-4 text-ink-400" />
                            <span>Profile & Preferences</span>
                        </a>
                        @auth
                            <button type="button" @click="showSignOutModal = true" class="flex min-h-11 w-full items-center gap-2 rounded-md px-2.5 py-1.5 text-xs font-medium text-error-600 hover:bg-error-50 transition-colors">
                                <x-icon name="log-out" class="size-4" /><span>Sign Out</span>
                            </button>
                        @else
                            <a href="{{ route('auth.login') }}" class="flex min-h-11 items-center gap-2 rounded-md px-2.5 text-xs text-brand-700 hover:bg-brand-50">Sign In</a>
                        @endauth
                    </div>
                </x-slot:content>
            </x-ui.dropdown>

            @auth
            {{-- Sign Out Web Confirmation Dialog --}}
            <template x-teleport="body">
                <div 
                    x-show="showSignOutModal" 
                    x-cloak 
                    class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 md:py-12"
                    role="dialog" 
                    aria-modal="true" 
                    aria-labelledby="signout-modal-title"
                    @keydown.escape.window="showSignOutModal = false"
                >
                    <div x-show="showSignOutModal" x-transition.opacity class="fixed inset-0 bg-ink-950/40 backdrop-blur-xs" @click="showSignOutModal = false"></div>
                    <div class="flex min-h-full items-center justify-center p-2 text-center sm:p-0">
                        <div x-show="showSignOutModal" x-transition class="relative transform overflow-hidden rounded-2xl bg-surface border border-border-default text-left shadow-modal w-full max-w-md p-6" @click.stop>
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-amber-50 text-amber-600 border border-amber-200">
                                    <x-icon name="log-out" class="w-5 h-5" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-base font-heading font-bold text-ink-950" id="signout-modal-title">Sign Out of UniGuide?</h3>
                                    <p class="mt-1 text-xs text-ink-600 font-sans leading-relaxed">
                                        Are you sure you want to end your active session? Your saved universities, programs, and application tracker items will remain securely saved with your profile.
                                    </p>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-border-subtle">
                                <button type="button" @click="showSignOutModal = false" class="inline-flex min-h-11 items-center justify-center px-4 py-2 text-xs font-semibold text-ink-700 hover:bg-surface-subtle rounded-lg border border-border-default transition-colors">
                                    Stay Signed In
                                </button>
                                <form action="{{ route('auth.logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-white bg-error-600 hover:bg-error-700 active:bg-error-800 rounded-lg shadow-xs transition-colors">
                                        <x-icon name="log-out" class="size-4" />
                                        <span>Yes, Sign Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
            @endauth
        </div>
    </div>
</header>

