<x-layouts.public 
    title="Admission Explorer — UniGuide Pakistan"
    description="Discover active Pakistani university admissions, application deadlines, national entry test schedules, and verified intake sessions."
>
    <div x-data="{ 
        mobileFilterOpen: false,
        activeStatus: '{{ $filters['status'] }}',
        activeProvince: '{{ $filters['province'] }}',
        activeDegreeLevel: '{{ $filters['degree_level'] }}'
    }" class="min-h-screen bg-canvas pb-20">
        
        <!-- Header Banner & Tools Strip -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumb -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Admissions', 'url' => route('admissions.index')]
                    ]" />
                </div>

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-brand-50 border border-brand-200/60 text-brand-700 text-xs font-semibold font-sans mb-3">
                            <x-icon name="calendar-clock" class="w-3.5 h-3.5 text-brand-600" />
                            <span>National Intake Intelligence • Fall 2026</span>
                        </div>
                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                            National University Admission Explorer
                        </h1>
                        <p class="text-sm sm:text-base text-ink-600 font-sans max-w-2xl mt-1.5 leading-relaxed">
                            Track verified admission campaigns, registration deadlines, and entrance test criteria across Punjab and Islamabad’s top public and chartered institutions.
                        </p>
                    </div>

                    <!-- Direct Shortcuts: Calendar & Checklist -->
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a 
                            href="{{ route('admissions.calendar') }}" 
                            class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-800 font-sans shadow-2xs transition-smooth"
                        >
                            <x-icon name="calendar" class="w-4 h-4 text-brand-600" />
                            <span>Deadline Calendar</span>
                        </a>

                        <a 
                            href="{{ route('admissions.checklist') }}" 
                            class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl border border-brand-200/70 bg-brand-50 hover:bg-brand-100 text-xs font-semibold text-brand-700 font-sans shadow-2xs transition-smooth"
                        >
                            <x-icon name="clipboard-check" class="w-4 h-4 text-brand-600" />
                            <span>Document Checklist</span>
                        </a>
                    </div>
                </div>

                <!-- Primary Search Bar & Status Quick Tabs -->
                <div class="mt-8">
                    <form action="{{ route('admissions.index') }}" method="GET" class="relative">
                        @if($filters['province'] !== 'all') <input type="hidden" name="province" value="{{ $filters['province'] }}" /> @endif
                        @if($filters['degree_level'] !== 'all') <input type="hidden" name="degree_level" value="{{ $filters['degree_level'] }}" /> @endif
                        @if($filters['session'] !== 'all') <input type="hidden" name="session" value="{{ $filters['session'] }}" /> @endif
                        @if($filters['status'] !== 'all') <input type="hidden" name="status" value="{{ $filters['status'] }}" /> @endif

                        <div class="relative flex items-center">
                            <x-icon name="search" class="w-5 h-5 text-ink-400 absolute left-4.5 pointer-events-none" />
                            <input 
                                type="text" 
                                name="q" 
                                value="{{ $filters['q'] }}" 
                                placeholder="Search by university (NUST, FAST, LUMS), entrance test (NET, NAT, MDCAT), or city…"
                                class="w-full bg-canvas/80 border border-border-default rounded-2xl pl-13 pr-32 py-3.5 text-sm sm:text-base text-ink-900 placeholder:text-ink-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 font-sans shadow-xs transition-smooth"
                            />
                            <div class="absolute right-2.5 flex items-center gap-2">
                                @if(!empty($filters['q']))
                                    <a 
                                        href="{{ route('admissions.index', array_merge(request()->except(['q', 'page']))) }}" 
                                        class="p-1.5 text-ink-400 hover:text-ink-600 rounded-lg hover:bg-surface-subtle"
                                        title="Clear search"
                                    >
                                        <x-icon name="x" class="w-4 h-4" />
                                    </a>
                                @endif
                                <button 
                                    type="submit" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 text-white font-sans text-xs font-semibold hover:bg-brand-700 transition-smooth shadow-xs cursor-pointer"
                                >
                                    <span>Filter</span>
                                    <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Status Filter Tabs -->
                    <div class="flex items-center gap-2 mt-4 overflow-x-auto pb-1 text-xs font-sans font-semibold">
                        <a 
                            href="{{ route('admissions.index', array_merge(request()->except(['status', 'page']))) }}" 
                            class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap {{ $filters['status'] === 'all' ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                        >
                            All Campaigns
                        </a>
                        <a 
                            href="{{ route('admissions.index', array_merge(request()->except(['status', 'page']), ['status' => 'open'])) }}" 
                            class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap flex items-center gap-1.5 {{ $filters['status'] === 'open' ? 'bg-success-600 text-white border-success-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                        >
                            <span class="w-2 h-2 rounded-full {{ $filters['status'] === 'open' ? 'bg-white' : 'bg-success-500' }}"></span>
                            <span>Admissions Open</span>
                        </a>
                        <a 
                            href="{{ route('admissions.index', array_merge(request()->except(['status', 'page']), ['status' => 'closing_soon'])) }}" 
                            class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap flex items-center gap-1.5 {{ $filters['status'] === 'closing_soon' ? 'bg-warning-600 text-white border-warning-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                        >
                            <span class="w-2 h-2 rounded-full {{ $filters['status'] === 'closing_soon' ? 'bg-white' : 'bg-warning-500' }}"></span>
                            <span>Closing Soon</span>
                        </a>
                        <a 
                            href="{{ route('admissions.index', array_merge(request()->except(['status', 'page']), ['status' => 'merit_announced'])) }}" 
                            class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap {{ $filters['status'] === 'merit_announced' ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                        >
                            <span>Merit Lists Active</span>
                        </a>
                        <a 
                            href="{{ route('admissions.index', array_merge(request()->except(['status', 'page']), ['status' => 'upcoming'])) }}" 
                            class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap {{ $filters['status'] === 'upcoming' ? 'bg-ink-800 text-white border-ink-800 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                        >
                            <span>Upcoming Intakes</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Body: Desktop Filter Sidebar + Results Viewport -->
        <main class="landing-container mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Desktop Sidebar Filters -->
                <aside class="hidden lg:block lg:col-span-1 space-y-6">
                    <form action="{{ route('admissions.index') }}" method="GET" id="desktop-adm-filter-form">
                        @if(!empty($filters['q'])) <input type="hidden" name="q" value="{{ $filters['q'] }}" /> @endif

                        <div class="bg-surface rounded-2xl border border-border-default p-5 space-y-6 shadow-2xs">
                            <div class="flex items-center justify-between border-b border-border-subtle pb-3.5">
                                <span class="font-heading font-bold text-sm text-ink-900 flex items-center gap-2">
                                    <x-icon name="sliders-horizontal" class="w-4 h-4 text-brand-600" />
                                    Filter Admissions
                                </span>
                                @if(!empty($appliedFilters))
                                    <a 
                                        href="{{ route('admissions.index') }}" 
                                        class="text-xs text-brand-600 hover:text-brand-700 font-semibold"
                                    >
                                        Reset All
                                    </a>
                                @endif
                            </div>

                            <!-- Admission Status -->
                            <div>
                                <label for="filter-status" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Admission Status
                                </label>
                                <select 
                                    id="filter-status" 
                                    name="status" 
                                    onchange="document.getElementById('desktop-adm-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['statuses'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['status'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Province / Territory -->
                            <div>
                                <label for="filter-province" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Province / Territory
                                </label>
                                <select 
                                    id="filter-province" 
                                    name="province" 
                                    onchange="document.getElementById('desktop-adm-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['provinces'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['province'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Degree Level -->
                            <div>
                                <label for="filter-degree-level" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Degree Level
                                </label>
                                <select 
                                    id="filter-degree-level" 
                                    name="degree_level" 
                                    onchange="document.getElementById('desktop-adm-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['degree_levels'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['degree_level'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Academic Session -->
                            <div>
                                <label for="filter-session" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Intake Session
                                </label>
                                <select 
                                    id="filter-session" 
                                    name="session" 
                                    onchange="document.getElementById('desktop-adm-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['sessions'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['session'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Offering University -->
                            <div>
                                <label for="filter-university" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Institution
                                </label>
                                <select 
                                    id="filter-university" 
                                    name="university" 
                                    onchange="document.getElementById('desktop-adm-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    <option value="all" {{ $filters['university'] === 'all' ? 'selected' : '' }}>All Institutions</option>
                                    @foreach($universities as $u)
                                        <option value="{{ $u['slug'] }}" {{ $filters['university'] === $u['slug'] ? 'selected' : '' }}>
                                            {{ $u['acronym'] }} ({{ $u['city'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Gazette Integrity Notice -->
                        <div class="mt-4 p-3.5 rounded-xl bg-surface-subtle border border-border-subtle text-xs sm:text-[11px] font-sans text-ink-500 space-y-1">
                            <div class="flex items-center gap-1.5 font-semibold text-ink-700">
                                <x-icon name="shield-check" class="w-3.5 h-3.5 text-brand-600" />
                                <span>Direct University Source Auditing</span>
                            </div>
                            <p class="leading-relaxed">
                                Deadlines and entry test schedules are cross-checked against official registrar notifications and newspaper advertisements.
                            </p>
                        </div>
                    </form>
                </aside>

                <!-- Results Viewport -->
                <section class="lg:col-span-3 space-y-6">
                    
                    <!-- Control Bar: Result Count, Mobile Trigger, Sort -->
                    <div class="bg-surface rounded-2xl border border-border-default p-4 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                @click="mobileFilterOpen = true"
                                class="lg:hidden inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-800 shadow-2xs"
                            >
                                <x-icon name="sliders-horizontal" class="w-4 h-4 text-brand-600" />
                                <span>Filters</span>
                                @if(count($appliedFilters) > 0)
                                    <span class="w-5 h-5 rounded-full bg-brand-600 text-white text-xs sm:text-[10px] flex items-center justify-center font-mono">
                                        {{ count($appliedFilters) }}
                                    </span>
                                @endif
                            </button>

                            <p class="text-xs sm:text-sm font-sans text-ink-600">
                                Showing <span class="font-bold text-ink-900">{{ $results['from'] }}–{{ $results['to'] }}</span> of <span class="font-bold text-ink-900">{{ $results['total'] }}</span> verified intake cycles
                            </p>
                        </div>

                        <!-- Sort Selector -->
                        <form action="{{ route('admissions.index') }}" method="GET" class="flex items-center gap-2 self-end sm:self-auto">
                            @foreach(request()->except(['sort', 'page']) as $k => $v)
                                @if(!empty($v) && $v !== 'all')
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                                @endif
                            @endforeach

                            <label for="sort-select" class="text-xs font-sans text-ink-500 whitespace-nowrap hidden sm:inline">Sort by:</label>
                            <select 
                                id="sort-select" 
                                name="sort" 
                                onchange="this.form.submit()"
                                class="text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-1.5 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                            >
                                <option value="deadline_asc" {{ $sort === 'deadline_asc' ? 'selected' : '' }}>Earliest Deadline</option>
                                <option value="deadline_desc" {{ $sort === 'deadline_desc' ? 'selected' : '' }}>Latest Deadline</option>
                                <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>Title (A–Z)</option>
                                <option value="university_asc" {{ $sort === 'university_asc' ? 'selected' : '' }}>University Name</option>
                            </select>
                        </form>
                    </div>

                    <!-- Applied Filter Chips -->
                    @if(!empty($appliedFilters))
                        <div class="flex items-center flex-wrap gap-2 pt-1">
                            <span class="text-xs text-ink-500 font-sans font-medium">Applied:</span>
                            @foreach($appliedFilters as $key => $chip)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-surface border border-border-default text-xs font-sans text-ink-800 shadow-2xs">
                                    <span>{{ $chip['label'] }}</span>
                                    <a 
                                        href="{{ route('admissions.index', array_merge(request()->except([$chip['param'], 'page']))) }}" 
                                        class="text-ink-400 hover:text-ink-700"
                                        title="Remove filter"
                                    >
                                        <x-icon name="x" class="w-3.5 h-3.5" />
                                    </a>
                                </span>
                            @endforeach
                            <a 
                                href="{{ route('admissions.index') }}" 
                                class="text-xs text-brand-600 hover:text-brand-700 font-semibold ml-1"
                            >
                                Clear all
                            </a>
                        </div>
                    @endif

                    <!-- Empty State -->
                    @if(empty($results['data']))
                        <div class="bg-surface rounded-2xl border border-border-default p-12 text-center shadow-2xs">
                            <div class="w-14 h-14 rounded-2xl bg-brand-50 border border-brand-200/60 flex items-center justify-center mx-auto mb-4 text-brand-600">
                                <x-icon name="calendar-x" class="w-7 h-7" />
                            </div>
                            <h3 class="font-heading font-bold text-lg text-ink-950 mb-1.5">No admissions found</h3>
                            <p class="text-sm font-sans text-ink-600 max-w-md mx-auto mb-6">
                                No admission campaigns match your current filters. Try selecting a broader status or clearing city constraints.
                            </p>
                            <x-ui.button variant="primary" size="sm" :href="route('admissions.index')">
                                Reset All Filters
                            </x-ui.button>
                        </div>
                    @else
                        <!-- Admission Campaign Cards -->
                        <div class="space-y-4">
                            @foreach($results['data'] as $adm)
                                <div class="bg-surface rounded-2xl border border-border-default hover:border-brand-300 hover:shadow-card p-4 sm:p-6 transition-smooth flex flex-col justify-between gap-5">
                                    <div>
                                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-3">
                                            <div>
                                                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                                    <span class="px-2 py-0.5 rounded text-xs sm:text-[10px] font-mono font-bold uppercase bg-brand-50 text-brand-700 border border-brand-200/60">
                                                        {{ $adm['session'] }}
                                                    </span>
                                                    <x-domain.admission-status :status="$adm['status']" size="sm" />
                                                    <span class="text-xs font-sans text-ink-500">
                                                        {{ $adm['province_label'] }}
                                                    </span>
                                                </div>

                                                <h3 class="font-heading font-bold text-base sm:text-lg text-ink-950 hover:text-brand-600 transition-colors">
                                                    <a href="{{ route('admissions.show', $adm['slug']) }}">
                                                        {{ $adm['title'] }}
                                                    </a>
                                                </h3>
                                                
                                                <p class="text-xs text-brand-700 font-semibold font-sans mt-0.5">
                                                    {{ $adm['university_name'] }} ({{ $adm['university_acronym'] }})
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-2 self-start sm:self-auto shrink-0">
                                                <div class="text-left sm:text-right">
                                                    <span class="block text-xs sm:text-[11px] font-sans text-ink-600 font-medium">Application Deadline</span>
                                                    <span class="font-heading font-extrabold text-sm text-ink-900 block">{{ $adm['deadline'] }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <p class="text-xs sm:text-sm text-ink-600 font-sans leading-relaxed mb-4">
                                            {{ $adm['summary'] }}
                                        </p>

                                        <!-- Meta Details Strip -->
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 p-3 rounded-xl bg-surface-subtle border border-border-subtle text-xs font-sans text-ink-600">
                                            <div>
                                                <span class="block text-xs sm:text-[10px] uppercase font-bold text-ink-600">Entry Test Required</span>
                                                <span class="font-semibold text-ink-900">{{ $adm['entry_test'] }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-xs sm:text-[10px] uppercase font-bold text-ink-600">Application Processing Fee</span>
                                                <span class="font-mono font-semibold text-ink-900">{{ $adm['application_fee'] }}</span>
                                            </div>
                                            <div>
                                                <span class="block text-xs sm:text-[10px] uppercase font-bold text-ink-600">Merit Announcement</span>
                                                <span class="font-semibold text-brand-700">{{ $adm['merit_date'] }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Bottom Row: Source Citation & Actions -->
                                    <div class="pt-3 border-t border-border-subtle flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs font-sans">
                                        <div class="flex items-center gap-1.5 text-ink-500">
                                            <x-icon name="shield-check" class="w-3.5 h-3.5 text-success-600 shrink-0" />
                                            <span class="truncate">{{ $adm['source_citation'] }}</span>
                                        </div>

                                        <div class="flex items-center gap-2.5 shrink-0 self-end sm:self-auto">
                                            <a 
                                                href="{{ $adm['official_url'] }}" 
                                                target="_blank" 
                                                rel="noopener noreferrer"
                                                class="text-xs text-ink-600 hover:text-brand-600 font-medium inline-flex items-center gap-1"
                                            >
                                                <span>Official Portal</span>
                                                <x-icon name="external-link" class="w-3 h-3" />
                                            </a>
                                            <x-ui.button variant="primary" size="xs" :href="route('admissions.show', $adm['slug'])" iconRight="arrow-right">
                                                Admission Schedule & Criteria
                                            </x-ui.button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Functional Pagination -->
                        <div class="mt-8 pt-4 border-t border-border-subtle">
                            <x-ui.pagination 
                                :currentPage="$results['current_page']" 
                                :totalPages="$results['total_pages']" 
                                :totalItems="$results['total']" 
                                :perPage="$results['per_page']" 
                            />
                        </div>
                    @endif

                </section>
            </div>
        </main>

        <!-- Mobile Filter Drawer -->
        <div 
            x-show="mobileFilterOpen" 
            x-modal-focus="mobileFilterOpen"
            @keydown.escape.window="mobileFilterOpen = false"
            @resize.window="if (window.innerWidth >= 1024) mobileFilterOpen = false"
            aria-label="Filter admissions"
            x-cloak
            class="fixed inset-0 z-50 overflow-hidden lg:hidden"
            role="dialog" 
            aria-modal="true"
        >
            <div 
                x-show="mobileFilterOpen" 
                class="fixed inset-0 bg-ink-950/40 backdrop-blur-xs" 
                @click="mobileFilterOpen = false"
            ></div>

            <div class="fixed inset-x-0 bottom-0 max-h-[90dvh] sm:inset-x-auto sm:inset-y-0 sm:right-0 sm:max-h-none max-w-full flex sm:pl-10">
                <div class="w-screen max-w-md max-h-[90dvh] sm:max-h-none bg-surface rounded-t-2xl sm:rounded-none border-l border-border-default shadow-modal p-4 sm:p-6 safe-area-bottom overflow-y-auto flex flex-col justify-between">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-border-subtle pb-4">
                            <h2 class="font-heading font-bold text-base text-ink-950 flex items-center gap-2">
                                <x-icon name="sliders-horizontal" class="w-4 h-4 text-brand-600" />
                                <span>Filter Admissions</span>
                            </h2>
                            <button 
                                type="button" 
                                @click="mobileFilterOpen = false" 
                                aria-label="Close filters"
                                class="p-1.5 text-ink-400 hover:text-ink-700 rounded-lg hover:bg-surface-subtle"
                            >
                                <x-icon name="x" class="w-5 h-5" />
                            </button>
                        </div>

                        <form action="{{ route('admissions.index') }}" method="GET" class="space-y-4">
                            @if(!empty($filters['q'])) <input type="hidden" name="q" value="{{ $filters['q'] }}" /> @endif

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">Admission Status</label>
                                <select name="status" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    @foreach($filterOptions['statuses'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['status'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">Province / Territory</label>
                                <select name="province" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    @foreach($filterOptions['provinces'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['province'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">Degree Level</label>
                                <select name="degree_level" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    @foreach($filterOptions['degree_levels'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['degree_level'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pt-4 flex items-center gap-3">
                                <a 
                                    href="{{ route('admissions.index') }}" 
                                    class="w-1/2 py-2.5 rounded-xl border border-border-default text-center text-xs font-semibold text-ink-700 hover:bg-surface-subtle transition-colors"
                                >
                                    Reset
                                </a>
                                <button 
                                    type="submit" 
                                    class="w-1/2 py-2.5 rounded-xl bg-brand-600 text-center text-xs font-semibold text-white hover:bg-brand-700 transition-colors shadow-xs"
                                >
                                    Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-layouts.public>

