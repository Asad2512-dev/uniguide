<x-layouts.university-admin 
    :title="'Institutional Command Dashboard — ' . $institution['acronym'] . ' Admissions Directorate'"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <!-- Page Header -->
    <x-ui.page-header 
        title="Institutional Command Dashboard"
        description="Comprehensive admissions intelligence, program catalog authority, statutory compliance, and AI data review."
        badge="{{ $institution['acronym'] }} Main Campus H-12"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                    ['label' => 'Command Overview']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="file-down">
                Export Gazette Report
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" icon="sparkles" href="{{ route('uni_admin.ai_verification') }}">
                Review AI Queue ({{ $pending_verifications_count }})
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <!-- Profile Completeness & Notice Strip -->
    <div class="mb-8 p-4 sm:p-6 rounded-2xl bg-surface border border-border-default shadow-card">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="relative w-16 h-16 shrink-0 flex items-center justify-center rounded-2xl bg-brand-50 border border-brand-200/80">
                    <span class="font-heading font-extrabold text-xl text-brand-700">{{ $profile_completeness }}%</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="font-heading font-bold text-base text-ink-950">Institutional Profile Completeness</h2>
                        <x-ui.badge variant="success" size="sm">National Tier 1</x-ui.badge>
                    </div>
                    <p class="text-xs text-ink-500 font-sans mt-0.5 max-w-xl">
                        Your university profile is visible to over 240,000 Pakistani candidates. Complete pending items to maintain Priority Verification on the national portal.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-48 bg-surface-subtle h-2.5 rounded-full overflow-hidden border border-border-subtle">
                    <div class="bg-brand-600 h-full rounded-full transition-all duration-500" style="width: {{ $profile_completeness }}%"></div>
                </div>
                <a href="{{ route('uni_admin.profile') }}" class="px-4 py-2 rounded-xl border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-700 transition-smooth">
                    Edit Profile →
                </a>
            </div>
        </div>

        @if(!empty($missing_items))
            <div class="mt-4 pt-4 border-t border-border-subtle flex items-center gap-2 text-xs text-amber-700">
                <x-icon name="alert-triangle" class="w-4 h-4 text-amber-600 shrink-0" />
                <span>Action Recommended: {{ $missing_items[0] }}</span>
            </div>
        @endif
    </div>

    <!-- Core KPI Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        @foreach($kpis as $st)
            <x-ui.metric-card 
                :title="$st['title']"
                :value="$st['value']"
                :change="$st['change']"
                :changeType="$st['changeType']"
                :icon="$st['icon']"
                :iconColor="$st['iconColor']"
            />
        @endforeach
    </div>

    <!-- Suggested Administrative Actions -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-heading font-bold text-base text-ink-950">Recommended Administrative Actions</h3>
                <p class="text-xs text-ink-500 font-sans">Time-sensitive admissions workflows and compliance tasks.</p>
            </div>
            <span class="text-xs font-mono text-ink-600">3 Priority Tasks</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($suggested_actions as $act)
                <div class="p-5 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center">
                                <x-icon :name="$act['icon']" class="w-4 h-4" />
                            </div>
                            <span class="text-xs sm:text-[10px] font-mono font-bold uppercase px-2 py-0.5 rounded {{ $act['priority'] === 'high' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-surface-subtle text-ink-600' }}">
                                {{ $act['priority'] }} Priority
                            </span>
                        </div>
                        <h4 class="font-heading font-bold text-sm text-ink-950 mt-2">{{ $act['title'] }}</h4>
                        <p class="text-xs text-ink-500 font-sans leading-relaxed">{{ $act['desc'] }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between">
                        <a href="{{ route($act['route']) }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                            <span>{{ $act['cta'] }}</span>
                            <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Active Admission Sessions & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Sessions (7 cols) -->
        <div class="lg:col-span-7 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Active Admission Sessions</h3>
                    <p class="text-xs text-ink-500 font-sans">Current intake pipelines and candidate application volume.</p>
                </div>
                <a href="{{ route('uni_admin.admissions') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                    Manage Cycles →
                </a>
            </div>

            <div class="space-y-4">
                @foreach($active_sessions as $ses)
                    <div class="p-5 rounded-2xl bg-surface border border-border-default hover:border-brand-200 shadow-2xs space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="font-heading font-bold text-base text-ink-950">{{ $ses['name'] }}</h4>
                                    <x-ui.badge variant="success" size="sm" :dot="true">{{ $ses['status_label'] }}</x-ui.badge>
                                </div>
                                <span class="text-xs font-mono text-ink-500 block mt-1">
                                    {{ $ses['test_series'] }}
                                </span>
                            </div>

                            <div class="text-right shrink-0">
                                <span class="text-xs font-mono font-bold text-brand-700 bg-brand-50 px-2.5 py-1 rounded-lg">
                                    {{ $ses['days_left'] }} Days Left
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-3 pt-3 border-t border-border-subtle text-xs font-sans">
                            <div>
                                <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">Intake Capacity</span>
                                <span class="font-bold text-ink-900 font-mono">{{ number_format($ses['seats']) }} Seats</span>
                            </div>
                            <div>
                                <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">Applications</span>
                                <span class="font-bold text-brand-600 font-mono">{{ number_format($ses['applications_count']) }} Received</span>
                            </div>
                            <div>
                                <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">Deadline</span>
                                <span class="font-bold text-ink-900 font-mono">{{ $ses['deadline'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Audit Activity Log (5 cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Recent System Activity</h3>
                    <p class="text-xs text-ink-500 font-sans">Real-time audit log of changes made by administrative staff.</p>
                </div>
            </div>

            <div class="p-5 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                <div class="space-y-4">
                    @foreach($recent_activity as $act)
                        <div class="flex items-start gap-3 pb-3 border-b border-border-subtle last:border-0 last:pb-0">
                            <div class="w-8 h-8 rounded-lg bg-surface-subtle text-ink-600 flex items-center justify-center shrink-0 mt-0.5">
                                <x-icon :name="$act['icon']" class="w-4 h-4 text-brand-600" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-medium text-ink-900 leading-snug">{{ $act['action'] }}</p>
                                <div class="flex items-center gap-2 text-xs sm:text-[10px] text-ink-400 font-mono mt-1">
                                    <span>{{ $act['user'] }}</span>
                                    <span>•</span>
                                    <span>{{ $act['timestamp'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.university-admin>

