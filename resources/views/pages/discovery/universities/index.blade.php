<x-layouts.public 
    title="National University Directory — UniGuide Pakistan"
    description="Discover and compare 248+ HEC-recognized public and chartered private universities across Pakistan. Filter by province, discipline, merit cutoffs, and verified tuition fees."
>
    <div x-data="{ 
        mobileFilterOpen: false,
        viewMode: '{{ $view }}',
        activeDiscipline: '{{ $filters['discipline'] }}',
        activeProvince: '{{ $filters['province'] }}',
        activeSector: '{{ $filters['sector'] }}'
    }" class="min-h-screen bg-canvas pb-20">
        
        <!-- Header Banner & Search -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'National Directory', 'url' => route('universities.index')]
                    ]" />
                </div>

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-brand-50 border border-brand-200/60 text-brand-700 text-xs font-semibold font-sans mb-3">
                            <x-icon name="shield-check" class="w-3.5 h-3.5 text-brand-600" />
                            <span>HEC Recognized & Statutory Gazette Audited</span>
                        </div>
                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                            National University Directory
                        </h1>
                        <p class="text-sm sm:text-base text-ink-600 font-sans max-w-2xl mt-1.5 leading-relaxed">
                            Authoritative catalog of public sector and chartered higher education institutions in Pakistan, verified against official parliamentary charters and HEC standards.
                        </p>
                    </div>

                    <!-- Live National Counter Metric -->
                    <div class="flex items-center gap-4 bg-surface-subtle p-3.5 rounded-xl border border-border-subtle shrink-0">
                        <div class="text-center px-2">
                            <span class="block font-heading font-extrabold text-xl text-ink-950">{{ $results['total'] }}</span>
                            <span class="text-[11px] font-sans text-ink-500 uppercase tracking-wider font-semibold">Institutions</span>
                        </div>
                        <div class="h-8 w-px bg-border-default"></div>
                        <div class="text-center px-2">
                            <span class="block font-heading font-extrabold text-xl text-success-700">100%</span>
                            <span class="text-[11px] font-sans text-ink-500 uppercase tracking-wider font-semibold">Chartered</span>
                        </div>
                    </div>
                </div>

                <!-- Primary Search Bar & Popular Quick Queries -->
                <div class="mt-8">
                    <form action="{{ route('universities.index') }}" method="GET" class="relative">
                        <!-- Preserve current view and non-conflicting filters -->
                        <input type="hidden" name="view" :value="viewMode" />
                        @if($filters['province'] !== 'all') <input type="hidden" name="province" value="{{ $filters['province'] }}" /> @endif
                        @if($filters['sector'] !== 'all') <input type="hidden" name="sector" value="{{ $filters['sector'] }}" /> @endif
                        @if($filters['discipline'] !== 'all') <input type="hidden" name="discipline" value="{{ $filters['discipline'] }}" /> @endif

                        <div class="relative flex items-center">
                            <x-icon name="search" class="w-5 h-5 text-ink-400 absolute left-4.5 pointer-events-none" />
                            <input 
                                type="text" 
                                name="q" 
                                value="{{ $filters['q'] }}" 
                                placeholder="Search by university name, acronym (NUST, FAST, LUMS, GIKI), or campus city…"
                                class="w-full bg-canvas/80 border border-border-default rounded-2xl pl-13 pr-32 py-3.5 text-sm sm:text-base text-ink-900 placeholder:text-ink-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 font-sans shadow-xs transition-smooth"
                            />
                            <div class="absolute right-2.5 flex items-center gap-2">
                                @if(!empty($filters['q']))
                                    <a 
                                        href="{{ route('universities.index', array_merge(request()->except(['q', 'page']))) }}" 
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

                    <!-- Popular Quick Queries -->
                    <div class="flex items-center flex-wrap gap-2 mt-3 text-xs font-sans text-ink-500">
                        <span class="font-medium text-ink-400">Popular Searches:</span>
                        <a href="{{ route('universities.index', ['q' => 'NUST']) }}" class="px-2.5 py-0.5 rounded-lg bg-surface-subtle border border-border-default hover:border-brand-300 hover:text-brand-600 transition-colors">NUST Islamabad</a>
                        <a href="{{ route('universities.index', ['q' => 'FAST']) }}" class="px-2.5 py-0.5 rounded-lg bg-surface-subtle border border-border-default hover:border-brand-300 hover:text-brand-600 transition-colors">FAST-NUCES</a>
                        <a href="{{ route('universities.index', ['q' => 'LUMS']) }}" class="px-2.5 py-0.5 rounded-lg bg-surface-subtle border border-border-default hover:border-brand-300 hover:text-brand-600 transition-colors">LUMS Lahore</a>
                        <a href="{{ route('universities.index', ['discipline' => 'medical']) }}" class="px-2.5 py-0.5 rounded-lg bg-surface-subtle border border-border-default hover:border-brand-300 hover:text-brand-600 transition-colors">Medical Colleges (PMDC)</a>
                        <a href="{{ route('universities.index', ['sector' => 'public']) }}" class="px-2.5 py-0.5 rounded-lg bg-surface-subtle border border-border-default hover:border-brand-300 hover:text-brand-600 transition-colors">Public Sector Only</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Body: Desktop Filter Sidebar + Results Viewport -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Desktop Sidebar Filters -->
                <aside class="hidden lg:block lg:col-span-1 space-y-6">
                    <form action="{{ route('universities.index') }}" method="GET" id="desktop-filter-form">
                        <input type="hidden" name="view" :value="viewMode" />
                        @if(!empty($filters['q'])) <input type="hidden" name="q" value="{{ $filters['q'] }}" /> @endif

                        <div class="bg-surface rounded-2xl border border-border-default p-5 space-y-6 shadow-2xs">
                            <div class="flex items-center justify-between border-b border-border-subtle pb-3.5">
                                <span class="font-heading font-bold text-sm text-ink-900 flex items-center gap-2">
                                    <x-icon name="sliders-horizontal" class="w-4 h-4 text-brand-600" />
                                    Filter Directory
                                </span>
                                @if(!empty($appliedFilters))
                                    <a 
                                        href="{{ route('universities.index') }}" 
                                        class="text-xs text-brand-600 hover:text-brand-700 font-semibold"
                                    >
                                        Reset All
                                    </a>
                                @endif
                            </div>

                            <!-- Province Filter -->
                            <div>
                                <label for="filter-province" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Province / Territory
                                </label>
                                <select 
                                    id="filter-province" 
                                    name="province" 
                                    onchange="document.getElementById('desktop-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['provinces'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['province'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- City Filter -->
                            <div>
                                <label for="filter-city" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Campus City
                                </label>
                                <select 
                                    id="filter-city" 
                                    name="city" 
                                    onchange="document.getElementById('desktop-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['cities'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['city'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sector Filter -->
                            <div>
                                <label for="filter-sector" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Institutional Sector
                                </label>
                                <select 
                                    id="filter-sector" 
                                    name="sector" 
                                    onchange="document.getElementById('desktop-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['sectors'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['sector'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Academic Discipline -->
                            <div>
                                <label for="filter-discipline" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Primary Academic Discipline
                                </label>
                                <select 
                                    id="filter-discipline" 
                                    name="discipline" 
                                    onchange="document.getElementById('desktop-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['disciplines'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['discipline'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Degree Level -->
                            <div>
                                <label for="filter-degree-level" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Degree Level Offered
                                </label>
                                <select 
                                    id="filter-degree-level" 
                                    name="degree_level" 
                                    onchange="document.getElementById('desktop-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['degree_levels'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['degree_level'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- HEC Category Tier -->
                            <div>
                                <label for="filter-hec" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    HEC Accreditation Category
                                </label>
                                <select 
                                    id="filter-hec" 
                                    name="hec_category" 
                                    onchange="document.getElementById('desktop-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['hec_categories'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['hec_category'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Max Semester Tuition -->
                            <div>
                                <label for="filter-max-fee" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Max Tuition (PKR / Sem)
                                </label>
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="number" 
                                        id="filter-max-fee"
                                        name="max_fee" 
                                        value="{{ $filters['max_fee'] }}" 
                                        placeholder="e.g. 200000"
                                        class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800 font-mono"
                                    />
                                    <button 
                                        type="submit" 
                                        class="px-3 py-2 rounded-xl bg-ink-900 text-white text-xs font-semibold hover:bg-brand-600 transition-colors shrink-0"
                                    >
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Data Integrity Notice -->
                        <div class="mt-4 p-3.5 rounded-xl bg-surface-subtle border border-border-subtle text-[11px] font-sans text-ink-500 space-y-1">
                            <div class="flex items-center gap-1.5 font-semibold text-ink-700">
                                <x-icon name="shield-check" class="w-3.5 h-3.5 text-brand-600" />
                                <span>Federal Gazette Verification</span>
                            </div>
                            <p class="leading-relaxed">
                                Every institution indexed possesses a recognized parliamentary act or provincial charter verified by the Higher Education Commission.
                            </p>
                        </div>
                    </form>
                </aside>

                <!-- Results Viewport -->
                <section class="lg:col-span-3 space-y-6">
                    
                    <!-- Control Bar: Result Count, Mobile Filter Trigger, Sort, View Toggle -->
                    <div class="bg-surface rounded-2xl border border-border-default p-4 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        
                        <!-- Left: Count & Mobile Filter Button -->
                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                @click="mobileFilterOpen = true"
                                class="lg:hidden inline-flex items-center gap-2 px-3 py-2 rounded-xl border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-800 shadow-2xs"
                            >
                                <x-icon name="sliders-horizontal" class="w-4 h-4 text-brand-600" />
                                <span>Filters</span>
                                @if(count($appliedFilters) > 0)
                                    <span class="w-5 h-5 rounded-full bg-brand-600 text-white text-[10px] flex items-center justify-center font-mono">
                                        {{ count($appliedFilters) }}
                                    </span>
                                @endif
                            </button>

                            <p class="text-xs sm:text-sm font-sans text-ink-600">
                                Showing <span class="font-bold text-ink-900">{{ $results['from'] }}–{{ $results['to'] }}</span> of <span class="font-bold text-ink-900">{{ $results['total'] }}</span> institutions
                            </p>
                        </div>

                        <!-- Right: Sort Dropdown & View Mode Switcher -->
                        <div class="flex items-center gap-3 self-end sm:self-auto">
                            <!-- Sort Selector -->
                            <form action="{{ route('universities.index') }}" method="GET" class="flex items-center gap-2">
                                <input type="hidden" name="view" :value="viewMode" />
                                @foreach(request()->except(['sort', 'page', 'view']) as $k => $v)
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
                                    <option value="rank" {{ $sort === 'rank' ? 'selected' : '' }}>HEC National Standing</option>
                                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>University Name (A–Z)</option>
                                    <option value="fee_asc" {{ $sort === 'fee_asc' ? 'selected' : '' }}>Tuition: Lowest First</option>
                                    <option value="fee_desc" {{ $sort === 'fee_desc' ? 'selected' : '' }}>Tuition: Highest First</option>
                                </select>
                            </form>

                            <!-- View Mode Buttons -->
                            <div class="flex items-center border border-border-default rounded-xl p-0.5 bg-surface-subtle">
                                <button 
                                    type="button" 
                                    @click="viewMode = 'grid'"
                                    :class="viewMode === 'grid' ? 'bg-surface text-brand-600 shadow-2xs font-semibold' : 'text-ink-500 hover:text-ink-900'"
                                    class="p-1.5 rounded-lg text-xs transition-smooth"
                                    title="Grid View"
                                    aria-label="Grid View"
                                >
                                    <x-icon name="layout-grid" class="w-4 h-4" />
                                </button>
                                <button 
                                    type="button" 
                                    @click="viewMode = 'list'"
                                    :class="viewMode === 'list' ? 'bg-surface text-brand-600 shadow-2xs font-semibold' : 'text-ink-500 hover:text-ink-900'"
                                    class="p-1.5 rounded-lg text-xs transition-smooth"
                                    title="Compact List View"
                                    aria-label="List View"
                                >
                                    <x-icon name="list" class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Applied Filter Chips -->
                    @if(!empty($appliedFilters))
                        <div class="flex items-center flex-wrap gap-2 pt-1">
                            <span class="text-xs text-ink-500 font-sans font-medium">Applied:</span>
                            @foreach($appliedFilters as $key => $chip)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-surface border border-border-default text-xs font-sans text-ink-800 shadow-2xs">
                                    <span>{{ $chip['label'] }}</span>
                                    <a 
                                        href="{{ route('universities.index', array_merge(request()->except([$chip['param'], 'page']))) }}" 
                                        class="text-ink-400 hover:text-ink-700"
                                        title="Remove filter"
                                    >
                                        <x-icon name="x" class="w-3.5 h-3.5" />
                                    </a>
                                </span>
                            @endforeach
                            <a 
                                href="{{ route('universities.index', ['view' => $view]) }}" 
                                class="text-xs text-brand-600 hover:text-brand-700 font-semibold ml-1"
                            >
                                Clear all
                            </a>
                        </div>
                    @endif

                    <!-- Empty State If No Results -->
                    @if(empty($results['data']))
                        <div class="bg-surface rounded-2xl border border-border-default p-12 text-center shadow-2xs">
                            <div class="w-14 h-14 rounded-2xl bg-brand-50 border border-brand-200/60 flex items-center justify-center mx-auto mb-4 text-brand-600">
                                <x-icon name="search-x" class="w-7 h-7" />
                            </div>
                            <h3 class="font-heading font-bold text-lg text-ink-950 mb-1.5">No universities found</h3>
                            <p class="text-sm font-sans text-ink-600 max-w-md mx-auto mb-6">
                                We could not find any university matching your criteria. Try loosening your filters or searching by a different city or acronym.
                            </p>
                            <x-ui.button variant="primary" size="sm" :href="route('universities.index')">
                                Reset All Filters
                            </x-ui.button>
                        </div>
                    @else
                        <!-- Grid View Mode -->
                        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($results['data'] as $uni)
                                <x-domain.university-card 
                                    :name="$uni['name']"
                                    :acronym="$uni['acronym']"
                                    :location="$uni['city'] . ', ' . $uni['province_label']"
                                    :sector="$uni['charter_type']"
                                    :hecRank="$uni['hec_ranking']"
                                    :feeRange="$uni['fee_range']"
                                    :programsCount="($uni['campuses'][0]['programs_offered_count'] ?? 25) . ' Programs'"
                                    :acceptanceRate="$uni['acceptance_rate']"
                                    :status="$uni['status']"
                                    :deadline="$uni['deadline']"
                                    :featured="$uni['featured'] ?? false"
                                    :href="route('universities.show', $uni['slug'])"
                                />
                            @endforeach
                        </div>

                        <!-- Compact List View Mode -->
                        <div x-show="viewMode === 'list'" class="space-y-4">
                            @foreach($results['data'] as $uni)
                                <div class="bg-surface rounded-2xl border border-border-default hover:border-brand-300 hover:shadow-card p-5 transition-smooth flex flex-col md:flex-row md:items-center justify-between gap-5">
                                    <div class="flex items-start gap-4">
                                        <div class="w-13 h-13 rounded-xl bg-surface-subtle border border-border-default flex items-center justify-center font-heading font-extrabold text-base text-brand-700 shadow-2xs shrink-0">
                                            {{ $uni['acronym'] }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="font-heading font-bold text-base text-ink-950 hover:text-brand-600 transition-colors">
                                                    <a href="{{ route('universities.show', $uni['slug']) }}">
                                                        {{ $uni['name'] }}
                                                    </a>
                                                </h3>
                                                @if($uni['featured'] ?? false)
                                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-semibold bg-brand-50 text-brand-700 border border-brand-200/60 uppercase font-mono">
                                                        Top Tier
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs font-sans text-ink-500">
                                                <span class="flex items-center gap-1">
                                                    <x-icon name="map-pin" class="w-3.5 h-3.5 text-ink-400" />
                                                    {{ $uni['city'] }}, {{ $uni['province_label'] }}
                                                </span>
                                                <span>•</span>
                                                <span class="font-medium text-ink-700">{{ $uni['charter_type'] }}</span>
                                                <span>•</span>
                                                <span class="text-brand-700 font-semibold">{{ $uni['hec_ranking'] }}</span>
                                                <span>•</span>
                                                <span>{{ count($uni['campuses'] ?? []) }} Campuses</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between md:justify-end gap-6 pt-3 md:pt-0 border-t md:border-t-0 border-border-subtle shrink-0">
                                        <div class="text-left md:text-right">
                                            <span class="block text-[11px] font-sans text-ink-500 font-medium">Estimated Tuition</span>
                                            <span class="font-mono font-bold text-xs sm:text-sm text-ink-900">{{ $uni['fee_range'] }}</span>
                                            <span class="block text-[10px] text-ink-400 font-sans">per semester</span>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <x-ui.button variant="outline" size="sm" :href="route('universities.show', $uni['slug'])">
                                                View Profile
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
            x-cloak
            class="fixed inset-0 z-50 overflow-hidden lg:hidden"
            role="dialog" 
            aria-modal="true"
        >
            <div 
                x-show="mobileFilterOpen" 
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-ink-950/40 backdrop-blur-xs" 
                @click="mobileFilterOpen = false"
            ></div>

            <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                <div 
                    x-show="mobileFilterOpen"
                    x-transition:enter="transform transition ease-in-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-200"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="w-screen max-w-md bg-surface border-l border-border-default shadow-modal p-6 overflow-y-auto flex flex-col justify-between"
                >
                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-border-subtle pb-4">
                            <h2 class="font-heading font-bold text-base text-ink-950 flex items-center gap-2">
                                <x-icon name="sliders-horizontal" class="w-4 h-4 text-brand-600" />
                                <span>Filter Universities</span>
                            </h2>
                            <button 
                                type="button" 
                                @click="mobileFilterOpen = false" 
                                class="p-1.5 text-ink-400 hover:text-ink-700 rounded-lg hover:bg-surface-subtle"
                            >
                                <x-icon name="x" class="w-5 h-5" />
                            </button>
                        </div>

                        <!-- Mobile Filter Form -->
                        <form action="{{ route('universities.index') }}" method="GET" id="mobile-filter-form" class="space-y-4">
                            <input type="hidden" name="view" :value="viewMode" />
                            @if(!empty($filters['q'])) <input type="hidden" name="q" value="{{ $filters['q'] }}" /> @endif

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">Province / Territory</label>
                                <select name="province" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    @foreach($filterOptions['provinces'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['province'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">Campus City</label>
                                <select name="city" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    @foreach($filterOptions['cities'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['city'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">Sector</label>
                                <select name="sector" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    @foreach($filterOptions['sectors'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['sector'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">Primary Discipline</label>
                                <select name="discipline" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    @foreach($filterOptions['disciplines'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['discipline'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">HEC Accreditation Category</label>
                                <select name="hec_category" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    @foreach($filterOptions['hec_categories'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['hec_category'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pt-4 flex items-center gap-3">
                                <a 
                                    href="{{ route('universities.index') }}" 
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
