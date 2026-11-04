<x-layouts.student 
    title="Candidate Command Center — UniGuide Pakistan"
    description="Personalized admissions overview, eligibility scorecards, active application tracker, and scholarship notifications."
>
    <div class="space-y-8 pb-12">
        
        <!-- Welcome Hero & Status Strip -->
        <div class="rounded-2xl border border-brand-200/80 bg-gradient-to-r from-surface via-surface to-brand-50/40 p-6 sm:p-8 shadow-card relative overflow-hidden">
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-success-50 border border-success-200/80 text-success-700 text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full bg-success-600 animate-pulse"></span>
                            <span>Fall 2026 Cycle Active</span>
                        </span>
                        <span class="text-xs font-mono text-ink-400">ID: {{ $profile['applicant_id'] }}</span>
                    </div>
                    
                    <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                        Welcome back, {{ $profile['full_name'] }}
                    </h1>
                    <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                        {{ $profile['stream_label'] }} • Target: Computing & Software Engineering • Islamabad / Rawalpindi
                    </p>
                </div>

                <!-- Profile Completeness Score Card -->
                <div class="shrink-0 p-4 rounded-xl border border-border-default bg-surface shadow-2xs flex items-center gap-4">
                    <div class="relative w-12 h-12 flex items-center justify-center">
                        <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                            <path class="text-surface-subtle" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-brand-600 transition-all duration-500" stroke-width="3" stroke-dasharray="{{ $completeness['percentage'] }}, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <span class="absolute font-mono font-bold text-xs text-ink-950">{{ $completeness['percentage'] }}%</span>
                    </div>

                    <div>
                        <div class="text-xs font-bold text-ink-950 font-heading">Profile Completeness</div>
                        <p class="text-[11px] text-ink-500 font-sans mt-0.5">
                            {{ $completeness['is_ready_for_admissions'] ? 'Ready for Institutional Merit' : 'Pending Documents' }}
                        </p>
                        <a href="{{ route('student.profile') }}" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700 underline block mt-0.5">
                            Update Credentials →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- High-Priority Suggested Actions (Next Steps) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 rounded-xl border border-warning-200 bg-warning-50/30 flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-warning-100 text-warning-800 flex items-center justify-center shrink-0">
                    <x-icon name="calendar-clock" class="w-4 h-4" />
                </div>
                <div class="space-y-1 text-xs font-sans">
                    <span class="font-bold text-ink-950 block">FAST-NU Test Scheduled</span>
                    <p class="text-ink-600">Admit Card Roll #NU-ISB-99201 generated for 08 July 2026.</p>
                    <a href="{{ route('student.tracker') }}" class="text-brand-700 font-semibold underline block pt-0.5">View Admit Card Details</a>
                </div>
            </div>

            <div class="p-4 rounded-xl border border-brand-200 bg-brand-50/20 flex items-start gap-3">
                <div class="w-8 h-8 rounded-lg bg-brand-100 text-brand-800 flex items-center justify-center shrink-0">
                    <x-icon name="award" class="w-4 h-4" />
                </div>
                <div class="space-y-1 text-xs font-sans">
                    <span class="font-bold text-ink-950 block">HEC Need-Based Aid Eligible</span>
                    <p class="text-ink-600">Income < PKR 45,000 matches 100% full tuition subsidy criteria.</p>
                    <a href="{{ route('student.saved.scholarships') }}" class="text-brand-700 font-semibold underline block pt-0.5">Check Document Checklist</a>
                </div>
            </div>

            <div class="p-4 rounded-xl border border-border-default bg-surface flex items-start gap-3 shadow-2xs">
                <div class="w-8 h-8 rounded-lg bg-surface-subtle text-ink-600 flex items-center justify-center shrink-0">
                    <x-icon name="sparkles" class="w-4 h-4 text-brand-600" />
                </div>
                <div class="space-y-1 text-xs font-sans">
                    <span class="font-bold text-ink-950 block">Target NET-4 Score Simulation</span>
                    <p class="text-ink-600">A score of 156+ will place your NUST aggregate at 79.5%.</p>
                    <a href="{{ route('counselor.chat', 'conv-1') }}" class="text-brand-700 font-semibold underline block pt-0.5">Ask AI Counselor</a>
                </div>
            </div>
        </div>

        <!-- Two-Column Command Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left 8 Cols: Admission Tracker & Calculated Aggregates -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Admission Tracker Summary -->
                <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-5">
                    <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center font-bold">
                                <x-icon name="file-text" class="w-4 h-4" />
                            </div>
                            <div>
                                <h2 class="font-heading font-bold text-base text-ink-950">Active Applications Tracker</h2>
                                <p class="text-xs text-ink-500 font-sans">Self-managed Pakistani university admission lifecycles.</p>
                            </div>
                        </div>

                        <a href="{{ route('student.tracker') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                            Manage All ({{ count($applications) }}) →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach(array_slice($applications, 0, 4) as $app)
                            <div class="p-4 rounded-xl border border-border-default bg-surface hover:border-brand-200 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-heading font-bold text-sm text-ink-950">{{ $app['university_acronym'] }}</span>
                                        <span class="text-ink-300">•</span>
                                        <span class="text-xs font-semibold text-ink-800">{{ $app['program_name'] }}</span>
                                    </div>
                                    <div class="text-[11px] text-ink-500 font-sans">
                                        {{ $app['campus'] }} • Ref: <span class="font-mono text-ink-700">{{ $app['application_ref'] }}</span>
                                    </div>
                                    <div class="text-[11px] text-ink-400 font-sans">
                                        Challan: <span class="font-medium text-ink-700">{{ $app['fee_challan_status'] }}</span>
                                    </div>
                                </div>

                                <div class="shrink-0 flex sm:flex-col sm:items-end justify-between items-center gap-2">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold font-mono {{ match($app['stage']) {
                                        'offer_received' => 'bg-success-50 text-success-700 border border-success-200/80',
                                        'test_scheduled' => 'bg-warning-50 text-warning-700 border border-warning-200/80',
                                        'applied' => 'bg-brand-50 text-brand-700 border border-brand-200/80',
                                        default => 'bg-surface-subtle text-ink-700 border border-border-default',
                                    } }}">
                                        {{ $app['stage_label'] }}
                                    </span>
                                    <span class="text-[11px] font-mono text-ink-400">{{ $app['deadline'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Calculated Aggregates Overview -->
                <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                        <div class="flex items-center gap-2">
                            <x-icon name="calculator" class="w-4 h-4 text-brand-600" />
                            <h2 class="font-heading font-bold text-base text-ink-950">Statutory Aggregates Scorecard</h2>
                        </div>
                        <a href="{{ route('student.recommendations') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                            View Tiers & Cutoffs →
                        </a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="p-3.5 rounded-xl bg-brand-50/20 border border-brand-100">
                            <span class="text-[10px] uppercase font-mono font-bold text-ink-500 block truncate">NUST SEECS</span>
                            <div class="font-heading font-bold text-xl text-brand-700 mt-1">77.95%</div>
                            <span class="text-[10px] text-ink-400 font-sans block mt-0.5">NET 152 • FSc 81%</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-brand-50/20 border border-brand-100">
                            <span class="text-[10px] uppercase font-mono font-bold text-ink-500 block truncate">FAST Islamabad</span>
                            <div class="font-heading font-bold text-xl text-brand-700 mt-1">75.50%</div>
                            <span class="text-[10px] text-ink-400 font-sans block mt-0.5">Test 74 • FSc 81%</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-brand-50/20 border border-brand-100">
                            <span class="text-[10px] uppercase font-mono font-bold text-ink-500 block truncate">COMSATS CUI</span>
                            <div class="font-heading font-bold text-xl text-brand-700 mt-1">83.60%</div>
                            <span class="text-[10px] text-ink-400 font-sans block mt-0.5">NAT 78 • Safe Match</span>
                        </div>

                        <div class="p-3.5 rounded-xl bg-brand-50/20 border border-brand-100">
                            <span class="text-[10px] uppercase font-mono font-bold text-ink-500 block truncate">UET Lahore</span>
                            <div class="font-heading font-bold text-xl text-brand-700 mt-1">81.20%</div>
                            <span class="text-[10px] text-ink-400 font-sans block mt-0.5">FSc 81% + ECAT</span>
                        </div>
                    </div>
                </div>

                <!-- Recommended Universities Grid -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-heading font-bold text-base text-ink-950">Recommended Universities for You</h2>
                            <p class="text-xs text-ink-500 font-sans">Based on your FSc Pre-Engineering credentials and PKR 200k fee cap.</p>
                        </div>
                        <a href="{{ route('student.recommendations') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                            Explore Recommendations →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach(array_slice($savedUnis, 0, 2) as $uni)
                            <div class="p-5 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="font-heading font-bold text-xs uppercase px-2 py-0.5 rounded bg-brand-50 text-brand-700 font-mono">
                                            {{ $uni['hec_ranking'] }}
                                        </span>
                                        <span class="text-[11px] font-mono text-ink-400">{{ $uni['deadline'] }}</span>
                                    </div>
                                    <h3 class="font-heading font-bold text-base text-ink-950">
                                        <a href="{{ route('universities.show', $uni['slug']) }}" class="hover:text-brand-600">
                                            {{ $uni['name'] }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-ink-500 font-sans">
                                        {{ $uni['city'] }} • Tuition: <span class="font-medium text-ink-800">{{ $uni['fee_range'] }}</span>
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-border-subtle flex items-center justify-between text-xs mt-4">
                                    <span class="font-semibold text-brand-600">{{ $uni['match_tier'] }}</span>
                                    <a href="{{ route('universities.show', $uni['slug']) }}" class="font-medium text-ink-700 hover:text-brand-600">
                                        View Profile →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right 4 Cols: Deadlines, Scholarships & AI Shortcuts -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Upcoming Deadlines Card -->
                <div class="p-5 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-border-subtle">
                        <div class="flex items-center gap-2">
                            <x-icon name="calendar-clock" class="w-4 h-4 text-brand-600" />
                            <h3 class="font-heading font-bold text-sm text-ink-950">Upcoming Deadlines</h3>
                        </div>
                        <a href="{{ route('admissions.calendar') }}" class="text-[11px] font-semibold text-brand-600">Calendar →</a>
                    </div>

                    <div class="space-y-3">
                        @foreach($upcomingDeadlines as $dead)
                            <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle text-xs font-sans space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-ink-900">{{ $dead['university_acronym'] }}</span>
                                    <span class="text-[10px] font-mono font-bold text-warning-700 bg-warning-50 px-1.5 py-0.2 rounded">
                                        {{ $dead['deadline'] }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-ink-600">{{ $dead['program_name'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Qualifying Scholarships Card -->
                <div class="p-5 rounded-2xl bg-gradient-to-b from-emerald-50/40 via-surface to-surface border border-emerald-200/80 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-emerald-100">
                        <div class="flex items-center gap-2">
                            <x-icon name="award" class="w-4 h-4 text-emerald-600" />
                            <h3 class="font-heading font-bold text-sm text-ink-950">Financial Aid Matches</h3>
                        </div>
                        <span class="text-[10px] font-mono text-emerald-700 font-bold">Income Match</span>
                    </div>

                    <div class="space-y-2.5">
                        @foreach($savedScholarships as $sch)
                            <div class="p-3 rounded-xl bg-surface border border-border-default text-xs font-sans space-y-1">
                                <span class="font-bold text-ink-950 block truncate">{{ $sch['name'] }}</span>
                                <p class="text-[11px] text-ink-600 line-clamp-1">{{ $sch['coverage'] }}</p>
                                <span class="text-[10px] font-semibold text-emerald-700 block">{{ $sch['eligibility_status'] }}</span>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('student.saved.scholarships') }}" class="block text-center text-xs font-semibold text-emerald-700 hover:text-emerald-800 pt-1">
                        View All Saved Aid Programs →
                    </a>
                </div>

                <!-- Recent AI Counseling Discussions -->
                <div class="p-5 rounded-2xl bg-surface border border-border-default shadow-card space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-border-subtle">
                        <div class="flex items-center gap-2">
                            <x-icon name="sparkles" class="w-4 h-4 text-brand-600" />
                            <h3 class="font-heading font-bold text-sm text-ink-950">AI Counselor History</h3>
                        </div>
                        <a href="{{ route('counselor.index') }}" class="text-[11px] font-semibold text-brand-600">New Chat</a>
                    </div>

                    <div class="space-y-2">
                        <a 
                            href="{{ route('counselor.chat', 'conv-1') }}"
                            class="block p-3 rounded-xl bg-surface-subtle hover:bg-brand-50/30 border border-border-subtle text-xs font-sans transition-colors"
                        >
                            <span class="font-bold text-ink-900 block truncate">BS CS in Islamabad under PKR 200k</span>
                            <span class="text-[10px] text-ink-400 block mt-0.5">NUST, COMSATS, FAST evaluated</span>
                        </a>

                        <a 
                            href="{{ route('counselor.chat', 'conv-2') }}"
                            class="block p-3 rounded-xl bg-surface-subtle hover:bg-brand-50/30 border border-border-subtle text-xs font-sans transition-colors"
                        >
                            <span class="font-bold text-ink-900 block truncate">FAST vs NUST Software Engineering</span>
                            <span class="text-[10px] text-ink-400 block mt-0.5">Industry hiring & curriculum compare</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.student>
