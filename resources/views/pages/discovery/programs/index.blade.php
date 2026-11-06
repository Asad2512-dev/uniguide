<x-layouts.public 
    title="Academic Program Explorer — UniGuide Pakistan"
    description="Discover undergraduate and postgraduate degrees across Punjab and Islamabad. Compare closing merits, credit hours, semester fee structures, and professional accreditation councils."
>
    <div x-data="{ 
        mobileFilterOpen: false,
        activeDiscipline: '{{ $filters['discipline'] }}'
    }" class="min-h-screen bg-canvas pb-20">
        
        <!-- Header Banner & Search -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumb -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Programs', 'url' => route('programs.index')]
                    ]" />
                </div>

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-brand-50 border border-brand-200/60 text-brand-700 text-xs font-semibold font-sans mb-3">
                            <x-icon name="graduation-cap" class="w-3.5 h-3.5 text-brand-600" />
                            <span>National Academic Program Registry</span>
                        </div>
                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                            Academic Degree Program Explorer
                        </h1>
                        <p class="text-sm sm:text-base text-ink-600 font-sans max-w-2xl mt-1.5 leading-relaxed">
                            Search accredited undergraduate and postgraduate curricula across Punjab and Islamabad. Evaluate closing aggregates, council accreditations, and semester fee schedules.
                        </p>
                    </div>

                    <!-- Program Counter Metrics -->
                    <div class="flex items-center gap-4 bg-surface-subtle p-3.5 rounded-xl border border-border-subtle shrink-0">
                        <div class="text-center px-2">
                            <span class="block font-heading font-extrabold text-xl text-ink-950">{{ $results['total'] }}</span>
                            <span class="text-xs sm:text-[11px] font-sans text-ink-500 uppercase tracking-wider font-semibold">Degrees</span>
                        </div>
                        <div class="h-8 w-px bg-border-default"></div>
                        <div class="text-center px-2">
                            <span class="block font-heading font-extrabold text-xl text-brand-700">Fall 2026</span>
                            <span class="text-xs sm:text-[11px] font-sans text-ink-500 uppercase tracking-wider font-semibold">Admissions</span>
                        </div>
                    </div>
                </div>

                <!-- Primary Search Bar -->
                <div class="mt-8">
                    <form action="{{ route('programs.index') }}" method="GET" class="relative">
                        @if($filters['discipline'] !== 'all') <input type="hidden" name="discipline" value="{{ $filters['discipline'] }}" /> @endif
                        @if($filters['degree_level'] !== 'all') <input type="hidden" name="degree_level" value="{{ $filters['degree_level'] }}" /> @endif
                        @if($filters['university'] !== 'all') <input type="hidden" name="university" value="{{ $filters['university'] }}" /> @endif

                        <div class="relative flex items-center">
                            <x-icon name="search" class="w-5 h-5 text-ink-400 absolute left-4.5 pointer-events-none" />
                            <input 
                                type="text" 
                                name="q" 
                                value="{{ $filters['q'] }}" 
                                placeholder="Search by degree title (Computer Science, Artificial Intelligence, MBBS, BBA), or university…"
                                class="w-full bg-canvas/80 border border-border-default rounded-2xl pl-13 pr-32 py-3.5 text-sm sm:text-base text-ink-900 placeholder:text-ink-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 font-sans shadow-xs transition-smooth"
                            />
                            <div class="absolute right-2.5 flex items-center gap-2">
                                @if(!empty($filters['q']))
                                    <a 
                                        href="{{ route('programs.index', array_merge(request()->except(['q', 'page']))) }}" 
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

                    <!-- Discipline Pill Tabs -->
                    <div class="flex items-center gap-2 mt-4 overflow-x-auto pb-1 text-xs font-sans font-semibold">
                        <a 
                            href="{{ route('programs.index', array_merge(request()->except(['discipline', 'page']))) }}"
                            class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap {{ $filters['discipline'] === 'all' ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                        >
                            All Fields
                        </a>
                        @foreach($filterOptions['disciplines'] as $key => $label)
                            @if($key !== 'all')
                                <a 
                                    href="{{ route('programs.index', array_merge(request()->except(['discipline', 'page']), ['discipline' => $key])) }}"
                                    class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap {{ $filters['discipline'] === $key ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                                >
                                    {{ $label }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Body: Desktop Filter Sidebar + Program Grid Viewport -->
        <main class="landing-container mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Desktop Sidebar Filters -->
                <aside class="hidden lg:block lg:col-span-1 space-y-6">
                    <form action="{{ route('programs.index') }}" method="GET" id="desktop-prog-filter-form">
                        @if(!empty($filters['q'])) <input type="hidden" name="q" value="{{ $filters['q'] }}" /> @endif

                        <div class="bg-surface rounded-2xl border border-border-default p-5 space-y-6 shadow-2xs">
                            <div class="flex items-center justify-between border-b border-border-subtle pb-3.5">
                                <span class="font-heading font-bold text-sm text-ink-900 flex items-center gap-2">
                                    <x-icon name="sliders-horizontal" class="w-4 h-4 text-brand-600" />
                                    Filter Programs
                                </span>
                                @if(!empty($appliedFilters))
                                    <a 
                                        href="{{ route('programs.index') }}" 
                                        class="text-xs text-brand-600 hover:text-brand-700 font-semibold"
                                    >
                                        Reset All
                                    </a>
                                @endif
                            </div>

                            <!-- Degree Level -->
                            <div>
                                <label for="filter-degree-level" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Degree Level
                                </label>
                                <select 
                                    id="filter-degree-level" 
                                    name="degree_level" 
                                    onchange="document.getElementById('desktop-prog-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['degree_levels'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['degree_level'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Academic Discipline -->
                            <div>
                                <label for="filter-discipline" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Discipline Category
                                </label>
                                <select 
                                    id="filter-discipline" 
                                    name="discipline" 
                                    onchange="document.getElementById('desktop-prog-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    @foreach($filterOptions['disciplines'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['discipline'] === $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- University Filter -->
                            <div>
                                <label for="filter-university" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Offering University
                                </label>
                                <select 
                                    id="filter-university" 
                                    name="university" 
                                    onchange="document.getElementById('desktop-prog-filter-form').submit()"
                                    class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                                >
                                    <option value="all" {{ $filters['university'] === 'all' ? 'selected' : '' }}>All Universities</option>
                                    @foreach($universities as $u)
                                        <option value="{{ $u['slug'] }}" {{ $filters['university'] === $u['slug'] ? 'selected' : '' }}>
                                            {{ $u['acronym'] }} ({{ $u['city'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Max Semester Tuition -->
                            <div>
                                <label for="filter-max-fee" class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">
                                    Max Semester Fee (PKR)
                                </label>
                                <div class="flex items-center gap-2">
                                    <input 
                                        type="number" 
                                        id="filter-max-fee"
                                        name="max_fee" 
                                        value="{{ $filters['max_fee'] }}" 
                                        placeholder="e.g. 180000"
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

                        <!-- Accreditation Integrity Notice -->
                        <div class="mt-4 p-3.5 rounded-xl bg-surface-subtle border border-border-subtle text-xs sm:text-[11px] font-sans text-ink-500 space-y-1">
                            <div class="flex items-center gap-1.5 font-semibold text-ink-700">
                                <x-icon name="award" class="w-3.5 h-3.5 text-brand-600" />
                                <span>Statutory Council Accreditations</span>
                            </div>
                            <p class="leading-relaxed">
                                Programs marked with PEC Level-II (Washington Accord) qualify for global engineer mobility. Computing programs adhere to NCEAC criteria.
                            </p>
                        </div>
                    </form>
                </aside>

                <!-- Results Viewport -->
                <section class="lg:col-span-3 space-y-6">
                    
                    <!-- Control Bar: Result Count, Mobile Trigger, Sort Selector -->
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
                                Showing <span class="font-bold text-ink-900">{{ $results['from'] }}–{{ $results['to'] }}</span> of <span class="font-bold text-ink-900">{{ $results['total'] }}</span> academic programs
                            </p>
                        </div>

                        <!-- Sort Selector -->
                        <form action="{{ route('programs.index') }}" method="GET" class="flex items-center gap-2 self-end sm:self-auto">
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
                                <option value="merit_desc" {{ $sort === 'merit_desc' ? 'selected' : '' }}>Highest Closing Merit</option>
                                <option value="fee_asc" {{ $sort === 'fee_asc' ? 'selected' : '' }}>Tuition: Lowest First</option>
                                <option value="fee_desc" {{ $sort === 'fee_desc' ? 'selected' : '' }}>Tuition: Highest First</option>
                                <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>Program Title (A–Z)</option>
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
                                        href="{{ route('programs.index', array_merge(request()->except([$chip['param'], 'page']))) }}" 
                                        class="text-ink-400 hover:text-ink-700"
                                        title="Remove filter"
                                    >
                                        <x-icon name="x" class="w-3.5 h-3.5" />
                                    </a>
                                </span>
                            @endforeach
                            <a 
                                href="{{ route('programs.index') }}" 
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
                                <x-icon name="search-x" class="w-7 h-7" />
                            </div>
                            <h3 class="font-heading font-bold text-lg text-ink-950 mb-1.5">No degree programs found</h3>
                            <p class="text-sm font-sans text-ink-600 max-w-md mx-auto mb-6">
                                We could not find any academic program matching your filter settings. Try selecting a broader discipline or clearing fee constraints.
                            </p>
                            <x-ui.button variant="primary" size="sm" :href="route('programs.index')">
                                Reset All Filters
                            </x-ui.button>
                        </div>
                    @else
                        <!-- Programs Cards Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($results['data'] as $prog)
                                <x-domain.program-card 
                                    :title="$prog['title']"
                                    :university="$prog['university_name']"
                                    :degreeLevel="$prog['degree_level']"
                                    :duration="$prog['duration']"
                                    :feePerSemester="$prog['fee_semester']"
                                    :cutoffMerit="$prog['cutoff_merit']"
                                    :accreditation="$prog['accreditation']"
                                    :status="$prog['status']"
                                    :href="route('programs.show', $prog['slug'])"
                                />
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
            aria-label="Filter programs"
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
                                <span>Filter Degree Programs</span>
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

                        <form action="{{ route('programs.index') }}" method="GET" class="space-y-4">
                            @if(!empty($filters['q'])) <input type="hidden" name="q" value="{{ $filters['q'] }}" /> @endif

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">Degree Level</label>
                                <select name="degree_level" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    @foreach($filterOptions['degree_levels'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['degree_level'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">Academic Discipline</label>
                                <select name="discipline" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    @foreach($filterOptions['disciplines'] as $key => $label)
                                        <option value="{{ $key }}" {{ $filters['discipline'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-ink-700 font-sans mb-1.5">Offering University</label>
                                <select name="university" class="w-full text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-2 px-3 text-ink-800">
                                    <option value="all" {{ $filters['university'] === 'all' ? 'selected' : '' }}>All Universities</option>
                                    @foreach($universities as $u)
                                        <option value="{{ $u['slug'] }}" {{ $filters['university'] === $u['slug'] ? 'selected' : '' }}>
                                            {{ $u['acronym'] }} ({{ $u['city'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="pt-4 flex items-center gap-3">
                                <a 
                                    href="{{ route('programs.index') }}" 
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

