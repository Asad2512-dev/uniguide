<x-layouts.public 
    title="National Scholarship Directory — UniGuide Pakistan"
    description="Discover verified higher education scholarships in Pakistan including Need-Based, Ehsaas Undergraduate, PEEF, BEEF, LUMS NOP, and university endowment trust funds."
>
    <div x-data="{ 
        activeType: '{{ $filters['type'] }}',
        activeStatus: '{{ $filters['status'] }}'
    }" class="min-h-screen bg-canvas pb-20">
        
        <!-- Header Banner & Search -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Scholarships', 'url' => route('scholarships.index')]
                    ]" />
                </div>

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold font-sans mb-3">
                            <x-icon name="award" class="w-3.5 h-3.5 text-emerald-600" />
                            <span>Verified National Financial Aid Repository</span>
                        </div>
                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                            National Scholarship & Financial Aid Explorer
                        </h1>
                        <p class="text-sm sm:text-base text-ink-600 font-sans max-w-2xl mt-1.5 leading-relaxed">
                            Search government scholarships, provincial endowment funds, and university tuition waivers. Availability depends on verified regional eligibility.
                        </p>
                    </div>

                    <!-- Verified Fund Counter -->
                    <div class="flex items-center gap-4 bg-surface-subtle p-3.5 rounded-xl border border-border-subtle shrink-0">
                        <div class="text-center px-2">
                            <span class="block font-heading font-extrabold text-xl text-ink-950">{{ $results['total'] }}</span>
                            <span class="text-xs sm:text-[11px] font-sans text-ink-500 uppercase tracking-wider font-semibold">Scholarship Funds</span>
                        </div>
                        <div class="h-8 w-px bg-border-default"></div>
                        <div class="text-center px-2">
                            <span class="block font-heading font-extrabold text-xl text-emerald-700">100%</span>
                            <span class="text-xs sm:text-[11px] font-sans text-ink-500 uppercase tracking-wider font-semibold">Verified</span>
                        </div>
                    </div>
                </div>

                <!-- Primary Search Bar -->
                <div class="mt-8">
                    <form action="{{ route('scholarships.index') }}" method="GET" class="relative">
                        @if($filters['type'] !== 'all') <input type="hidden" name="type" value="{{ $filters['type'] }}" /> @endif
                        @if($filters['status'] !== 'all') <input type="hidden" name="status" value="{{ $filters['status'] }}" /> @endif

                        <div class="relative flex items-center">
                            <x-icon name="search" class="w-5 h-5 text-ink-400 absolute left-4.5 pointer-events-none" />
                            <input 
                                type="text" 
                                name="q" 
                                value="{{ $filters['q'] }}" 
                                placeholder="Search scholarships by provider (Ehsaas, PEEF, BEEF, LUMS, NUST), or benefits…"
                                class="w-full bg-canvas/80 border border-border-default rounded-2xl pl-13 pr-32 py-3.5 text-sm sm:text-base text-ink-900 placeholder:text-ink-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 font-sans shadow-xs transition-smooth"
                            />
                            <div class="absolute right-2.5 flex items-center gap-2">
                                @if(!empty($filters['q']))
                                    <a 
                                        href="{{ route('scholarships.index', array_merge(request()->except(['q', 'page']))) }}" 
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

                    <!-- Type Filter Tabs -->
                    <div class="flex items-center gap-2 mt-4 overflow-x-auto pb-1 text-xs font-sans font-semibold">
                        <a 
                            href="{{ route('scholarships.index', array_merge(request()->except(['type', 'page']))) }}"
                            class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap {{ $filters['type'] === 'all' ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                        >
                            All Scholarships
                        </a>
                        @foreach($filterOptions['scholarship_types'] as $key => $label)
                            @if($key !== 'all')
                                <a 
                                    href="{{ route('scholarships.index', array_merge(request()->except(['type', 'page']), ['type' => $key])) }}"
                                    class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap {{ $filters['type'] === $key ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                                >
                                    {{ $label }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Body: Results Grid -->
        <main class="landing-container mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-6">
            
            <!-- Control Bar: Result Count, Status Filter, Sort -->
            <div class="bg-surface rounded-2xl border border-border-default p-4 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <p class="text-xs sm:text-sm font-sans text-ink-600">
                    Showing <span class="font-bold text-ink-900">{{ $results['from'] }}–{{ $results['to'] }}</span> of <span class="font-bold text-ink-900">{{ $results['total'] }}</span> verified financial aid schemes
                </p>

                <!-- Sort & Status Controls -->
                <div class="flex items-center gap-3 self-end sm:self-auto">
                    <form action="{{ route('scholarships.index') }}" method="GET" class="flex items-center gap-2">
                        @foreach(request()->except(['status', 'sort', 'page']) as $k => $v)
                            @if(!empty($v) && $v !== 'all')
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}" />
                            @endif
                        @endforeach

                        <label for="status-select" class="text-xs font-sans text-ink-500 whitespace-nowrap hidden sm:inline">Status:</label>
                        <select 
                            id="status-select" 
                            name="status" 
                            onchange="this.form.submit()"
                            class="text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-1.5 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                        >
                            <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>All Statuses</option>
                            <option value="open" {{ $filters['status'] === 'open' ? 'selected' : '' }}>Applications Open</option>
                            <option value="upcoming" {{ $filters['status'] === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="closed" {{ $filters['status'] === 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>

                        <label for="sort-select" class="text-xs font-sans text-ink-500 whitespace-nowrap hidden sm:inline ml-2">Sort:</label>
                        <select 
                            id="sort-select" 
                            name="sort" 
                            onchange="this.form.submit()"
                            class="text-xs font-sans rounded-xl border-border-default bg-surface-subtle py-1.5 px-3 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-ink-800"
                        >
                            <option value="deadline_asc" {{ $sort === 'deadline_asc' ? 'selected' : '' }}>Earliest Deadline</option>
                            <option value="deadline_desc" {{ $sort === 'deadline_desc' ? 'selected' : '' }}>Latest Deadline</option>
                            <option value="title_asc" {{ $sort === 'title_asc' ? 'selected' : '' }}>Title (A–Z)</option>
                        </select>
                    </form>
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
                                href="{{ route('scholarships.index', array_merge(request()->except([$chip['param'], 'page']))) }}" 
                                class="text-ink-400 hover:text-ink-700"
                                title="Remove filter"
                            >
                                <x-icon name="x" class="w-3.5 h-3.5" />
                            </a>
                        </span>
                    @endforeach
                    <a 
                        href="{{ route('scholarships.index') }}" 
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
                    <h3 class="font-heading font-bold text-lg text-ink-950 mb-1.5">No scholarships found</h3>
                    <p class="text-sm font-sans text-ink-600 max-w-md mx-auto mb-6">
                        No financial aid programs matched your filter parameters. Try clearing the type filter or searching by an institutional name.
                    </p>
                    <x-ui.button variant="primary" size="sm" :href="route('scholarships.index')">
                        Reset All Filters
                    </x-ui.button>
                </div>
            @else
                <!-- Scholarships Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($results['data'] as $sch)
                        <div class="bg-surface rounded-2xl border border-border-default hover:border-emerald-300 hover:shadow-card p-4 sm:p-6 transition-smooth flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-2.5">
                                    <div>
                                        <span class="inline-flex items-center text-xs sm:text-[10px] font-mono font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 mb-1.5">
                                            {{ $sch['type_label'] }}
                                        </span>
                                        <h3 class="font-heading font-bold text-base sm:text-lg text-ink-950 hover:text-brand-600 transition-colors">
                                            <a href="{{ route('scholarships.show', $sch['slug']) }}">
                                                {{ $sch['title'] }}
                                            </a>
                                        </h3>
                                        <p class="text-xs text-ink-500 font-sans mt-0.5">
                                            {{ $sch['provider'] }}
                                        </p>
                                    </div>

                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200/70">
                                        <x-icon name="award" class="w-5 h-5" />
                                    </div>
                                </div>

                                <!-- Coverage Callout -->
                                <div class="p-3.5 bg-surface-subtle rounded-xl border border-border-subtle text-xs font-sans space-y-1.5 my-3">
                                    <div class="flex items-start gap-2 text-ink-950 font-semibold">
                                        <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                        <span>{{ $sch['coverage'] }}</span>
                                    </div>
                                    <p class="text-xs sm:text-[11px] text-ink-500 pl-6">
                                        Scope: <strong>{{ $sch['universities_scope'] }}</strong>
                                    </p>
                                </div>

                                <p class="text-xs text-ink-600 font-sans leading-relaxed">
                                    {{ $sch['beneficiaries'] }}
                                </p>
                            </div>

                            <!-- Footer Row: Deadline & Actions -->
                            <div class="pt-3 border-t border-border-subtle flex items-center justify-between gap-3 text-xs font-sans">
                                <div>
                                    <span class="block text-xs sm:text-[11px] text-ink-600">Application Cutoff</span>
                                    <span class="font-bold text-ink-900">{{ $sch['deadline'] }}</span>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <x-ui.button variant="outline" size="xs" :href="route('scholarships.show', $sch['slug'])" iconRight="arrow-right">
                                        Eligibility & How to Apply
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

        </main>

    </div>
</x-layouts.public>

