<x-layouts.public 
    title="Your Strategic Recommendation Tiers — UniGuide Pakistan"
    description="Personalized Pakistani university admission recommendations categorized into Reach, Match, and Safety tiers with statutory aggregate calculations."
>
    <div x-data="{ activeTab: 'all' }" class="min-h-screen bg-canvas pb-24">
        
        <div class="mx-4 mt-4 rounded-xl border border-warning-200 bg-warning-50 p-4 text-sm leading-relaxed text-warning-800" role="note">Planning illustration: this assessment uses existing preset calculations. It does not establish verified eligibility or guarantee admission. Confirm current requirements and financial-aid terms with the institution.</div>
        <!-- Header Banner & Calculated Merit Overview -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'AI Counselor', 'url' => route('counselor.index')],
                        ['label' => 'Guided Assessment', 'url' => route('counselor.guided')],
                        ['label' => 'Strategic Recommendation Tiers', 'url' => '#']
                    ]" />
                </div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                            <x-icon name="sparkles" class="w-3.5 h-3.5 text-brand-600" />
                            <span>AI Strategic Evaluation Plan</span>
                            <span class="text-ink-300">•</span>
                            <span class="font-mono text-xs sm:text-[11px] text-brand-800">Fall 2026 Intake</span>
                        </div>
                        <h1 class="font-heading font-bold text-2xl sm:text-3xl lg:text-4xl text-ink-950 tracking-tight">
                            Your Admission Likelihood Tiers
                        </h1>
                        <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1 max-w-2xl leading-relaxed">
                            {{ $summaryVerdict }}
                        </p>
                    </div>

                    <!-- User Profile Quick Chip -->
                    <div class="shrink-0 p-3.5 rounded-xl border border-border-default bg-surface-subtle flex flex-wrap items-center gap-4 text-xs font-sans">
                        <div>
                            <span class="text-ink-600 block text-xs sm:text-[10px] uppercase font-mono">Academic Input</span>
                            <span class="font-bold text-ink-900">{{ $profile['matric_percentage'] }}% Matric • {{ $profile['fsc_percentage'] }}% FSc</span>
                        </div>
                        <div class="border-l border-border-default pl-4">
                            <span class="text-ink-600 block text-xs sm:text-[10px] uppercase font-mono">Target Test</span>
                            <span class="font-bold text-ink-900">{{ $profile['test_type'] }} (Score: {{ $profile['test_score'] }})</span>
                        </div>
                        <div class="border-l border-border-default pl-4">
                            <span class="text-ink-600 block text-xs sm:text-[10px] uppercase font-mono">Budget Cap</span>
                            <span class="font-bold text-brand-700">PKR {{ number_format($profile['budget_max']) }}/sem</span>
                        </div>
                        <div class="border-l border-border-default pl-4">
                            <a href="{{ route('counselor.guided') }}" class="text-brand-600 hover:text-brand-700 font-semibold underline text-xs">
                                Edit Inputs
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Aggregates Grid Across Key Institutions -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 mt-8 pt-6 border-t border-border-subtle">
                    @foreach($aggregates as $key => $agg)
                        <div class="p-3.5 rounded-xl border border-brand-100 bg-brand-50/20">
                            <span class="text-xs sm:text-[10px] uppercase tracking-wider text-ink-500 font-mono font-semibold block truncate">
                                {{ $agg['label'] }}
                            </span>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="font-heading font-extrabold text-2xl text-brand-700">{{ $agg['score'] }}</span>
                                <span class="text-xs font-mono text-brand-600 font-bold">%</span>
                            </div>
                            <span class="text-xs sm:text-[10px] text-ink-600 font-sans block mt-1 truncate" title="{{ $agg['formula'] }}">
                                {{ $agg['formula'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Main Tiers & Program Recommendations -->
        <main class="landing-container mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            
            <!-- Tier Filter Pills -->
            <div class="flex items-center justify-between pb-6 border-b border-border-default flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        @click="activeTab = 'all'"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-smooth cursor-pointer"
                        :class="activeTab === 'all' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                    >
                        All Tiers ({{ count($tiers['reach']) + count($tiers['match']) + count($tiers['safety']) }})
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'reach'"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-smooth cursor-pointer flex items-center gap-1.5"
                        :class="activeTab === 'reach' ? 'bg-amber-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                    >
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span>Reach ({{ count($tiers['reach']) }})</span>
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'match'"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-smooth cursor-pointer flex items-center gap-1.5"
                        :class="activeTab === 'match' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                    >
                        <span class="w-2 h-2 rounded-full bg-brand-300"></span>
                        <span>Target Match ({{ count($tiers['match']) }})</span>
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'safety'"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-smooth cursor-pointer flex items-center gap-1.5"
                        :class="activeTab === 'safety' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                    >
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Safety ({{ count($tiers['safety']) }})</span>
                    </button>
                </div>

                <!-- Export / Consult Actions -->
                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        onclick="window.print()" 
                        class="px-3 py-1.5 rounded-lg border border-border-default bg-surface hover:bg-surface-subtle text-xs font-sans text-ink-700 transition-smooth inline-flex items-center gap-1.5 cursor-pointer"
                    >
                        <x-icon name="printer" class="w-3.5 h-3.5 text-ink-400" />
                        <span>Print Strategy</span>
                    </button>
                    <a 
                        href="{{ route('counselor.chat') }}"
                        class="px-3 py-1.5 rounded-lg bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold font-sans transition-smooth inline-flex items-center gap-1.5 shadow-2xs"
                    >
                        <x-icon name="sparkles" class="w-3.5 h-3.5" />
                        <span>Discuss with AI Advisor</span>
                    </a>
                </div>
            </div>

            <!-- TIER 1: REACH INSTITUTIONS -->
            <div x-show="activeTab === 'all' || activeTab === 'reach'" class="mt-8 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                        <h2 class="font-heading font-bold text-lg text-ink-950">Reach Programs (High Ambition / Competitive)</h2>
                        <span class="text-xs text-ink-600 font-mono">Cutoff is near or slightly above preliminary aggregate</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($tiers['reach'] as $item)
                        <div class="p-5 rounded-2xl bg-surface border border-amber-200/80 shadow-2xs hover:shadow-card transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-2 mb-2 border-b border-border-subtle">
                                    <span class="text-xs font-bold text-amber-700 font-mono bg-amber-50 px-2 py-0.5 rounded">
                                        Reach Choice
                                    </span>
                                    <span class="text-xs text-ink-500 font-mono">Cutoff: {{ $item['closing_cutoff_2025'] }}</span>
                                </div>

                                <div class="space-y-1">
                                    <span class="text-xs sm:text-[11px] text-ink-600 uppercase font-mono font-semibold">{{ $item['acronym'] }} • {{ $item['campus'] }}</span>
                                    <h3 class="font-heading font-bold text-base text-ink-950">
                                        {{ $item['program'] }}
                                    </h3>
                                    <p class="text-xs text-ink-600 font-sans line-clamp-2 mt-1">
                                        {{ $item['rationale'] }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-border-subtle text-xs font-sans">
                                    <div>
                                        <span class="text-xs sm:text-[10px] text-ink-600 block font-mono">Calculated Aggregate</span>
                                        <span class="font-bold text-amber-700 font-mono">{{ $item['calculated_aggregate'] }}%</span>
                                    </div>
                                    <div>
                                        <span class="text-xs sm:text-[10px] text-ink-600 block font-mono">Semester Tuition</span>
                                        <span class="font-bold text-ink-900 font-mono">{{ $item['fee_semester'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                                <span class="text-xs sm:text-[11px] text-ink-600 font-mono">{{ $item['accreditation'] }}</span>
                                <div class="flex items-center gap-2">
                                    <a 
                                        href="{{ route('counselor.chat') }}?prompt=How+can+I+improve+my+chances+for+{{ urlencode($item['program']) }}+at+{{ urlencode($item['acronym']) }}%3F"
                                        class="font-semibold text-brand-600 hover:text-brand-700"
                                    >
                                        Ask AI Strategy →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 p-4 sm:p-6 rounded-xl border border-dashed border-border-default text-center text-xs text-ink-400">
                            No reach institutions in current filter.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- TIER 2: TARGET MATCH INSTITUTIONS -->
            <div x-show="activeTab === 'all' || activeTab === 'match'" class="mt-12 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-brand-600"></div>
                        <h2 class="font-heading font-bold text-lg text-ink-950">Target Match Programs (High Probability 60%–85%)</h2>
                        <span class="text-xs text-ink-600 font-mono">Calculated aggregate matches or comfortably clears closing cutoffs</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($tiers['match'] as $item)
                        <div class="p-5 rounded-2xl bg-surface border border-brand-200 shadow-2xs hover:shadow-card transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-2 mb-2 border-b border-brand-100/70">
                                    <span class="text-xs font-bold text-brand-700 font-mono bg-brand-50 px-2 py-0.5 rounded">
                                        Prime Match (Likely Admitted)
                                    </span>
                                    <span class="text-xs text-ink-500 font-mono">Cutoff: {{ $item['closing_cutoff_2025'] }}</span>
                                </div>

                                <div class="space-y-1">
                                    <span class="text-xs sm:text-[11px] text-ink-600 uppercase font-mono font-semibold">{{ $item['acronym'] }} • {{ $item['campus'] }}</span>
                                    <h3 class="font-heading font-bold text-base text-ink-950">
                                        {{ $item['program'] }}
                                    </h3>
                                    <p class="text-xs text-ink-600 font-sans line-clamp-2 mt-1">
                                        {{ $item['rationale'] }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-border-subtle text-xs font-sans">
                                    <div>
                                        <span class="text-xs sm:text-[10px] text-ink-600 block font-mono">Calculated Aggregate</span>
                                        <span class="font-bold text-brand-700 font-mono">{{ $item['calculated_aggregate'] }}% (+{{ $item['diff'] }}% Margin)</span>
                                    </div>
                                    <div>
                                        <span class="text-xs sm:text-[10px] text-ink-600 block font-mono">Semester Tuition</span>
                                        <span class="font-bold text-ink-900 font-mono">{{ $item['fee_semester'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                                <span class="text-xs sm:text-[11px] text-ink-600 font-mono">{{ $item['accreditation'] }}</span>
                                <div class="flex items-center gap-2">
                                    <a 
                                        href="{{ route('universities.show', $item['slug']) }}"
                                        class="font-semibold text-brand-600 hover:text-brand-700"
                                    >
                                        University Profile →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 p-4 sm:p-6 rounded-xl border border-dashed border-border-default text-center text-xs text-ink-400">
                            No match programs found.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- TIER 3: SAFETY INSTITUTIONS -->
            <div x-show="activeTab === 'all' || activeTab === 'safety'" class="mt-12 space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-emerald-600"></div>
                        <h2 class="font-heading font-bold text-lg text-ink-950">Safety Programs (Guaranteed >90% Probability)</h2>
                        <span class="text-xs text-ink-600 font-mono">Aggregate comfortably exceeds previous year closing thresholds</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($tiers['safety'] as $item)
                        <div class="p-5 rounded-2xl bg-surface border border-emerald-200/80 shadow-2xs hover:shadow-card transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-2 mb-2 border-b border-border-subtle">
                                    <span class="text-xs font-bold text-emerald-700 font-mono bg-emerald-50 px-2 py-0.5 rounded">
                                        Safety Choice
                                    </span>
                                    <span class="text-xs text-ink-500 font-mono">Cutoff: {{ $item['closing_cutoff_2025'] }}</span>
                                </div>

                                <div class="space-y-1">
                                    <span class="text-xs sm:text-[11px] text-ink-600 uppercase font-mono font-semibold">{{ $item['acronym'] }} • {{ $item['campus'] }}</span>
                                    <h3 class="font-heading font-bold text-base text-ink-950">
                                        {{ $item['program'] }}
                                    </h3>
                                    <p class="text-xs text-ink-600 font-sans line-clamp-2 mt-1">
                                        {{ $item['rationale'] }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-border-subtle text-xs font-sans">
                                    <div>
                                        <span class="text-xs sm:text-[10px] text-ink-600 block font-mono">Calculated Aggregate</span>
                                        <span class="font-bold text-emerald-700 font-mono">{{ $item['calculated_aggregate'] }}%</span>
                                    </div>
                                    <div>
                                        <span class="text-xs sm:text-[10px] text-ink-600 block font-mono">Semester Tuition</span>
                                        <span class="font-bold text-ink-900 font-mono">{{ $item['fee_semester'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                                <span class="text-xs sm:text-[11px] text-ink-600 font-mono">{{ $item['accreditation'] }}</span>
                                <div class="flex items-center gap-2">
                                    <a 
                                        href="{{ route('universities.show', $item['slug']) }}"
                                        class="font-semibold text-brand-600 hover:text-brand-700"
                                    >
                                        University Profile →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 p-4 sm:p-6 rounded-xl border border-dashed border-border-default text-center text-xs text-ink-400">
                            No safety programs found in this tier.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Matched Scholarships & Financial Aid Callout -->
            @if(count($scholarships) > 0)
                <div class="mt-14 rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50/50 via-surface to-emerald-50/30 p-6 sm:p-8 shadow-card">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold">
                            <x-icon name="award" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-lg text-ink-950">Qualifying Financial Aid & Scholarships</h3>
                            <p class="text-xs text-ink-600 font-sans">
                                Based on your declared monthly household income of PKR {{ number_format($profile['monthly_income']) }}.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-6">
                        @foreach($scholarships as $sch)
                            <div class="p-4 rounded-xl bg-surface border border-emerald-200/80 shadow-2xs">
                                <span class="text-xs sm:text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-mono">
                                    {{ $sch['status'] }}
                                </span>
                                <h4 class="font-heading font-bold text-sm text-ink-950 mt-2">{{ $sch['name'] }}</h4>
                                <p class="text-xs text-ink-600 font-sans mt-1">{{ $sch['coverage'] }}</p>
                                <div class="mt-3 pt-2 border-t border-border-subtle">
                                    <a href="{{ route('scholarships.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                                        Check Application Requirements →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Evidence Source Footnote Strip -->
            <div class="mt-12 p-4 rounded-xl bg-surface-subtle border border-border-default flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs font-sans text-ink-500">
                <div class="flex items-center gap-2">
                    <x-icon name="shield-check" class="w-4 h-4 text-brand-600 shrink-0" />
                    <span>Cutoff calibrations derived from official university merit gazettes (2023–2025). Actual 2026 cutoffs may vary.</span>
                </div>
                <a href="{{ route('counselor.evidence') }}" class="font-semibold text-brand-600 hover:text-brand-700 shrink-0">
                    Audit Statutory Sources
                </a>
            </div>
        </main>
    </div>
</x-layouts.public>

