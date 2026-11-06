<x-layouts.public 
    :title="(!empty($query) ? 'Search: ' . $query : 'National Search') . ' — UniGuide Pakistan'"
    description="Unified search across all accredited Pakistani universities, campuses, and degree programs."
>
    <div class="min-h-screen bg-canvas pb-24">
        
        <!-- Header & Search Box -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Search', 'url' => route('search.index')],
                        ['label' => !empty($query) ? $query : 'All Records']
                    ]" />
                </div>

                <div class="max-w-3xl">
                    <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                        @if(!empty($query))
                            Search results for <span class="text-brand-600">"{{ $query }}"</span>
                        @else
                            Search Universities & Programs
                        @endif
                    </h1>
                    <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                        Displaying verified records across 248+ registered universities and accredited degree programs.
                    </p>

                    <!-- Search Input Form -->
                    <form action="{{ route('search.index') }}" method="GET" class="mt-6">
                        @if($type !== 'all') <input type="hidden" name="type" value="{{ $type }}" /> @endif
                        <div class="relative flex items-center">
                            <x-icon name="search" class="w-5 h-5 text-ink-400 absolute left-4.5 pointer-events-none" />
                            <input 
                                type="text" 
                                name="q" 
                                value="{{ $query }}" 
                                placeholder="Search by institution name (NUST, FAST, LUMS), program title (CS, AI, MBBS), or city…"
                                class="w-full bg-canvas/80 border border-border-default rounded-2xl pl-13 pr-32 py-3.5 text-sm sm:text-base text-ink-900 placeholder:text-ink-400 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 font-sans shadow-xs transition-smooth"
                                autofocus
                            />
                            <div class="absolute right-2.5 flex items-center gap-2">
                                @if(!empty($query))
                                    <a 
                                        href="{{ route('search.index') }}" 
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
                                    <span>Search</span>
                                    <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Filter Tabs: All, Universities, Programs -->
                    @if(!empty($query))
                        <div class="flex items-center gap-2 mt-6 overflow-x-auto text-xs font-sans font-semibold">
                            <a 
                                href="{{ route('search.index', ['q' => $query, 'type' => 'all']) }}" 
                                class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap flex items-center gap-1.5 {{ $type === 'all' ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                            >
                                <span>All Results</span>
                                <span class="px-1.5 py-0.2 rounded-full text-xs sm:text-[10px] font-mono {{ $type === 'all' ? 'bg-white/20 text-white' : 'bg-surface-subtle border border-border-default' }}">
                                    {{ $results['total'] }}
                                </span>
                            </a>
                            <a 
                                href="{{ route('search.index', ['q' => $query, 'type' => 'universities']) }}" 
                                class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap flex items-center gap-1.5 {{ $type === 'universities' ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                            >
                                <span>Universities</span>
                                <span class="px-1.5 py-0.2 rounded-full text-xs sm:text-[10px] font-mono {{ $type === 'universities' ? 'bg-white/20 text-white' : 'bg-surface-subtle border border-border-default' }}">
                                    {{ count($results['universities']) }}
                                </span>
                            </a>
                            <a 
                                href="{{ route('search.index', ['q' => $query, 'type' => 'programs']) }}" 
                                class="px-3.5 py-1.5 rounded-xl border transition-smooth whitespace-nowrap flex items-center gap-1.5 {{ $type === 'programs' ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                            >
                                <span>Degree Programs</span>
                                <span class="px-1.5 py-0.2 rounded-full text-xs sm:text-[10px] font-mono {{ $type === 'programs' ? 'bg-white/20 text-white' : 'bg-surface-subtle border border-border-default' }}">
                                    {{ count($results['programs']) }}
                                </span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Main Body -->
        <main class="landing-container mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            @if(empty($query))
                <!-- Initial State: Prompt search -->
                <div class="bg-surface rounded-2xl border border-border-default p-12 text-center shadow-2xs max-w-xl mx-auto">
                    <div class="w-14 h-14 rounded-2xl bg-brand-50 border border-brand-200/60 flex items-center justify-center mx-auto mb-4 text-brand-600">
                        <x-icon name="search" class="w-7 h-7" />
                    </div>
                    <h2 class="font-heading font-bold text-lg text-ink-950 mb-1.5">Search Pakistan Higher Education</h2>
                    <p class="text-xs sm:text-sm font-sans text-ink-600 mb-6">
                        Type any university acronym, field of study, or city to find accredited degrees and institutional profiles.
                    </p>

                    <div class="flex items-center justify-center flex-wrap gap-2 text-xs font-sans">
                        <span class="text-ink-600 font-medium">Quick suggestions:</span>
                        <a href="{{ route('search.index', ['q' => 'NUST']) }}" class="px-2.5 py-1 rounded-lg bg-surface-subtle border border-border-default text-brand-700 hover:border-brand-300">NUST</a>
                        <a href="{{ route('search.index', ['q' => 'Computer Science']) }}" class="px-2.5 py-1 rounded-lg bg-surface-subtle border border-border-default text-brand-700 hover:border-brand-300">Computer Science</a>
                        <a href="{{ route('search.index', ['q' => 'FAST']) }}" class="px-2.5 py-1 rounded-lg bg-surface-subtle border border-border-default text-brand-700 hover:border-brand-300">FAST</a>
                        <a href="{{ route('search.index', ['q' => 'Lahore']) }}" class="px-2.5 py-1 rounded-lg bg-surface-subtle border border-border-default text-brand-700 hover:border-brand-300">Lahore</a>
                    </div>
                </div>

            @elseif($results['total'] === 0)
                <!-- Empty State -->
                <div class="bg-surface rounded-2xl border border-border-default p-12 text-center shadow-2xs max-w-xl mx-auto">
                    <div class="w-14 h-14 rounded-2xl bg-brand-50 border border-brand-200/60 flex items-center justify-center mx-auto mb-4 text-brand-600">
                        <x-icon name="search-x" class="w-7 h-7" />
                    </div>
                    <h2 class="font-heading font-bold text-lg text-ink-950 mb-1.5">No results found for "{{ $query }}"</h2>
                    <p class="text-xs sm:text-sm font-sans text-ink-600 mb-6">
                        We could not match any university or academic program. Check your spelling or try broader keywords like "Engineering", "Islamabad", or "Computing".
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        <x-ui.button variant="outline" size="sm" :href="route('universities.index')">
                            Browse All Universities
                        </x-ui.button>
                        <x-ui.button variant="primary" size="sm" :href="route('programs.index')">
                            Explore Degree Programs
                        </x-ui.button>
                    </div>
                </div>

            @else
                <!-- Results View -->
                <div class="space-y-10">
                    
                    <!-- Universities Section (if shown) -->
                    @if(($type === 'all' || $type === 'universities') && count($results['universities']) > 0)
                        <section class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                                    <x-icon name="building-2" class="w-5 h-5 text-brand-600" />
                                    <span>Matching Universities ({{ count($results['universities']) }})</span>
                                </h2>
                                <a href="{{ route('universities.index', ['q' => $query]) }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                                    View in directory →
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($results['universities'] as $uni)
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
                        </section>
                    @endif

                    <!-- Degree Programs Section (if shown) -->
                    @if(($type === 'all' || $type === 'programs') && count($results['programs']) > 0)
                        <section class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                                    <x-icon name="graduation-cap" class="w-5 h-5 text-brand-600" />
                                    <span>Matching Degree Programs ({{ count($results['programs']) }})</span>
                                </h2>
                                <a href="{{ route('programs.index', ['q' => $query]) }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                                    View in program explorer →
                                </a>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($results['programs'] as $prog)
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
                        </section>
                    @endif

                </div>
            @endif
        </main>

    </div>
</x-layouts.public>
