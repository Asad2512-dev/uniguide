@php
    $publicItems = collect(config('navigation.public', []));
    $mobileGroups = [
        'Explore' => $publicItems->whereIn('route', ['universities.index', 'programs.index']),
        'Admissions' => collect([
            ['label' => 'Admission Explorer', 'route' => 'admissions.index', 'icon' => 'calendar-clock'],
            ['label' => 'Admission Calendar', 'route' => 'admissions.calendar', 'icon' => 'calendar'],
            ['label' => 'Scholarships', 'route' => 'scholarships.index', 'icon' => 'award'],
            ['label' => 'Document Checklist', 'route' => 'admissions.checklist', 'icon' => 'list-checks'],
        ]),
        'Intelligent Tools' => $publicItems->whereIn('route', ['counselor.index', 'compare.index', 'eligibility.index']),
        'Platform' => $publicItems->whereIn('route', ['marketing.how_it_works', 'marketing.features', 'marketing.about'])->push(['label' => 'Contact', 'route' => 'marketing.contact', 'icon' => 'mail']),
    ];
    $groups = [
        'Explore' => $publicItems->whereIn('route', ['universities.index', 'programs.index']),
        'Admissions' => collect([
            ['label' => 'Admission Explorer', 'route' => 'admissions.index', 'icon' => 'calendar-clock'],
            ['label' => 'Admission Calendar', 'route' => 'admissions.calendar', 'icon' => 'calendar'],
            ['label' => 'Admission Checklist', 'route' => 'admissions.checklist', 'icon' => 'list-checks'],
        ]),
        'More' => $publicItems->whereNotIn('route', ['universities.index', 'programs.index', 'admissions.index', 'scholarships.index']),
    ];
@endphp

