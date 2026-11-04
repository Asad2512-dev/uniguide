<x-layouts.national-admin title="National HEC Command — Executive Operational Overview">
    <!-- Page Header -->
    <x-ui.page-header 
        title="National Operational Command"
        description="Federal oversight authority, chartered university coverage, automated crawler pipelines, and data verification status."
        badge="HEC Command & Control"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
                    ['label' => 'National Command Overview']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="globe" href="{{ route('national_admin.sources') }}">
                Data Sources
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" icon="activity" href="{{ route('national_admin.scraping') }}">
                Crawler Health Monitor
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    @if(session('toast_success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between text-xs font-sans shadow-xs">
            <div class="flex items-center gap-2.5">
                <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0" />
                <span class="font-medium">{{ session('toast_success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 cursor-pointer">
                <x-icon name="x" class="w-4 h-4" />
            </button>
        </div>
    @endif

    <!-- Core KPI Metrics Grid (8 metrics) -->
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

    <!-- Active System Alerts Banner Strip -->
    <div class="mb-8 space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="font-heading font-bold text-base text-ink-950 flex items-center gap-2">
                <x-icon name="shield-alert" class="w-4 h-4 text-amber-600" />
                Federal Regulatory & Inspection Notices
            </h3>
            <span class="text-xs font-mono text-ink-400">Live Telemetry</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($system_alerts as $alert)
                <div class="p-4 rounded-2xl border transition-all duration-200 {{ $alert['severity'] === 'critical' ? 'bg-rose-50/60 border-rose-200 text-rose-950' : ($alert['severity'] === 'warning' ? 'bg-amber-50/60 border-amber-200 text-amber-950' : 'bg-surface border-border-default text-ink-950') }}">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[10px] font-mono uppercase font-bold tracking-wider {{ $alert['severity'] === 'critical' ? 'bg-rose-100 text-rose-700 border border-rose-300' : ($alert['severity'] === 'warning' ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-brand-50 text-brand-700 border border-brand-200') }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $alert['severity'] === 'critical' ? 'bg-rose-600' : ($alert['severity'] === 'warning' ? 'bg-amber-600' : 'bg-brand-600') }}"></span>
                            {{ strtoupper($alert['severity']) }}
                        </span>
                        <span class="text-[11px] font-mono text-ink-400">{{ $alert['timestamp'] }}</span>
                    </div>
                    <h4 class="font-heading font-bold text-xs leading-snug mb-1">{{ $alert['title'] }}</h4>
                    <p class="text-[11px] text-ink-600 font-sans leading-relaxed mb-3">{{ $alert['desc'] }}</p>
                    <a href="{{ route('national_admin.verification') }}" class="inline-flex items-center gap-1 text-[11px] font-semibold text-brand-700 hover:text-brand-900 transition-colors">
                        <span>{{ $alert['action_label'] }}</span>
                        <x-icon name="arrow-right" class="w-3 h-3" />
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Regional Breakdown & Recent Changes Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Provincial Coverage Table (8 Cols) -->
        <div class="lg:col-span-7 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Provincial Jurisdiction & Data Coverage</h3>
                    <p class="text-xs text-ink-500 font-sans">Statutory coverage distribution across chartered institutions and constituent colleges.</p>
                </div>
                <a href="{{ route('national_admin.universities') }}" class="text-xs font-semibold text-brand-700 hover:text-brand-800 transition-colors">
                    Registry →
                </a>
            </div>

            <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" aria-label="Provincial Jurisdiction & Data Coverage">
                        <thead>
                            <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                                <th scope="col" class="py-3 px-4 font-semibold">Province / Region</th>
                                <th scope="col" class="py-3 px-4 text-center font-semibold">Unis</th>
                                <th scope="col" class="py-3 px-4 text-center font-semibold">Campuses</th>
                                <th scope="col" class="py-3 px-4 text-center font-semibold">Programs</th>
                                <th scope="col" class="py-3 px-4 text-right font-semibold">Coverage</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle text-xs">
                            @foreach($regional_breakdown as $reg)
                                <tr class="hover:bg-surface-subtle/80 transition-colors">
                                    <td class="py-3.5 px-4 font-bold text-ink-950 font-heading">
                                        {{ $reg['region'] }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono text-ink-700">
                                        {{ $reg['universities'] }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono text-ink-700">
                                        {{ $reg['campuses'] }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono text-ink-700">
                                        {{ number_format($reg['programs']) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <div class="w-16 bg-surface-subtle h-2 rounded-full overflow-hidden border border-border-subtle">
                                                <div class="bg-emerald-500 h-full rounded-full" style="width: {{ $reg['coverage'] }}%"></div>
                                            </div>
                                            <span class="font-mono font-bold text-ink-800 text-[11px]">{{ $reg['coverage'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Regulatory & Institution Changes (5 Cols) -->
        <div class="lg:col-span-5 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Recent Data Ingestions</h3>
                    <p class="text-xs text-ink-500 font-sans">Institutional changes synchronized from automated crawlers & registrars.</p>
                </div>
                <a href="{{ route('national_admin.ai_extraction') }}" class="text-xs font-semibold text-brand-700 hover:text-brand-800 transition-colors">
                    Review All
                </a>
            </div>

            <div class="rounded-2xl bg-surface border border-border-default shadow-card p-5 space-y-4">
                @foreach($recent_changes as $ch)
                    <div class="flex items-start gap-3 pb-3 border-b border-border-subtle last:border-b-0 last:pb-0">
                        <div class="w-8 h-8 rounded-xl bg-brand-50 border border-brand-200/80 text-brand-700 flex items-center justify-center shrink-0 mt-0.5">
                            <x-icon name="building-2" class="w-4 h-4" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-0.5">
                                <h4 class="font-heading font-bold text-xs text-ink-950 truncate">{{ $ch['university'] }}</h4>
                                <span class="text-[10px] font-mono text-ink-400 shrink-0">{{ $ch['timestamp'] }}</span>
                            </div>
                            <p class="text-xs text-ink-600 font-sans leading-relaxed">{{ $ch['change'] }}</p>
                            <span class="text-[11px] font-mono text-brand-700 mt-1 block">By {{ $ch['user'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quick Operations Command Dock -->
    <div class="p-6 rounded-2xl bg-gradient-to-br from-brand-950 via-brand-900 to-indigo-950 text-white shadow-elevated">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-1">
                <span class="text-[10px] font-mono uppercase tracking-widest text-brand-300 font-bold">Automated Federal Engine</span>
                <h3 class="font-heading font-extrabold text-lg text-white">Central Verification & Scraping Control Active</h3>
                <p class="text-xs text-brand-100/80 max-w-2xl font-sans leading-relaxed">
                    Continuous monitoring across 248 official university websites, PEC gazette feeds, and IBCC equivalence notices. High-confidence AI extractions are queued for human-in-the-loop validation.
                </p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('national_admin.ai_extraction') }}" class="px-4 py-2.5 rounded-xl bg-white text-brand-950 hover:bg-brand-50 font-bold text-xs transition-smooth shadow-xs flex items-center gap-2">
                    <x-icon name="sparkles" class="w-4 h-4 text-brand-600" />
                    Review AI Extractions (4)
                </a>
                <a href="{{ route('national_admin.verification') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-xs transition-smooth flex items-center gap-2">
                    <x-icon name="shield-check" class="w-4 h-4" />
                    Verification Queue
                </a>
            </div>
        </div>
    </div>
</x-layouts.national-admin>

