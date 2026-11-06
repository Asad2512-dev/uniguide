<x-layouts.public 
    title="Official Eligibility Determination — UniGuide Pakistan"
    description="Statutory eligibility determination based on Pakistan Engineering Council, NCEAC computing guidelines, and university prospectuses."
>
    <div x-data="{ activeTab: 'all' }" class="min-h-screen bg-canvas pb-24">
        
        <!-- Header Banner & Counts Strip -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Eligibility Checker', 'url' => route('eligibility.index')],
                        ['label' => 'Statutory Determination Results']
                    ]" />
                </div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-semibold mb-2">
                            <x-icon name="shield-check" class="w-3.5 h-3.5 text-emerald-600" />
                            <span>Verified Backend Rules Engine</span>
                            <span class="text-ink-300">•</span>
                            <span class="font-mono text-xs sm:text-[11px] text-emerald-800">PEC & NCEAC Regulated</span>
                        </div>
                        <h1 class="font-heading font-bold text-2xl sm:text-3xl lg:text-4xl text-ink-950 tracking-tight">
                            Official Academic Eligibility Results
                        </h1>
                        <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1 max-w-2xl leading-relaxed">
                            Evaluated against published statutory acts, accreditation council rules, and official institutional admission prospectuses.
                        </p>
                    </div>

                    <!-- Candidate Quick Context Chip -->
                    <div class="shrink-0 p-3.5 rounded-xl border border-border-default bg-surface-subtle text-xs font-sans">
                        <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block font-bold">Evaluated Profile</span>
                        <span class="font-bold text-ink-900 block mt-0.5">
                            {{ $candidate['qualification'] }} ({{ $candidate['academic_group'] }}) • {{ $candidate['fsc_percentage'] }}% FSc
                        </span>
                        <span class="text-xs sm:text-[11px] text-brand-700 font-mono block mt-0.5">
                            Test: {{ $candidate['test_type'] }} (Score: {{ $candidate['test_score'] }})
                        </span>
                    </div>
                </div>

                <!-- Counts & Aggregates Strip -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8 pt-6 border-t border-border-subtle">
                    <div class="p-4 rounded-xl bg-emerald-50/40 border border-emerald-200 flex items-center justify-between">
                        <div>
                            <span class="text-xs sm:text-[11px] font-bold text-emerald-800 uppercase tracking-wider font-mono">Eligible Programs</span>
                            <div class="font-heading font-extrabold text-2xl text-emerald-700 mt-0.5">{{ $counts['eligible'] }}</div>
                            <span class="text-xs sm:text-[10px] text-emerald-600 font-sans">Statutory requirements satisfied</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                            <x-icon name="check-circle" class="w-5 h-5" />
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-warning-50/40 border border-warning-200 flex items-center justify-between">
                        <div>
                            <span class="text-xs sm:text-[11px] font-bold text-warning-800 uppercase tracking-wider font-mono">Conditional / Action Required</span>
                            <div class="font-heading font-extrabold text-2xl text-warning-700 mt-0.5">{{ $counts['conditional'] }}</div>
                            <span class="text-xs sm:text-[10px] text-warning-600 font-sans">Requires deficiency math or IBCC</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-warning-100 text-warning-700 flex items-center justify-center font-bold">
                            <x-icon name="alert-circle" class="w-5 h-5" />
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-surface-subtle border border-border-default flex items-center justify-between">
                        <div>
                            <span class="text-xs sm:text-[11px] font-bold text-ink-600 uppercase tracking-wider font-mono">Ineligible by Statute</span>
                            <div class="font-heading font-extrabold text-2xl text-ink-900 mt-0.5">{{ $counts['ineligible'] }}</div>
                            <span class="text-xs sm:text-[10px] text-ink-600 font-sans">Marks below minimum regulatory cutoff</span>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-surface border border-border-default text-ink-400 flex items-center justify-center font-bold">
                            <x-icon name="x-circle" class="w-5 h-5" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Results Grid -->
        <main class="landing-container mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-8">
            
            <!-- Filter Tabs -->
            <div class="flex items-center justify-between pb-4 border-b border-border-default flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        @click="activeTab = 'all'"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-smooth cursor-pointer"
                        :class="activeTab === 'all' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                    >
                        All Evaluated Programs ({{ $counts['total'] }})
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'eligible'"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-smooth cursor-pointer flex items-center gap-1.5"
                        :class="activeTab === 'eligible' ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                    >
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span>Eligible ({{ $counts['eligible'] }})</span>
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'conditional'"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-smooth cursor-pointer flex items-center gap-1.5"
                        :class="activeTab === 'conditional' ? 'bg-warning-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                    >
                        <span class="w-2 h-2 rounded-full bg-warning-300"></span>
                        <span>Conditional ({{ $counts['conditional'] }})</span>
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'ineligible'"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-smooth cursor-pointer flex items-center gap-1.5"
                        :class="activeTab === 'ineligible' ? 'bg-ink-800 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                    >
                        <span class="w-2 h-2 rounded-full bg-ink-400"></span>
                        <span>Ineligible ({{ $counts['ineligible'] }})</span>
                    </button>
                </div>

                <a 
                    href="{{ route('eligibility.shortlist') }}"
                    class="px-4 py-1.5 rounded-lg border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-brand-700 transition-smooth inline-flex items-center gap-1.5"
                >
                    <x-icon name="bookmark" class="w-3.5 h-3.5 text-brand-600" />
                    <span>View Shortlist Portfolio</span>
                </a>
            </div>

            <!-- 1. ELIGIBLE SECTION -->
            <div x-show="activeTab === 'all' || activeTab === 'eligible'" class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                    <h2 class="font-heading font-bold text-base text-ink-950">Statutorily Eligible Programs</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($results['eligible'] as $el)
                        <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-emerald-200/90 shadow-2xs hover:shadow-card transition-all flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between pb-2 border-b border-emerald-100">
                                    <span class="text-xs font-bold text-emerald-800 font-mono bg-emerald-50 px-2 py-0.5 rounded">
                                        {{ $el['status_label'] }}
                                    </span>
                                    <span class="text-xs text-ink-500 font-mono">{{ $el['verified_date'] }}</span>
                                </div>

                                <div>
                                    <span class="text-xs text-ink-600 uppercase font-mono font-semibold">{{ $el['acronym'] }} • {{ $el['campus'] }}</span>
                                    <h3 class="font-heading font-bold text-base text-ink-950 mt-0.5">{{ $el['program'] }}</h3>
                                    <p class="text-xs text-emerald-700 font-sans mt-1">{{ $el['eligibility_note'] }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-border-subtle text-xs font-sans">
                                    <div>
                                        <span class="text-xs sm:text-[10px] text-ink-600 block font-mono">Calculated Aggregate</span>
                                        <span class="font-bold text-emerald-700 font-mono">{{ $el['calculated_aggregate'] }}% (vs {{ $el['closing_cutoff_ref'] }})</span>
                                    </div>
                                    <div>
                                        <span class="text-xs sm:text-[10px] text-ink-600 block font-mono">Semester Tuition</span>
                                        <span class="font-bold text-ink-900 font-mono">{{ $el['fee_semester'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                                <a href="{{ route('eligibility.rule', $el['applied_rule']) }}" class="text-xs sm:text-[11px] text-ink-500 hover:text-brand-600 underline font-mono truncate max-w-xs">
                                    Rule: {{ $el['rule_title'] }}
                                </a>

                                <form action="{{ route('eligibility.shortlist.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="slug" value="{{ strtolower($el['acronym']) }}" />
                                    <input type="hidden" name="name" value="{{ $el['university'] }}" />
                                    <input type="hidden" name="acronym" value="{{ $el['acronym'] }}" />
                                    <button type="submit" class="px-3 py-1 rounded-lg bg-brand-50 hover:bg-brand-100 text-brand-700 font-semibold cursor-pointer">
                                        + Save to Shortlist
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. CONDITIONAL SECTION -->
            <div x-show="activeTab === 'all' || activeTab === 'conditional'" class="space-y-4 pt-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-warning-500"></span>
                    <h2 class="font-heading font-bold text-base text-ink-950">Conditional Eligibility (Actions Required)</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($results['conditional'] as $cond)
                        <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-warning-200/90 shadow-2xs hover:shadow-card transition-all flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between pb-2 border-b border-warning-100">
                                    <span class="text-xs font-bold text-warning-800 font-mono bg-warning-50 px-2 py-0.5 rounded">
                                        {{ $cond['status_label'] }}
                                    </span>
                                    <span class="text-xs text-ink-500 font-mono">{{ $cond['verified_date'] }}</span>
                                </div>

                                <div>
                                    <span class="text-xs text-ink-600 uppercase font-mono font-semibold">{{ $cond['acronym'] }} • {{ $cond['campus'] }}</span>
                                    <h3 class="font-heading font-bold text-base text-ink-950 mt-0.5">{{ $cond['program'] }}</h3>
                                    <p class="text-xs text-warning-800 font-sans mt-1 leading-relaxed">{{ $cond['condition_reason'] }}</p>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                                <a href="{{ route('eligibility.rule', $cond['applied_rule']) }}" class="text-xs sm:text-[11px] text-ink-500 hover:text-brand-600 underline font-mono">
                                    Inspect Legal Clause →
                                </a>

                                <a href="{{ route('counselor.chat') }}?prompt=How+do+I+satisfy+conditions+for+{{ urlencode($cond['program']) }}+at+{{ urlencode($cond['acronym']) }}" class="text-brand-600 hover:text-brand-700 font-semibold">
                                    Ask AI Guidance
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-4 sm:p-6 rounded-xl border border-dashed border-border-default text-center text-xs text-ink-400">
                            No conditional requirements flagged.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 3. INELIGIBLE SECTION -->
            <div x-show="activeTab === 'all' || activeTab === 'ineligible'" class="space-y-4 pt-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-ink-400"></span>
                    <h2 class="font-heading font-bold text-base text-ink-950">Statutorily Ineligible Programs</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($results['ineligible'] as $inelig)
                        <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default hover:border-border-strong transition-all flex flex-col justify-between">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between pb-2 border-b border-border-subtle">
                                    <span class="text-xs font-bold text-ink-700 font-mono bg-surface-subtle px-2 py-0.5 rounded">
                                        {{ $inelig['status_label'] }}
                                    </span>
                                    <span class="text-xs text-ink-600 font-mono">{{ $inelig['verified_date'] }}</span>
                                </div>

                                <div>
                                    <span class="text-xs text-ink-600 uppercase font-mono font-semibold">{{ $inelig['acronym'] }} • {{ $inelig['campus'] }}</span>
                                    <h3 class="font-heading font-bold text-base text-ink-950 mt-0.5">{{ $inelig['program'] }}</h3>
                                    <p class="text-xs text-error-700 font-sans mt-1 leading-relaxed">{{ $inelig['unmet_reason'] }}</p>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                                <a href="{{ route('eligibility.rule', $inelig['applied_rule']) }}" class="text-xs sm:text-[11px] text-ink-500 hover:text-brand-600 underline font-mono">
                                    Inspect Mandatory Statute →
                                </a>

                                <a href="{{ route('counselor.chat') }}?prompt=What+alternatives+exist+since+I+am+ineligible+for+{{ urlencode($inelig['program']) }}" class="text-brand-600 hover:text-brand-700 font-semibold">
                                    Explore Alternatives
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-4 sm:p-6 rounded-xl border border-dashed border-border-default text-center text-xs text-ink-400">
                            Congratulations! No ineligible programs found.
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</x-layouts.public>

