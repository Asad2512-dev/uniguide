<x-layouts.public 
    title="Conversational University Search — UniGuide Pakistan"
    description="Search Pakistani universities using everyday natural language. AI intent analysis, criteria extraction, and matching accredited institutions."
>
    <div class="min-h-screen bg-canvas pb-24">
        
        <!-- Header & Search Input Section -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'AI Counselor', 'url' => route('counselor.index')],
                        ['label' => 'Conversational Search', 'url' => route('counselor.search')]
                    ]" />
                </div>

                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-3">
                        <x-icon name="search" class="w-3.5 h-3.5 text-brand-600" />
                        <span>Natural Language Intent Engine</span>
                    </div>
                    <h1 class="font-heading font-bold text-2xl sm:text-3xl lg:text-4xl text-ink-950 tracking-tight">
                        Conversational University Search
                    </h1>
                    <p class="text-xs sm:text-sm text-ink-600 font-sans mt-2">
                        Type your requirements in plain English or Urdu phrasing. Our AI breaks down your criteria and cross-references verified institutional databases.
                    </p>
                </div>

                <!-- Big Search Box -->
                <form action="{{ route('counselor.search') }}" method="GET" class="mt-6 max-w-4xl">
                    <div class="relative flex items-center">
                        <x-icon name="search" class="w-5 h-5 text-ink-400 absolute left-4 pointer-events-none" />
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ $query }}"
                            placeholder="Try: Top CS universities in Islamabad under 200k with hostel..."
                            class="w-full pl-12 pr-28 py-3.5 rounded-2xl border border-border-default bg-surface-subtle focus:bg-surface text-ink-900 placeholder:text-ink-400 text-sm sm:text-base font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none shadow-2xs transition-all"
                            required
                        />
                        <button 
                            type="submit" 
                            class="absolute right-2.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold font-sans shadow-xs transition-smooth cursor-pointer flex items-center gap-1.5"
                        >
                            <span>Search</span>
                            <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                        </button>
                    </div>

                    <!-- Sample Queries -->
                    <div class="flex flex-wrap items-center gap-2 mt-3 text-xs">
                        <span class="text-ink-400 font-medium">Examples:</span>
                        @foreach($sampleQueries as $sample)
                            <a 
                                href="{{ route('counselor.search', ['q' => $sample]) }}"
                                class="px-2.5 py-1 rounded-full bg-surface border border-border-default hover:border-brand-300 hover:text-brand-700 text-ink-600 transition-smooth font-sans truncate max-w-xs"
                            >
                                {{ $sample }}
                            </a>
                        @endforeach
                    </div>
                </form>
            </div>
        </section>

        <!-- Search Results & Interpreted Criteria Grid -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                <!-- Left Sidebar: Interpreted Query Breakdown -->
                <aside class="space-y-6">
                    <div class="p-5 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                        <div class="flex items-center gap-2 pb-3 border-b border-border-subtle">
                            <x-icon name="sliders-horizontal" class="w-4 h-4 text-brand-600" />
                            <h2 class="font-heading font-bold text-sm text-ink-950">Interpreted Intent</h2>
                        </div>

                        <div class="space-y-3 text-xs font-sans">
                            <div>
                                <span class="text-[10px] text-ink-400 font-mono uppercase block">Target Discipline</span>
                                <span class="font-bold text-ink-900 bg-surface-subtle px-2 py-1 rounded border border-border-default block mt-0.5">
                                    {{ $criteria['discipline'] }}
                                </span>
                            </div>

                            <div>
                                <span class="text-[10px] text-ink-400 font-mono uppercase block">Location Match</span>
                                <span class="font-bold text-ink-900 bg-surface-subtle px-2 py-1 rounded border border-border-default block mt-0.5">
                                    {{ $criteria['location'] }}
                                </span>
                            </div>

                            <div>
                                <span class="text-[10px] text-ink-400 font-mono uppercase block">Tuition Constraint</span>
                                <span class="font-bold text-brand-700 bg-brand-50 px-2 py-1 rounded border border-brand-200 block mt-0.5">
                                    {{ $criteria['budget_ceiling'] }}
                                </span>
                            </div>

                            <div>
                                <span class="text-[10px] text-ink-400 font-mono uppercase block">Identified Amenities</span>
                                <div class="space-y-1 mt-1">
                                    @foreach($criteria['amenities_requested'] as $amenity)
                                        <div class="flex items-center gap-1.5 text-ink-700">
                                            <x-icon name="check" class="w-3.5 h-3.5 text-success-600 shrink-0" />
                                            <span>{{ $amenity }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-border-subtle">
                            <a 
                                href="{{ route('counselor.guided') }}"
                                class="text-xs font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1"
                            >
                                <span>Switch to Guided Assessment</span>
                                <x-icon name="arrow-right" class="w-3 h-3" />
                            </a>
                        </div>
                    </div>

                    <!-- AI Citation Note -->
                    <div class="p-4 rounded-xl bg-surface-subtle border border-border-default text-xs text-ink-500 font-sans space-y-1">
                        <div class="font-semibold text-ink-800 flex items-center gap-1.5">
                            <x-icon name="shield-check" class="w-3.5 h-3.5 text-success-600" />
                            <span>Statutory Verification</span>
                        </div>
                        <p>All institutions verified under HEC Charter & Syndicate Gazette Records.</p>
                    </div>
                </aside>

                <!-- Right 3 Cols: AI Commentary & Institutional Matches -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- AI Synthesis Banner -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-gradient-to-r from-brand-50/70 via-surface to-brand-50/40 border border-brand-200/80 shadow-2xs">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-brand-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                                <x-icon name="sparkles" class="w-4 h-4" />
                            </div>
                            <div class="space-y-1 text-xs font-sans text-ink-700 leading-relaxed">
                                <div class="font-heading font-bold text-sm text-ink-950 flex items-center gap-2">
                                    <span>AI Counselor Analysis</span>
                                    <span class="text-[10px] font-mono text-brand-800 bg-brand-100 px-1.5 py-0.2 rounded font-bold">
                                        {{ count($matches) }} Matches Found
                                    </span>
                                </div>
                                <p>{{ $result['ai_synthesis'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Matches List -->
                    <div class="space-y-4">
                        @forelse($matches as $m)
                            @php $uni = $m['university']; @endphp
                            <div class="p-5 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-border-subtle">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-700 border border-brand-100 flex items-center justify-center font-heading font-bold text-sm shrink-0">
                                            {{ $uni['acronym'] }}
                                        </div>
                                        <div>
                                            <h3 class="font-heading font-bold text-base text-ink-950">
                                                <a href="{{ route('universities.show', $uni['slug']) }}" class="hover:text-brand-600 transition-colors">
                                                    {{ $uni['name'] }}
                                                </a>
                                            </h3>
                                            <div class="text-xs text-ink-500 font-sans flex items-center gap-2">
                                                <span>{{ $uni['city'] }}, {{ $uni['province_label'] ?? 'Pakistan' }}</span>
                                                <span class="text-ink-300">•</span>
                                                <span class="capitalize">{{ $uni['type'] ?? 'Public' }} Sector</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="shrink-0 flex items-center gap-2">
                                        <span class="text-xs font-mono font-bold text-success-700 bg-success-50 border border-success-200/60 px-2.5 py-1 rounded-full">
                                            {{ $m['relevance_score'] }}% Match
                                        </span>
                                    </div>
                                </div>

                                <!-- AI Rationale Commentary -->
                                <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle text-xs text-ink-700 font-sans flex items-start gap-2">
                                    <x-icon name="message-square" class="w-3.5 h-3.5 text-brand-600 shrink-0 mt-0.5" />
                                    <span>{{ $m['ai_commentary'] }}</span>
                                </div>

                                <!-- Criteria Fit Indicators -->
                                <div class="grid grid-cols-3 gap-2 text-xs font-sans">
                                    <div class="p-2 rounded-lg bg-canvas border border-border-subtle">
                                        <span class="text-[10px] text-ink-400 font-mono block">Field Alignment</span>
                                        <span class="font-semibold text-ink-800">{{ $m['criteria_fit']['discipline'] }}</span>
                                    </div>
                                    <div class="p-2 rounded-lg bg-canvas border border-border-subtle">
                                        <span class="text-[10px] text-ink-400 font-mono block">Location Match</span>
                                        <span class="font-semibold text-ink-800">{{ $m['criteria_fit']['location'] }}</span>
                                    </div>
                                    <div class="p-2 rounded-lg bg-canvas border border-border-subtle">
                                        <span class="text-[10px] text-ink-400 font-mono block">Accreditation</span>
                                        <span class="font-semibold text-ink-800 truncate block">{{ $m['criteria_fit']['accreditation'] }}</span>
                                    </div>
                                </div>

                                <!-- Actions Strip -->
                                <div class="pt-2 flex items-center justify-between text-xs">
                                    <a 
                                        href="{{ route('counselor.chat') }}?prompt=Give+me+a+detailed+breakdown+of+{{ urlencode($uni['name']) }}+admissions+and+fees"
                                        class="inline-flex items-center gap-1 font-semibold text-brand-600 hover:text-brand-700"
                                    >
                                        <x-icon name="sparkles" class="w-3.5 h-3.5" />
                                        <span>Consult Counselor About This University</span>
                                    </a>

                                    <a 
                                        href="{{ route('universities.show', $uni['slug']) }}"
                                        class="inline-flex items-center gap-1 text-ink-600 hover:text-ink-950 font-medium"
                                    >
                                        <span>View Directory Profile</span>
                                        <x-icon name="arrow-right" class="w-3 h-3" />
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 rounded-2xl border border-dashed border-border-default bg-surface text-center space-y-3">
                                <x-icon name="search-x" class="w-8 h-8 text-ink-400 mx-auto" />
                                <h3 class="font-heading font-bold text-base text-ink-900">No institutions matched this query</h3>
                                <p class="text-xs text-ink-500 font-sans max-w-sm mx-auto">
                                    Try broadening your search criteria or adjusting your fee ceiling.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-layouts.public>