<header x-data="{ mobileMenuOpen: false }" @keydown.escape.window="mobileMenuOpen = false"
    @resize.window="if (window.innerWidth >= 1152) mobileMenuOpen = false"
    class="public-header sticky top-0 z-40 w-full glass-header border-b border-border-default/80">
    <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 sm:h-18 items-center justify-between gap-4">
            <x-brand.wordmark size="md" :href="route('home')" :tagline="false" class="shrink-0" />
            <nav class="hidden min-[1152px]:flex items-center gap-1" aria-label="Main Navigation">
                @foreach($groups as $label => $items)
                    @if($label === 'More')
                        <a href="{{ route('scholarships.index') }}" @if(request()->routeIs('scholarships.*')) aria-current="page" @endif class="public-nav-link">{{ content('navigation.public.scholarships.label', 'Scholarships') }}</a>
                        <a href="{{ route('compare.index') }}" @if(request()->routeIs('compare.*')) aria-current="page" @endif class="public-nav-link hidden min-[1440px]:inline-flex">{{ content('navigation.public.compare.label', 'Compare') }}</a>
                        <a href="{{ route('counselor.index') }}" @if(request()->routeIs('counselor.*')) aria-current="page" @endif class="public-nav-link hidden min-[1440px]:inline-flex">{{ content('navigation.public.counselor.label', 'AI Counselor') }}</a>
                    @endif
                    <x-ui.dropdown align="left" width="w-72">
                        <x-slot:trigger>
                            <button type="button" class="public-nav-link {{ $items->contains(fn ($item) => request()->routeIs(strtok($item['route'], '.') . '.*')) ? 'bg-brand-50 text-brand-700' : '' }}">
                                {{ $label }}<x-icon name="chevron-down" class="size-4 shrink-0" />
                            </button>
                        </x-slot:trigger>
                        <x-slot:content>
                            @foreach($items as $item)
                                <a href="{{ route($item['route']) }}" @if(request()->routeIs($item['route'])) aria-current="page" @endif
                                    class="flex min-h-11 items-center gap-3 rounded-lg px-3 py-2 text-sm text-ink-700 hover:bg-brand-50 hover:text-brand-700 {{ $label === 'More' && in_array($item['route'], ['compare.index', 'counselor.index']) ? 'min-[1440px]:hidden' : '' }}">
                                    <x-icon :name="$item['icon']" class="size-4 shrink-0 text-brand-600" />
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </x-slot:content>
                    </x-ui.dropdown>
                @endforeach
            </nav>
            <div class="flex shrink-0 items-center gap-2">
                <button type="button" @click="$store.commandPalette.toggle()" class="inline-flex sm:hidden min-[1440px]:inline-flex size-11 items-center justify-center rounded-lg text-ink-600 hover:bg-surface-subtle" aria-label="Search universities and programs">
                    <x-icon name="search" class="size-5" />
                </button>
                <div class="hidden md:flex"><x-ui.button variant="ghost" :href="route('auth.login')" class="min-h-11">{{ content('global.nav.sign_in', 'Sign In') }}</x-ui.button></div>
                <div class="hidden md:flex"><x-ui.button :href="route('auth.register')" class="min-h-11">{{ content('global.nav.get_started', 'Get Started') }}</x-ui.button></div>
                <button type="button" @click="mobileMenuOpen = true" :aria-expanded="mobileMenuOpen.toString()" aria-controls="mobile-nav-drawer" class="min-[1152px]:hidden inline-flex size-11 items-center justify-center rounded-lg text-ink-700 hover:bg-surface-subtle" aria-label="Open navigation menu">
                    <x-icon name="menu" class="size-5" />
                </button>
            </div>
        </div>
    </div>
    <template x-teleport="body">
    <div id="mobile-nav-drawer" role="dialog" aria-modal="true" aria-label="Public navigation" x-show="mobileMenuOpen" x-modal-focus="mobileMenuOpen" x-cloak class="fixed inset-0 z-50 bg-ink-950/40 backdrop-blur-xs" @click.self="mobileMenuOpen = false">
        <div class="absolute inset-y-0 left-0 flex w-96 max-w-full flex-col bg-surface safe-area-top shadow-modal">
            <div class="flex shrink-0 items-center justify-between border-b border-border-default p-4">
                <x-brand.wordmark size="sm" :href="route('home')" :tagline="false" />
                <button type="button" @click="mobileMenuOpen = false" class="inline-flex size-11 items-center justify-center rounded-lg text-ink-600 hover:bg-surface-subtle" aria-label="Close navigation menu"><x-icon name="x" class="size-5" /></button>
            </div>
            <nav class="min-h-0 flex-1 overflow-y-auto overscroll-contain p-4" aria-label="Mobile public navigation">
                <button type="button" @click="mobileMenuOpen = false; $nextTick(() => $store.commandPalette.toggle())" class="mb-4 flex min-h-11 w-full items-center gap-3 rounded-lg border border-border-default px-3 text-sm text-ink-600"><x-icon name="search" class="size-4" />{{ content('global.nav.search_placeholder', 'Search universities and programs') }}</button>
                @foreach($mobileGroups as $section => $items)
                    <p class="mb-2 mt-4 px-3 text-xs font-semibold uppercase tracking-wider text-ink-500">{{ $section }}</p>
                    @foreach($items as $item)
                        <a href="{{ route($item['route']) }}" @if(request()->routeIs($item['route'])) aria-current="page" @endif class="flex min-h-11 items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-ink-700 hover:bg-brand-50 hover:text-brand-700">
                            <x-icon :name="$item['icon']" class="size-5 shrink-0 text-brand-600" /><span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                @endforeach
            </nav>
            <div class="grid shrink-0 grid-cols-2 gap-3 border-t border-border-default p-4 [--safe-bottom-padding:1rem] safe-area-bottom">
                <x-ui.button variant="outline" :href="route('auth.login')" class="min-h-11">{{ content('global.nav.sign_in', 'Sign In') }}</x-ui.button>
                <x-ui.button :href="route('auth.register')" class="min-h-11">{{ content('global.nav.get_started', 'Get Started') }}</x-ui.button>
            </div>
        </div>
    </div>
    </template>
</header>
