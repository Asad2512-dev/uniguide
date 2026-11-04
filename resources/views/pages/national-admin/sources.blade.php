<x-layouts.national-admin title="Official Data Sources — Central University Repositories">
    <x-ui.page-header 
        title="Official Data Source Management"
        description="Configuration and real-time health telemetry for authoritative institutional prospectuses, statutory council gazettes, and university admission portals."
        badge="Authoritative Ingestion Nodes"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
                    ['label' => 'Data Sources']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="activity" href="{{ route('national_admin.scraping') }}">
                Scraping Control Center
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" icon="plus" @click="$dispatch('open-add-source')">
                Register New Source
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

    <div class="space-y-4">
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                        <th class="py-3.5 px-4 font-semibold">Source Name & Target Institution</th>
                        <th class="py-3.5 px-4 font-semibold">Endpoint & Feed Type</th>
                        <th class="py-3.5 px-4 text-center font-semibold">Reliability</th>
                        <th class="py-3.5 px-4 text-center font-semibold">HTTP Code</th>
                        <th class="py-3.5 px-4 font-semibold">Frequency</th>
                        <th class="py-3.5 px-4 text-right font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle text-xs">
                    @foreach($sources as $src)
                        <tr class="hover:bg-surface-subtle/80 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-ink-950 font-heading block">{{ $src['name'] }}</span>
                                <span class="text-[11px] font-mono text-brand-700 block">{{ $src['institution'] }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-mono text-ink-800 block truncate max-w-xs">{{ $src['base_url'] }}{{ $src['endpoint'] }}</span>
                                <span class="text-[11px] text-ink-500 block">{{ $src['feed_type'] }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center gap-1 font-mono font-bold text-xs {{ $src['reliability_score'] >= 95 ? 'text-emerald-700' : 'text-amber-700' }}">
                                    {{ $src['reliability_score'] }}%
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-mono font-bold {{ $src['http_status'] === 200 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    HTTP {{ $src['http_status'] }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-ink-700 block">{{ $src['crawl_frequency'] }}</span>
                                <span class="text-[11px] font-mono text-ink-400 block">{{ $src['last_crawled'] }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <form method="POST" action="{{ route('national_admin.scraping.trigger') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="source_name" value="{{ $src['name'] }}">
                                    <button type="submit" class="px-2.5 py-1 rounded-lg border border-border-default hover:bg-brand-50 hover:text-brand-700 text-[11px] font-semibold text-ink-700 transition-colors cursor-pointer">
                                        Trigger Crawl
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
