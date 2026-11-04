<x-layouts.national-admin title="Web Scraping Control Center — Automated Federal Ingestion">
    <x-ui.page-header 
        title="Web Scraping Control Center"
        description="Automated crawler daemon fleet orchestrating continuous synchronization of university admissions notices, merit gazettes, and fee schedules."
        badge="Crawler Engine Active"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
                    ['label' => 'Web Scraping Center']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="history" href="{{ route('national_admin.scraping_history') }}">
                Execution Logs
            </x-ui.button>
            <form method="POST" action="{{ route('national_admin.scraping.retry') }}" class="inline">
                @csrf
                <x-ui.button type="submit" variant="primary" size="sm" icon="refresh-cw">
                    Retry Failed Jobs ({{ $stats['failed_today'] }})
                </x-ui.button>
            </form>
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

    <!-- Ingestion Engine Status Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-[10px] font-mono uppercase text-ink-500 block mb-1">Total Targets</span>
            <span class="font-heading font-extrabold text-2xl text-ink-950">{{ $stats['total_sources'] }}</span>
            <span class="text-[10px] text-ink-500 block mt-0.5">Authoritative Endpoints</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-[10px] font-mono uppercase text-ink-500 block mb-1">Active Crawlers</span>
            <span class="font-heading font-extrabold text-2xl text-brand-700">{{ $stats['active_crawlers'] }}</span>
            <span class="text-[10px] text-brand-600 block mt-0.5">Workers Online</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-[10px] font-mono uppercase text-ink-500 block mb-1">Queue Depth</span>
            <span class="font-heading font-extrabold text-2xl text-amber-700">{{ $stats['queue_depth'] }}</span>
            <span class="text-[10px] text-amber-600 block mt-0.5">Pending Tasks</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-[10px] font-mono uppercase text-ink-500 block mb-1">Failed Today</span>
            <span class="font-heading font-extrabold text-2xl text-rose-700">{{ $stats['failed_today'] }}</span>
            <span class="text-[10px] text-rose-600 block mt-0.5">Cloudflare Gate</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-[10px] font-mono uppercase text-ink-500 block mb-1">Success Rate</span>
            <span class="font-heading font-extrabold text-2xl text-emerald-700">{{ $stats['success_rate'] }}%</span>
            <span class="text-[10px] text-emerald-600 block mt-0.5">24h Rolling</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-[10px] font-mono uppercase text-ink-500 block mb-1">Avg Crawl Latency</span>
            <span class="font-heading font-extrabold text-2xl text-ink-950">{{ $stats['avg_crawl_time'] }}</span>
            <span class="text-[10px] text-ink-500 block mt-0.5">Per PDF/HTML Entity</span>
        </div>
    </div>

    <!-- Active Crawler Workers Roster -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-heading font-bold text-base text-ink-950">Active Crawler Worker Slots</h3>
            <form method="POST" action="{{ route('national_admin.scraping.trigger') }}" class="inline">
                @csrf
                <input type="hidden" name="source_name" value="All Active Institutional Repositories">
                <button type="submit" class="text-xs font-semibold text-brand-700 hover:text-brand-800 transition-colors cursor-pointer">
                    Dispatch All Workers Now →
                </button>
            </form>
        </div>

        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                        <th class="py-3.5 px-4 font-semibold">Worker & Target Source</th>
                        <th class="py-3.5 px-4 font-semibold">Status</th>
                        <th class="py-3.5 px-4 font-semibold">Freshness</th>
                        <th class="py-3.5 px-4 text-center font-semibold">Pages Parsed</th>
                        <th class="py-3.5 px-4 text-center font-semibold">Entities Found</th>
                        <th class="py-3.5 px-4 text-right font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle text-xs">
                    @foreach($crawlers as $crw)
                        <tr class="hover:bg-surface-subtle/80 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-mono text-[11px] text-ink-400 block">{{ $crw['id'] }}</span>
                                <span class="font-bold text-ink-950 font-heading block">{{ $crw['source_name'] }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[11px] font-mono font-medium {{ $crw['status'] === 'running' ? 'bg-brand-50 text-brand-700 border border-brand-200' : ($crw['status'] === 'failed' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-surface-subtle text-ink-700 border border-border-subtle') }}">
                                    @if($crw['status'] === 'running')
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-600 animate-pulse"></span>
                                    @endif
                                    {{ $crw['status_label'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-ink-800 font-medium block">{{ $crw['freshness'] }}</span>
                                <span class="text-[10px] font-mono text-ink-400 block">{{ $crw['last_success'] }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-ink-800">
                                {{ $crw['pages_crawled'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-emerald-700">
                                {{ $crw['entities_found'] }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form method="POST" action="{{ route('national_admin.scraping.trigger') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="source_name" value="{{ $crw['source_name'] }}">
                                    <button type="submit" class="px-2.5 py-1 rounded-lg border border-border-default hover:bg-brand-50 hover:text-brand-700 text-[11px] font-semibold text-ink-700 transition-colors cursor-pointer">
                                        Run Now
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.national-admin>
