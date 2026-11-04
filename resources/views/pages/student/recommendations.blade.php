<x-layouts.student 
    title="Personalized Recommendations — UniGuide Pakistan"
    description="Your strategic Reach, Match, and Safety university tiers calibrated against your verified Matric, FSc, and NET scores."
>
    <div x-data="{ activeTab: 'all' }" class="space-y-8 pb-12">
        
        <!-- Header Banner & Calculated Aggregates Strip -->
        <div class="rounded-2xl border border-brand-200/80 bg-gradient-to-r from-surface via-surface to-brand-50/40 p-6 sm:p-8 shadow-card">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                        <x-icon name="compass" class="w-3.5 h-3.5 text-brand-600" />
                        <span>Calibrated Strategy Plan</span>
                        <span class="text-ink-300">•</span>
                        <span class="font-mono text-[11px] text-brand-800">Fall 2026 Intake</span>
                    </div>

                    <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                        Personalized University Recommendations
                    </h1>
                    <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1 max-w-2xl leading-relaxed">
                        {{ $summaryVerdict }}
                    </p>
                </div>

                <div class="shrink-0 p-3.5 rounded-xl border border-border-default bg-surface shadow-2xs text-xs font-sans">
                    <span class="text-[10px] text-ink-400 uppercase font-mono block">Profile Snapshot</span>
                    <span class="font-bold text-ink-900 block mt-0.5">88% Matric • 81% FSc • NET 152</span>
                    <span class="text-[11px] text-brand-700 font-mono block mt-0.5">Cap: PKR 200k/semester</span>
                </div>
            </div>

            <!-- Aggregates Grid Across Key Institutions -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 mt-8 pt-6 border-t border-border-subtle">
                @foreach($aggregates as $key => $agg)
                    <div class="p-3.5 rounded-xl border border-brand-100 bg-surface shadow-2xs">
                        <span class="text-[10px] uppercase tracking-wider text-ink-500 font-mono font-semibold block truncate">
                            {{ $agg['label'] }}
                        </span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="font-heading font-extrabold text-2xl text-brand-700">{{ $agg['score'] }}</span>
                            <span class="text-xs font-mono text-brand-600 font-bold">%</span>
                        </div>
                        <span class="text-[10px] text-ink-400 font-sans block mt-1 truncate" title="{{ $agg['formula'] }}">
                            {{ $agg['formula'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="flex items-center justify-between pb-4 border-b border-border-default flex-wrap gap-4">
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

            <a 
                href="{{ route('counselor.chat') }}"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-brand-600 text-white text-xs font-semibold shadow-2xs hover:bg-brand-700 transition-smooth"
            >
                <x-icon name="sparkles" class="w-3.5 h-3.5" />
                <span>Discuss with Counselor</span>
            </a>
        </div>

        <!-- 1. REACH TIER -->
        <div x-show="activeTab === 'all' || activeTab === 'reach'" class="space-y-4">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                <h2 class="font-heading font-bold text-base text-ink-950">Reach Programs (Competitive / Dream Choice)</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($tiers['reach'] as $item)
                    <div class="p-5 rounded-2xl bg-surface border border-amber-200/80 shadow-2xs hover:shadow-card transition-all flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-amber-700 font-mono bg-amber-50 px-2 py-0.5 rounded">
                                    Reach Choice
                                </span>
                                <span class="text-xs text-ink-500 font-mono">Cutoff: {{ $item['closing_cutoff_2025'] }}</span>
                            </div>

                            <div>
                                <span class="text-[11px] text-ink-400 uppercase font-mono font-semibold">{{ $item['acronym'] }} • {{ $item['campus'] }}</span>
                                <h3 class="font-heading font-bold text-base text-ink-950 mt-0.5">{{ $item['program'] }}</h3>
                                <p class="text-xs text-ink-600 font-sans mt-1 line-clamp-2">{{ $item['rationale'] }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-border-subtle text-xs font-sans">
                                <div>
                                    <span class="text-[10px] text-ink-400 block font-mono">Calculated Aggregate</span>
                                    <span class="font-bold text-amber-700 font-mono">{{ $item['calculated_aggregate'] }}%</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-ink-400 block font-mono">Semester Tuition</span>
                                    <span class="font-bold text-ink-900 font-mono">{{ $item['fee_semester'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                            <form action="{{ route('student.tracker.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="university_name" value="{{ $item['university'] }}" />
                                <input type="hidden" name="university_acronym" value="{{ $item['acronym'] }}" />
                                <input type="hidden" name="university_slug" value="{{ $item['slug'] }}" />
                                <input type="hidden" name="program_name" value="{{ $item['program'] }}" />
                                <input type="hidden" name="campus" value="{{ $item['campus'] }}" />
                                <input type="hidden" name="stage" value="interested" />
                                <button type="submit" class="text-brand-600 hover:text-brand-700 font-semibold cursor-pointer">
                                    + Add to Tracker
                                </button>
                            </form>

                            <a href="{{ route('universities.show', $item['slug']) }}" class="text-ink-600 hover:text-ink-950 font-medium">
                                Profile →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 2. TARGET MATCH TIER -->
        <div x-show="activeTab === 'all' || activeTab === 'match'" class="space-y-4 pt-4">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-brand-600"></span>
                <h2 class="font-heading font-bold text-base text-ink-950">Target Match Programs (High Probability 60%–85%)</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($tiers['match'] as $item)
                    <div class="p-5 rounded-2xl bg-surface border border-brand-200 shadow-2xs hover:shadow-card transition-all flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-brand-700 font-mono bg-brand-50 px-2 py-0.5 rounded">
                                    Prime Target Match
                                </span>
                                <span class="text-xs text-ink-500 font-mono">Cutoff: {{ $item['closing_cutoff_2025'] }}</span>
                            </div>

                            <div>
                                <span class="text-[11px] text-ink-400 uppercase font-mono font-semibold">{{ $item['acronym'] }} • {{ $item['campus'] }}</span>
                                <h3 class="font-heading font-bold text-base text-ink-950 mt-0.5">{{ $item['program'] }}</h3>
                                <p class="text-xs text-ink-600 font-sans mt-1 line-clamp-2">{{ $item['rationale'] }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-border-subtle text-xs font-sans">
                                <div>
                                    <span class="text-[10px] text-ink-400 block font-mono">Calculated Aggregate</span>
                                    <span class="font-bold text-brand-700 font-mono">{{ $item['calculated_aggregate'] }}%</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-ink-400 block font-mono">Semester Tuition</span>
                                    <span class="font-bold text-ink-900 font-mono">{{ $item['fee_semester'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                            <form action="{{ route('student.tracker.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="university_name" value="{{ $item['university'] }}" />
                                <input type="hidden" name="university_acronym" value="{{ $item['acronym'] }}" />
                                <input type="hidden" name="university_slug" value="{{ $item['slug'] }}" />
                                <input type="hidden" name="program_name" value="{{ $item['program'] }}" />
                                <input type="hidden" name="campus" value="{{ $item['campus'] }}" />
                                <input type="hidden" name="stage" value="interested" />
                                <button type="submit" class="text-brand-600 hover:text-brand-700 font-semibold cursor-pointer">
                                    + Add to Tracker
                                </button>
                            </form>

                            <a href="{{ route('universities.show', $item['slug']) }}" class="text-ink-600 hover:text-ink-950 font-medium">
                                Profile →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 3. SAFETY TIER -->
        <div x-show="activeTab === 'all' || activeTab === 'safety'" class="space-y-4 pt-4">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-emerald-600"></span>
                <h2 class="font-heading font-bold text-base text-ink-950">Safety Programs (Guaranteed >90% Probability)</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($tiers['safety'] as $item)
                    <div class="p-5 rounded-2xl bg-surface border border-emerald-200/80 shadow-2xs hover:shadow-card transition-all flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-emerald-700 font-mono bg-emerald-50 px-2 py-0.5 rounded">
                                    Safety Choice
                                </span>
                                <span class="text-xs text-ink-500 font-mono">Cutoff: {{ $item['closing_cutoff_2025'] }}</span>
                            </div>

                            <div>
                                <span class="text-[11px] text-ink-400 uppercase font-mono font-semibold">{{ $item['acronym'] }} • {{ $item['campus'] }}</span>
                                <h3 class="font-heading font-bold text-base text-ink-950 mt-0.5">{{ $item['program'] }}</h3>
                                <p class="text-xs text-ink-600 font-sans mt-1 line-clamp-2">{{ $item['rationale'] }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-border-subtle text-xs font-sans">
                                <div>
                                    <span class="text-[10px] text-ink-400 block font-mono">Calculated Aggregate</span>
                                    <span class="font-bold text-emerald-700 font-mono">{{ $item['calculated_aggregate'] }}%</span>
                                </div>
                                <div>
                                    <span class="text-[10px] text-ink-400 block font-mono">Semester Tuition</span>
                                    <span class="font-bold text-ink-900 font-mono">{{ $item['fee_semester'] }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                            <form action="{{ route('student.tracker.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="university_name" value="{{ $item['university'] }}" />
                                <input type="hidden" name="university_acronym" value="{{ $item['acronym'] }}" />
                                <input type="hidden" name="university_slug" value="{{ $item['slug'] }}" />
                                <input type="hidden" name="program_name" value="{{ $item['program'] }}" />
                                <input type="hidden" name="campus" value="{{ $item['campus'] }}" />
                                <input type="hidden" name="stage" value="interested" />
                                <button type="submit" class="text-brand-600 hover:text-brand-700 font-semibold cursor-pointer">
                                    + Add to Tracker
                                </button>
                            </form>

                            <a href="{{ route('universities.show', $item['slug']) }}" class="text-ink-600 hover:text-ink-950 font-medium">
                                Profile →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.student>
