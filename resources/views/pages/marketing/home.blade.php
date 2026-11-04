<x-layouts.public 
    title="UniGuide Pakistan — National University Discovery & Admissions Platform"
    description="Discover 248+ HEC-recognized Pakistani universities, calculate official aggregate merit, explore accredited BS/MS degree programs, and compare fee structures."
>
    <!-- ========================================================================= -->
    <!-- 1. EDITORIAL HERO SECTION WITH LARGE CENTRAL INTELLIGENT SEARCH EXPERIENCE -->
    <!-- ========================================================================= -->
    <section class="relative pt-10 pb-16 md:pt-16 md:pb-24 border-b border-border-default overflow-hidden bg-gradient-to-b from-brand-50/40 via-canvas to-canvas">
        <!-- Subtle Background Glows -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 pointer-events-none opacity-40">
            <div class="absolute -top-24 left-1/4 w-96 h-96 bg-brand-200/50 rounded-full blur-3xl"></div>
            <div class="absolute top-10 right-1/4 w-80 h-80 bg-lavender-100/60 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center space-y-6">
                <!-- National Context Eyebrow -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold font-sans bg-surface border border-brand-200 text-brand-700 shadow-2xs">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-600"></span>
                    </span>
                    <span>Fall 2026 National University Admissions Cycle Live</span>
                    <span class="text-ink-300">•</span>
                    <span class="text-ink-600 font-normal">248 HEC Chartered Institutions</span>
                </div>

                <!-- Main Editorial Headline -->
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-heading font-extrabold text-ink-950 tracking-tight leading-[1.1] sm:leading-[1.12]">
                    Find the right university in Pakistan. <br class="hidden sm:inline" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 via-brand-700 to-indigo-900">
                        Calculate exact merit.
                    </span> 
                    Apply with confidence.
                </h1>

                <!-- Supporting Editorial Subtitle -->
                <p class="text-base sm:text-lg text-ink-600 font-sans leading-relaxed max-w-2xl mx-auto">
                    The national higher education intelligence platform. Explore verified admission cutoffs, gazetted semester tuition fees, and PEC/PMDC accredited programs across all 5 provincial jurisdictions.
                </p>

                <!-- ========================================================================= -->
                <!-- LARGE INTELLIGENT UNIVERSITY SEARCH EXPERIENCE (CENTRAL INTERACTION) -->
                <!-- ========================================================================= -->
                <div 
                    x-data="{
                        activeTab: 'university', // university, program, aggregate, province
                        searchQuery: '',
                        selectedProvince: 'all',
                        selectedSector: 'all',
                        selectedDiscipline: 'all',
                        suggestions: [
                            { type: 'University', title: 'National University of Sciences & Technology (NUST)', meta: 'Islamabad • HEC #1', route: '{{ route('universities.show', 'nust') }}' },
                            { type: 'University', title: 'FAST - NUCES (Computing & AI)', meta: 'Islamabad, Lahore, Karachi', route: '{{ route('universities.show', 'fast-nuces') }}' },
                            { type: 'University', title: 'Lahore University of Management Sciences (LUMS)', meta: 'Lahore • Business & CS', route: '{{ route('universities.show', 'lums') }}' },
                            { type: 'Program', title: 'BS Computer Science (BSCS)', meta: '4 Years • Average Fee PKR 140k/sem', route: '{{ route('programs.show', 'fast-bs-computer-science') }}' },
                            { type: 'Program', title: 'BS Software Engineering (BSSE)', meta: '4 Years • Top Merit', route: '{{ route('programs.show', 'nust-bs-software-engineering') }}' },
                            { type: 'Scholarship', title: 'HEC Need-Based Undergraduate Scholarship', meta: '100% Tuition + PKR 6,000/mo', route: '{{ route('universities.index') }}' },
                        ],
                        get filteredSuggestions() {
                            if (!this.searchQuery.trim()) return [];
                            return this.suggestions.filter(s => 
                                s.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                s.meta.toLowerCase().includes(this.searchQuery.toLowerCase())
                            );
                        }
                    }"
                    class="pt-4 text-left max-w-3xl mx-auto"
                >
                    <!-- Search Container Card -->
                    <div class="bg-surface rounded-2xl border border-border-default shadow-elevated p-3 sm:p-4 transition-all">
                        <!-- Mode Tabs -->
                        <div class="flex items-center space-x-1 border-b border-border-subtle pb-3 mb-3 text-xs font-sans">
                            <button 
                                type="button" 
                                @click="activeTab = 'university'"
                                :class="activeTab === 'university' ? 'bg-brand-50 text-brand-700 font-bold border-brand-200' : 'text-ink-600 hover:text-ink-950 hover:bg-surface-subtle border-transparent'"
                                class="px-3 py-1.5 rounded-lg border transition-smooth flex items-center gap-1.5 cursor-pointer"
                            >
                                <x-icon name="building-2" class="w-3.5 h-3.5" />
                                <span>By University</span>
                            </button>

                            <button 
                                type="button" 
                                @click="activeTab = 'program'"
                                :class="activeTab === 'program' ? 'bg-brand-50 text-brand-700 font-bold border-brand-200' : 'text-ink-600 hover:text-ink-950 hover:bg-surface-subtle border-transparent'"
                                class="px-3 py-1.5 rounded-lg border transition-smooth flex items-center gap-1.5 cursor-pointer"
                            >
                                <x-icon name="graduation-cap" class="w-3.5 h-3.5" />
                                <span>By Degree Program</span>
                            </button>

                            <button 
                                type="button" 
                                @click="activeTab = 'aggregate'"
                                :class="activeTab === 'aggregate' ? 'bg-brand-50 text-brand-700 font-bold border-brand-200' : 'text-ink-600 hover:text-ink-950 hover:bg-surface-subtle border-transparent'"
                                class="px-3 py-1.5 rounded-lg border transition-smooth flex items-center gap-1.5 cursor-pointer"
                            >
                                <x-icon name="calculator" class="w-3.5 h-3.5" />
                                <span>By My Aggregate %</span>
                            </button>

                            <button 
                                type="button" 
                                @click="activeTab = 'scholarship'"
                                :class="activeTab === 'scholarship' ? 'bg-brand-50 text-brand-700 font-bold border-brand-200' : 'text-ink-600 hover:text-ink-950 hover:bg-surface-subtle border-transparent'"
                                class="hidden sm:flex px-3 py-1.5 rounded-lg border transition-smooth items-center gap-1.5 cursor-pointer"
                            >
                                <x-icon name="award" class="w-3.5 h-3.5" />
                                <span>Scholarships</span>
                            </button>
                        </div>

                        <!-- Search Input Row -->
                        <div class="relative">
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                                <div class="relative flex-1">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-ink-400">
                                        <x-icon name="search" class="w-5 h-5" />
                                    </div>
                                    <input 
                                        type="text" 
                                        x-model="searchQuery"
                                        :placeholder="activeTab === 'aggregate' ? 'Enter your estimated aggregate (e.g. 78.5% or FSc marks)...' : (activeTab === 'program' ? 'Search degree (e.g. BS Software Engineering, MBBS, BBA)...' : 'Search university by name, city, or acronym (e.g. NUST, LUMS, Karachi)...')"
                                        class="w-full pl-11 pr-10 py-3 text-sm text-ink-900 placeholder:text-ink-400 bg-surface-subtle rounded-xl border border-border-default focus:outline-none focus:border-brand-500 focus:bg-surface focus:ring-2 focus:ring-brand-500/20 font-sans transition-all"
                                        @keydown.escape="searchQuery = ''"
                                        @keydown.enter="window.location.href = searchQuery.trim() ? '{{ route('search.index') }}?q=' + encodeURIComponent(searchQuery) : '{{ route('universities.index') }}'"
                                    />
                                    <button 
                                        type="button" 
                                        x-show="searchQuery.length > 0" 
                                        @click="searchQuery = ''"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-ink-400 hover:text-ink-700 cursor-pointer"
                                    >
                                        <x-icon name="x" class="w-4 h-4" />
                                    </button>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <button 
                                        type="button"
                                        @click="window.location.href = searchQuery.trim() ? '{{ route('search.index') }}?q=' + encodeURIComponent(searchQuery) : '{{ route('universities.index') }}'"
                                        class="inline-flex items-center justify-center gap-2 w-full sm:w-auto h-11 px-6 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-sans text-xs font-semibold shadow-xs transition-smooth cursor-pointer"
                                    >
                                        <span>Explore Matches</span>
                                        <x-icon name="arrow-right" class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>

                            <!-- Live Search Suggestions Dropdown -->
                            <div 
                                x-show="filteredSuggestions.length > 0" 
                                x-cloak
                                @click.outside="searchQuery = ''"
                                class="absolute left-0 right-0 top-full mt-2 z-30 bg-surface rounded-xl border border-border-default shadow-dropdown p-2 space-y-1"
                            >
                                <div class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider text-ink-400 font-sans">
                                    Instant Recommendations
                                </div>
                                <template x-for="item in filteredSuggestions" :key="item.title">
                                    <a 
                                        :href="item.route" 
                                        @click="searchQuery = ''"
                                        class="flex items-center justify-between p-2.5 rounded-lg hover:bg-surface-subtle transition-colors group cursor-pointer"
                                    >
                                        <div class="flex items-center gap-3">
                                            <span 
                                                class="px-2 py-0.5 rounded text-[10px] font-mono font-bold"
                                                :class="item.type === 'University' ? 'bg-brand-50 text-brand-700' : (item.type === 'Program' ? 'bg-success-50 text-success-700' : 'bg-lavender-50 text-lavender-700')"
                                                x-text="item.type"
                                            ></span>
                                            <div>
                                                <p class="text-xs font-semibold text-ink-900 group-hover:text-brand-600 transition-colors font-sans" x-text="item.title"></p>
                                                <p class="text-[11px] text-ink-500 font-sans" x-text="item.meta"></p>
                                            </div>
                                        </div>
                                        <x-icon name="arrow-up-right" class="w-4 h-4 text-ink-400 group-hover:text-brand-600 transition-colors" />
                                    </a>
                                </template>
                            </div>
                        </div>

                        <!-- Secondary Filter Pills Row -->
                        <div class="mt-3 pt-3 border-t border-border-subtle flex flex-wrap items-center justify-between gap-3 text-xs font-sans">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-ink-400 font-medium">Quick Filters:</span>
                                <button 
                                    type="button" 
                                    @click="selectedProvince = selectedProvince === 'ict' ? 'all' : 'ict'"
                                    :class="selectedProvince === 'ict' ? 'bg-brand-50 text-brand-700 border-brand-300 font-bold' : 'bg-surface text-ink-600 border-border-default hover:bg-surface-subtle'"
                                    class="px-2.5 py-1 rounded-full border transition-smooth text-[11px] cursor-pointer"
                                >
                                    Islamabad (Federal)
                                </button>
                                <button 
                                    type="button" 
                                    @click="selectedProvince = selectedProvince === 'punjab' ? 'all' : 'punjab'"
                                    :class="selectedProvince === 'punjab' ? 'bg-brand-50 text-brand-700 border-brand-300 font-bold' : 'bg-surface text-ink-600 border-border-default hover:bg-surface-subtle'"
                                    class="px-2.5 py-1 rounded-full border transition-smooth text-[11px] cursor-pointer"
                                >
                                    Punjab
                                </button>
                                <button 
                                    type="button" 
                                    @click="selectedProvince = selectedProvince === 'sindh' ? 'all' : 'sindh'"
                                    :class="selectedProvince === 'sindh' ? 'bg-brand-50 text-brand-700 border-brand-300 font-bold' : 'bg-surface text-ink-600 border-border-default hover:bg-surface-subtle'"
                                    class="px-2.5 py-1 rounded-full border transition-smooth text-[11px] cursor-pointer"
                                >
                                    Sindh
                                </button>
                                <button 
                                    type="button" 
                                    @click="selectedSector = selectedSector === 'public' ? 'all' : 'public'"
                                    :class="selectedSector === 'public' ? 'bg-emerald-50 text-emerald-800 border-emerald-300 font-bold' : 'bg-surface text-ink-600 border-border-default hover:bg-surface-subtle'"
                                    class="px-2.5 py-1 rounded-full border transition-smooth text-[11px] cursor-pointer"
                                >
                                    Public Sector Only
                                </button>
                            </div>

                            <a href="{{ route('marketing.how_it_works') }}" class="text-brand-600 hover:text-brand-700 font-semibold inline-flex items-center gap-1 text-[11px]">
                                <span>How aggregate works</span>
                                <x-icon name="arrow-right" class="w-3 h-3" />
                            </a>
                        </div>
                    </div>

                    <!-- Popular Search Suggestion Tags -->
                    <div class="flex flex-wrap items-center justify-center gap-2 mt-4 text-xs font-sans text-ink-500">
                        <span class="font-medium text-ink-400">Popular right now:</span>
                        <a href="#universities" class="px-2.5 py-1 rounded-md bg-surface border border-border-default text-ink-700 hover:border-brand-300 hover:text-brand-600 transition-colors shadow-2xs">NUST NET 2026</a>
                        <a href="#universities" class="px-2.5 py-1 rounded-md bg-surface border border-border-default text-ink-700 hover:border-brand-300 hover:text-brand-600 transition-colors shadow-2xs">FAST CS Aggregate</a>
                        <a href="#universities" class="px-2.5 py-1 rounded-md bg-surface border border-border-default text-ink-700 hover:border-brand-300 hover:text-brand-600 transition-colors shadow-2xs">LUMS SDSB Fee</a>
                        <a href="#scholarships" class="px-2.5 py-1 rounded-md bg-surface border border-border-default text-ink-700 hover:border-brand-300 hover:text-brand-600 transition-colors shadow-2xs">Ehsaas Phase-IV</a>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- 2. QUICK ACCESS CARDS: UNIVERSITIES, PROGRAMS, ADMISSIONS, SCHOLARSHIPS -->
            <!-- ========================================================================= -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mt-16 pt-8 border-t border-border-default">
                <!-- Tile 1 -->
                <a href="{{ route('universities.index') }}" class="group p-5 bg-surface rounded-2xl border border-border-default hover:border-brand-300 hover:shadow-card hover:-translate-y-0.5 transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-200/60 mb-4 group-hover:scale-110 transition-transform">
                            <x-icon name="building-2" class="w-5 h-5" />
                        </div>
                        <h3 class="font-heading font-bold text-base text-ink-950 group-hover:text-brand-600 transition-colors">
                            248+ HEC Universities
                        </h3>
                        <p class="text-xs text-ink-500 font-sans mt-1 leading-relaxed">
                            Filter by federal charter, provincial recognition, Category W rankings, and city campuses.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-brand-600 mt-4 font-sans">
                        <span>Browse directory</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
                    </span>
                </a>

                <!-- Tile 2 -->
                <a href="{{ route('programs.index') }}" class="group p-5 bg-surface rounded-2xl border border-border-default hover:border-success-300 hover:shadow-card hover:-translate-y-0.5 transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-success-50 text-success-600 flex items-center justify-center border border-success-200/60 mb-4 group-hover:scale-110 transition-transform">
                            <x-icon name="graduation-cap" class="w-5 h-5" />
                        </div>
                        <h3 class="font-heading font-bold text-base text-ink-950 group-hover:text-success-700 transition-colors">
                            6,400+ Degree Programs
                        </h3>
                        <p class="text-xs text-ink-500 font-sans mt-1 leading-relaxed">
                            BS, BE, MS & PhD degrees with verified semester fees and council accreditations.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-success-700 mt-4 font-sans">
                        <span>Explore curricula</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
                    </span>
                </a>

                <!-- Tile 3 -->
                <a href="{{ route('admissions.index') }}" class="group p-5 bg-surface rounded-2xl border border-border-default hover:border-warning-300 hover:shadow-card hover:-translate-y-0.5 transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-warning-50 text-warning-600 flex items-center justify-center border border-warning-200/60 mb-4 group-hover:scale-110 transition-transform">
                            <x-icon name="calendar-range" class="w-5 h-5" />
                        </div>
                        <h3 class="font-heading font-bold text-base text-ink-950 group-hover:text-warning-700 transition-colors">
                            Admission Cycles & Cutoffs
                        </h3>
                        <p class="text-xs text-ink-500 font-sans mt-1 leading-relaxed">
                            Live deadlines, multi-year closing aggregates, and 1-Link bank challan procedures.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-warning-700 mt-4 font-sans">
                        <span>Check schedules</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
                    </span>
                </a>

                <!-- Tile 4 -->
                <a href="{{ route('scholarships.index') }}" class="group p-5 bg-surface rounded-2xl border border-border-default hover:border-lavender-300 hover:shadow-card hover:-translate-y-0.5 transition-all flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-lavender-50 text-lavender-600 flex items-center justify-center border border-lavender-200/60 mb-4 group-hover:scale-110 transition-transform">
                            <x-icon name="award" class="w-5 h-5" />
                        </div>
                        <h3 class="font-heading font-bold text-base text-ink-950 group-hover:text-lavender-700 transition-colors">
                            PKR 4.2B+ Scholarships
                        </h3>
                        <p class="text-xs text-ink-500 font-sans mt-1 leading-relaxed">
                            Ehsaas, HEC Need-Based, USAID, and institutional endowment fund eligibility matcher.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-lavender-700 mt-4 font-sans">
                        <span>Find financial aid</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
                    </span>
                </a>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 3. FEATURED UNIVERSITY DISCOVERY SECTION -->
    <!-- ========================================================================= -->
    <section id="universities" class="py-16 md:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <x-ui.section-header 
                eyebrow="HEC Accredited Registry"
                title="Explore Verified Pakistani Universities"
                description="Browse institutions with officially chartered degrees, gazetted tuition schedules, and council-approved infrastructure."
            />

            <!-- Filter Controls -->
            <div class="shrink-0 flex items-center gap-2">
                <x-ui.segmented-control 
                    :options="[
                        ['id' => 'all', 'label' => 'All Sectors', 'count' => 248],
                        ['id' => 'public', 'label' => 'Public', 'count' => 142],
                        ['id' => 'private', 'label' => 'Chartered Private', 'count' => 106],
                    ]"
                    default="all"
                />
            </div>
        </div>

        <!-- University Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredUniversities as $uni)
                <x-domain.university-card 
                    :name="$uni['name']"
                    :acronym="$uni['acronym']"
                    :location="$uni['location']"
                    :sector="$uni['sector']"
                    :hecRank="$uni['hecRank']"
                    :feeRange="$uni['feeRange']"
                    :programsCount="$uni['programsCount']"
                    :acceptanceRate="$uni['acceptanceRate']"
                    :status="$uni['status']"
                    :deadline="$uni['deadline']"
                    :featured="$uni['featured']"
                    :href="route('universities.show', $uni['slug'])"
                />
            @endforeach
        </div>

        <div class="mt-12 p-6 bg-surface-subtle rounded-2xl border border-border-default flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                    <x-icon name="building-2" class="w-5 h-5" />
                </div>
                <div>
                    <h4 class="font-heading font-bold text-sm text-ink-950">Looking for regional or provincial universities?</h4>
                    <p class="text-xs text-ink-500 font-sans">Discover sub-campuses in Faisalabad, Multan, Peshawar, Hyderabad, Sukkur, and Quetta.</p>
                </div>
            </div>
            <x-ui.button variant="outline" size="sm" :href="route('universities.index')" iconRight="arrow-right">
                View Full University Registry
            </x-ui.button>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 4. DEGREE PROGRAM EXPLORATION SECTION -->
    <!-- ========================================================================= -->
    <section id="programs" class="py-16 md:py-24 bg-surface border-y border-border-default">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
                <x-ui.section-header 
                    eyebrow="Academic Curricula & Cutoffs"
                    title="Popular Undergraduate Degree Programs"
                    description="Compare minimum entry test merit aggregates, duration, and statutory council accreditation status."
                />

                <div class="flex flex-wrap items-center gap-2">
                    <x-form.filter-chip label="Computing & AI" active="true" />
                    <x-form.filter-chip label="Engineering (PEC)" />
                    <x-form.filter-chip label="Business & Management" />
                    <x-form.filter-chip label="Medical & PMDC" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($popularPrograms as $prog)
                    <x-domain.program-card 
                        :title="$prog['title']"
                        :university="$prog['university']"
                        :degreeLevel="$prog['degreeLevel']"
                        :duration="$prog['duration']"
                        :feePerSemester="$prog['feePerSemester']"
                        :cutoffMerit="$prog['cutoffMerit']"
                        :accreditation="$prog['accreditation']"
                        :status="$prog['status']"
                        :href="route('programs.show', $prog['slug'])"
                    />
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 5. AI COUNSELOR PRODUCT SHOWCASE -->
    <!-- ========================================================================= -->
    <section id="ai-counselor" class="py-16 md:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Left Narrative -->
            <div class="lg:col-span-5 space-y-6">
                <x-ui.section-header 
                    eyebrow="National Intelligence Engine"
                    title="An AI Counselor grounded strictly in verified Pakistani admission guidelines"
                    description="Trained on official Higher Education Commission policies, 5-year university merit archives, and BISE grading curves. No hallucinated requirements, no fabricated cutoff rumors."
                />

                <div class="space-y-4 text-xs font-sans">
                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 border border-brand-200/60 mt-0.5">
                            <x-icon name="shield-check" class="w-4 h-4" />
                        </div>
                        <div>
                            <h4 class="font-bold text-ink-900 text-sm">Regulatory Citations on Every Answer</h4>
                            <p class="text-ink-500 mt-0.5">Whenever UniGuide suggests a target institution, it quotes the exact published prospectus circular or statutory gazette number.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 border border-brand-200/60 mt-0.5">
                            <x-icon name="calculator" class="w-4 h-4" />
                        </div>
                        <div>
                            <h4 class="font-bold text-ink-900 text-sm">40+ Standardized University Formulas</h4>
                            <p class="text-ink-500 mt-0.5">Calculates precise aggregates across NUST NET (75/15/10), FAST NU (50/50), MDCAT Punjab/Sindh, and provincial engineering quotas.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 border border-brand-200/60 mt-0.5">
                            <x-icon name="clock" class="w-4 h-4" />
                        </div>
                        <div>
                            <h4 class="font-bold text-ink-900 text-sm">Safe vs Target vs Reach Stratification</h4>
                            <p class="text-ink-500 mt-0.5">Provides realistic risk analysis so candidates never apply exclusively to high-cutoff programs without backup safety options.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <x-ui.button variant="primary" size="md" :href="route('preview.student')" iconRight="arrow-right">
                        Launch AI Admission Advisor
                    </x-ui.button>
                    <x-ui.button variant="outline" size="md" :href="route('marketing.how_it_works')">
                        Learn Methodology
                    </x-ui.button>
                </div>
            </div>

            <!-- Right Interactive AI Demonstration Balloon -->
            <div class="lg:col-span-7">
                <x-domain.ai-message 
                    advisorName="UniGuide National Merit Intelligence"
                    confidence="98% High Confidence"
                    timestamp="Calculated using verified Fall 2026 guidelines"
                    :citations="[
                        ['source' => 'NUST SEECS Official Admission Gazette 2025-26', 'reference' => 'Cutoff Circular 2025-SEECS-CS', 'date' => 'Official Source'],
                        ['source' => 'FAST-NUCES Islamabad Admission Office', 'reference' => 'Batch 2025 Aggregate Closing List', 'date' => 'Verified']
                    ]"
                    :prompts="[
                        'What aggregate is needed for FAST BS Artificial Intelligence?',
                        'Calculate fee payment installments for LUMS SDSB',
                        'Show HEC need-based scholarship application deadline'
                    ]"
                >
                    <p>
                        Greetings Candidate! Here is your personalized admission prognosis based on your Matriculation marks (<strong>1042/1100 • 94.7%</strong>) and FSc Pre-Engineering marks (<strong>482/520 • 92.6%</strong>):
                    </p>
                    <ul class="list-disc pl-5 space-y-1.5">
                        <li>
                            <strong>NUST SEECS (Islamabad):</strong> A target score of <strong>150+ in NET-3</strong> yields an aggregate of <strong>81.20%</strong>. This safely clears the historical first merit cutoff for <em>BS Software Engineering</em> (80.12%) and <em>BS Computer Science</em> (81.40%).
                        </li>
                        <li>
                            <strong>FAST-NUCES (Islamabad / Lahore):</strong> Your 92.6% FSc Part-I provides exceptional leverage under the 50% FSc + 50% NU Test formula. A modest NU score of <strong>66/100</strong> secures admission in the First Merit List.
                        </li>
                        <li>
                            <strong>Financial Aid Feasibility:</strong> You qualify for the <strong>HEC Need-Based Grant</strong> and the <strong>PEEF Merit Scholarship</strong> for public sector enrollment.
                        </li>
                    </ul>
                </x-domain.ai-message>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 6. UNIVERSITY COMPARISON SHOWCASE -->
    <!-- ========================================================================= -->
    <section id="comparison" class="py-16 md:py-24 bg-surface-subtle border-y border-border-default">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-ui.section-header 
                align="center"
                eyebrow="Objective Decision Making"
                title="Side-by-Side Institutional Comparison"
                description="Evaluate institutions across the metrics that matter most: total 4-year tuition, accreditation tier, faculty PhD ratio, and hostel accommodations."
            />

            <!-- Comparison Table Container -->
            <div class="mt-10 overflow-hidden rounded-2xl border border-border-default bg-surface shadow-card">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm font-sans">
                        <thead>
                            <tr class="bg-surface-subtle border-b border-border-default">
                                <th class="p-5 text-xs font-bold uppercase tracking-wider text-ink-500 w-1/4">Evaluation Parameter</th>
                                @foreach($comparisonMatrix['institutions'] as $inst)
                                    <th class="p-5 text-center w-1/4">
                                        <div class="font-heading font-extrabold text-base text-ink-950">{{ $inst['name'] }}</div>
                                        <div class="text-xs text-ink-500 mt-0.5">{{ $inst['type'] }}</div>
                                        <span class="inline-block mt-2 px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-brand-50 text-brand-700 border border-brand-200/60">
                                            {{ $inst['badge'] }}
                                        </span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle text-xs">
                            @foreach($comparisonMatrix['attributes'] as $attr)
                                <tr class="hover:bg-surface-subtle/50 transition-colors">
                                    <td class="p-4 font-semibold text-ink-900 bg-surface-subtle/30">{{ $attr['label'] }}</td>
                                    <td class="p-4 text-center text-ink-700 font-medium">{{ $attr['nust'] }}</td>
                                    <td class="p-4 text-center text-ink-700 font-medium">{{ $attr['fast'] }}</td>
                                    <td class="p-4 text-center text-ink-700 font-medium">{{ $attr['lums'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 text-center">
                <x-ui.button variant="outline" size="md" :href="route('design-system') . '#data-tables'" iconRight="arrow-right">
                    Open Advanced Multi-University Comparison Tool
                </x-ui.button>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 7. SCHOLARSHIP DISCOVERY SECTION -->
    <!-- ========================================================================= -->
    <section id="scholarships" class="py-16 md:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <x-ui.section-header 
                eyebrow="Financial Aid & Equality"
                title="National Scholarships & Grants (2026–2027)"
                description="Over PKR 4.2 Billion in verified federal, provincial, and bilateral educational grants. No student should discontinue higher education due to tuition costs."
            />

            <x-ui.button variant="outline" size="sm" :href="route('scholarships.index')" icon="award">
                Check My Eligibility
            </x-ui.button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($scholarships as $sch)
                <x-domain.scholarship-card 
                    :title="$sch['title']"
                    :provider="$sch['provider']"
                    :coverage="$sch['coverage']"
                    :eligibility="$sch['eligibility']"
                    :deadline="$sch['deadline']"
                    :category="$sch['category']"
                    :href="route('scholarships.show', $sch['slug'])"
                />
            @endforeach
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 8. HOW THE PLATFORM WORKS (WORKFLOW BREAKDOWN) -->
    <!-- ========================================================================= -->
    <section class="py-16 md:py-24 bg-surface border-y border-border-default">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-ui.section-header 
                align="center"
                eyebrow="Simple 4-Step Journey"
                title="How Pakistani Students Navigate Admissions with UniGuide"
                description="From calculating your raw intermediate aggregate to securing your verified seat challan."
            />

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-12">
                <!-- Step 1 -->
                <div class="relative p-6 bg-surface-subtle rounded-2xl border border-border-default flex flex-col justify-between">
                    <span class="text-3xl font-heading font-extrabold text-brand-600/30">01</span>
                    <div class="mt-4 space-y-2">
                        <h4 class="font-heading font-bold text-base text-ink-950">Discover & Filter</h4>
                        <p class="text-xs text-ink-500 font-sans leading-relaxed">
                            Search by province, tuition budget, or PEC/PMDC council accreditation. Zero fake sub-campuses.
                        </p>
                    </div>
                    <span class="text-[11px] font-mono text-brand-700 font-semibold mt-4">Verified Registry →</span>
                </div>

                <!-- Step 2 -->
                <div class="relative p-6 bg-surface-subtle rounded-2xl border border-border-default flex flex-col justify-between">
                    <span class="text-3xl font-heading font-extrabold text-brand-600/30">02</span>
                    <div class="mt-4 space-y-2">
                        <h4 class="font-heading font-bold text-base text-ink-950">Calculate Aggregate</h4>
                        <p class="text-xs text-ink-500 font-sans leading-relaxed">
                            Input your Matric, FSc, and Entry Test scores once. The engine computes 40+ university formulas.
                        </p>
                    </div>
                    <span class="text-[11px] font-mono text-brand-700 font-semibold mt-4">Automatic Formulas →</span>
                </div>

                <!-- Step 3 -->
                <div class="relative p-6 bg-surface-subtle rounded-2xl border border-border-default flex flex-col justify-between">
                    <span class="text-3xl font-heading font-extrabold text-brand-600/30">03</span>
                    <div class="mt-4 space-y-2">
                        <h4 class="font-heading font-bold text-base text-ink-950">AI Cutoff Forecaster</h4>
                        <p class="text-xs text-ink-500 font-sans leading-relaxed">
                            Compare your aggregate against 5-year closing cutoffs to evaluate selection chances.
                        </p>
                    </div>
                    <span class="text-[11px] font-mono text-brand-700 font-semibold mt-4">Evidence Based →</span>
                </div>

                <!-- Step 4 -->
                <div class="relative p-6 bg-surface-subtle rounded-2xl border border-border-default flex flex-col justify-between">
                    <span class="text-3xl font-heading font-extrabold text-brand-600/30">04</span>
                    <div class="mt-4 space-y-2">
                        <h4 class="font-heading font-bold text-base text-ink-950">Track & Pay Challans</h4>
                        <p class="text-xs text-ink-500 font-sans leading-relaxed">
                            Follow merit list declarations, download 1-Link bank challans, and confirm your enrollment.
                        </p>
                    </div>
                    <span class="text-[11px] font-mono text-brand-700 font-semibold mt-4">1-Link Banking →</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 9. TRUST, DATA TRANSPARENCY & STATUTORY COMPLIANCE -->
    <!-- ========================================================================= -->
    <section class="py-16 md:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-surface rounded-3xl border border-border-default p-8 md:p-12 shadow-card">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-4">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-sans bg-success-50 text-success-800 border border-success-200">
                        <x-icon name="shield-check" class="w-4 h-4 text-success-600" />
                        <span>Data Transparency Protocol</span>
                    </div>

                    <h3 class="text-2xl md:text-3xl font-heading font-bold text-ink-950 tracking-tight">
                        Built in strict accordance with the Higher Education Commission of Pakistan Ordinance 2002
                    </h3>

                    <p class="text-sm text-ink-600 font-sans leading-relaxed">
                        To protect Pakistani students from fraudulent degrees and unauthorized sub-campuses, UniGuide verifies every single program against official Gazette notifications, federal legislative acts, and provincial assembly charters.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-border-subtle text-xs font-sans">
                        <div>
                            <span class="block font-bold text-ink-950 font-heading text-sm">248 Chartered Bodies</span>
                            <span class="text-ink-500">142 Public • 106 Chartered Private</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink-950 font-heading text-sm">Zero Fabricated Stats</span>
                            <span class="text-ink-500">Audited against published prospectuses</span>
                        </div>
                        <div>
                            <span class="block font-bold text-ink-950 font-heading text-sm">100% Free for Students</span>
                            <span class="text-ink-500">No student search or application fee surcharges</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 flex flex-col items-center justify-center p-6 bg-surface-subtle rounded-2xl border border-border-default text-center space-y-3">
                    <x-brand.logo size="lg" />
                    <span class="font-heading font-extrabold text-base text-ink-950">Statutory Verification Seal</span>
                    <p class="text-[11px] text-ink-500 font-sans">Federal Registry Node: HEC-PK-REG-2026</p>
                    <x-domain.verification-badge type="hec_recognized" detail="Level-1 Verified" size="sm" />
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================================================= -->
    <!-- 10. FINAL CONVERSION SECTION -->
    <!-- ========================================================================= -->
    <section class="pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative rounded-3xl bg-gradient-to-r from-brand-900 via-brand-800 to-indigo-950 p-8 sm:p-12 md:p-16 text-white overflow-hidden shadow-modal">
            <!-- Background Motif -->
            <div class="absolute -right-10 -bottom-10 w-96 h-96 opacity-10 pointer-events-none">
                <svg viewBox="0 0 200 200" fill="currentColor">
                    <path d="M100 0 L150 100 L200 100 L125 150 L150 200 L100 160 L50 200 L75 150 L0 100 L50 100 Z" />
                </svg>
            </div>

            <div class="relative max-w-2xl space-y-5">
                <span class="text-xs font-mono uppercase tracking-widest text-brand-200 bg-white/10 px-3 py-1 rounded-full font-bold">
                    Start Your Higher Education Journey
                </span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight leading-tight">
                    Join thousands of Pakistani students finding their future on UniGuide.
                </h2>
                <p class="text-sm sm:text-base text-brand-100 font-sans leading-relaxed">
                    Create a free student account to save programs, track admission deadlines across multiple universities, and unlock personalized AI counseling.
                </p>
                <div class="pt-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    <x-ui.button variant="secondary" size="lg" :href="route('auth.register')" class="bg-white text-ink-950 hover:bg-brand-50 shadow-elevated">
                        Create Free Student Account
                    </x-ui.button>
                    <x-ui.button variant="outline" size="lg" :href="route('preview.student')" class="border-white/30 text-white hover:bg-white/10">
                        View Demo Student Dashboard
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

