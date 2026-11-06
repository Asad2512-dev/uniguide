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
                    ['label' => 'Platform Administration', 'url' => route('national_admin.dashboard')],
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

    <div 
        x-data="{ 
            search: '', 
            feedFilter: 'all', 
            showModal: false,
            matches(src) {
                const s = this.search.toLowerCase();
                const matchesSearch = !s || src.name.toLowerCase().includes(s) || src.institution.toLowerCase().includes(s) || src.base_url.toLowerCase().includes(s);
                const matchesFeed = this.feedFilter === 'all' || src.feed_type.toLowerCase().includes(this.feedFilter.toLowerCase());
                return matchesSearch && matchesFeed;
            }
        }" 
        @open-add-source.window="showModal = true" 
        class="space-y-4"
    >
        <!-- Filter Bar -->
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <x-icon name="search" class="w-4 h-4 text-ink-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Search by source name, institution, or endpoint URL..."
                    class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-border-default bg-canvas focus:bg-surface focus:outline-none focus:ring-2 focus:ring-brand-500 transition-colors"
                />
            </div>

            <div class="flex items-center gap-3">
                <select 
                    x-model="feedFilter"
                    class="text-xs rounded-xl border border-border-default bg-surface px-3 py-2 text-ink-700 focus:outline-none focus:ring-2 focus:ring-brand-500"
                >
                    <option value="all">All Ingestion Types</option>
                    <option value="PDF">PDF Gazette Parser</option>
                    <option value="HTML">HTML Table Scraper</option>
                    <option value="API">REST JSON API Scraper</option>
                    <option value="Statutory">Statutory Gazette Scraper</option>
                </select>
            </div>
        </div>

        <!-- Sources Table -->
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table data-mobile-table class="w-full text-left border-collapse" aria-label="Official Data Source Feeds">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-xs sm:text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th scope="col" class="py-3.5 px-4 font-semibold">Source Name & Target Institution</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Endpoint & Feed Type</th>
                            <th scope="col" class="py-3.5 px-4 text-center font-semibold">Reliability</th>
                            <th scope="col" class="py-3.5 px-4 text-center font-semibold">HTTP Code</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Frequency</th>
                            <th scope="col" class="py-3.5 px-4 text-right font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        @foreach($sources as $src)
                            <tr 
                                x-show="matches({{ json_encode($src) }})"
                                class="hover:bg-surface-subtle/80 transition-colors"
                            >
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-ink-950 font-heading block">{{ $src['name'] }}</span>
                                    <span class="text-xs sm:text-[11px] font-mono text-brand-700 block">{{ $src['institution'] }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-ink-800 block truncate max-w-xs">{{ $src['base_url'] }}{{ $src['endpoint'] }}</span>
                                    <span class="text-xs sm:text-[11px] text-ink-500 block">{{ $src['feed_type'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 font-mono font-bold text-xs {{ $src['reliability_score'] >= 95 ? 'text-emerald-700' : 'text-amber-700' }}">
                                        {{ $src['reliability_score'] }}%
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs sm:text-[11px] font-mono font-bold {{ $src['http_status'] === 200 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                        HTTP {{ $src['http_status'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-ink-700 block">{{ $src['crawl_frequency'] }}</span>
                                    <span class="text-xs sm:text-[11px] font-mono text-ink-600 block">{{ $src['last_crawled'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <form method="POST" action="{{ route('national_admin.scraping.trigger') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="source_name" value="{{ $src['name'] }}">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg border border-border-default hover:bg-brand-50 hover:text-brand-700 text-xs sm:text-[11px] font-semibold text-ink-700 transition-colors cursor-pointer">
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

        <!-- Register New Ingestion Source Modal (Alpine.js) -->
        <div 
            x-show="showModal" 
            x-modal-focus="showModal"
            role="dialog" aria-modal="true" aria-label="Register official data source"

            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink-950/60 backdrop-blur-xs"
            @keydown.escape.window="showModal = false"
        >
            <div 
                class="bg-surface rounded-2xl border border-border-default shadow-elevated w-full max-w-lg max-h-[calc(100dvh-2rem)] overflow-y-auto"
                @click.away="showModal = false"
            >
                <div class="px-6 py-4 border-b border-border-default bg-surface-subtle flex items-center justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-sm text-ink-950">Register Authoritative Data Source</h3>
                        <p class="text-xs sm:text-[11px] text-ink-500 font-sans mt-0.5">Configure an automated crawler endpoint for university prospectuses or fee gazettes.</p>
                    </div>
                    <button type="button" @click="showModal = false" aria-label="Close register official data source" class="text-ink-400 hover:text-ink-700 cursor-pointer">
                        <x-icon name="x" class="w-4 h-4" />
                    </button>
                </div>

                <form method="POST" action="{{ route('national_admin.sources.create') }}" class="p-4 sm:p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Source Descriptor Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            required
                            placeholder="e.g. QAU Islamabad Directorate of Admissions Feed"
                            class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3.5 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Target Institution</label>
                            <input 
                                type="text" 
                                name="institution" 
                                required
                                placeholder="e.g. Quaid-i-Azam University"
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3.5 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Feed Type</label>
                            <select 
                                name="feed_type" 
                                required
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500"
                            >
                                <option value="PDF Gazette Parser">PDF Gazette Parser</option>
                                <option value="HTML Table Scraper">HTML Table Scraper</option>
                                <option value="REST JSON API Scraper">REST JSON API Scraper</option>
                                <option value="Statutory Gazette Scraper">Statutory Gazette Scraper</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Base URL</label>
                            <input 
                                type="url" 
                                name="base_url" 
                                required
                                placeholder="https://admissions.qau.edu.pk"
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3.5 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"
                            />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Endpoint</label>
                            <input 
                                type="text" 
                                name="endpoint" 
                                value="/prospectus/fees.pdf"
                                placeholder="/fees"
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3.5 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"
                            />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Crawl Frequency</label>
                        <select 
                            name="crawl_frequency" 
                            class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500"
                        >
                            <option value="Every 6 Hours">Every 6 Hours</option>
                            <option value="Every 12 Hours">Every 12 Hours</option>
                            <option value="Daily" selected>Daily</option>
                            <option value="Weekly">Weekly</option>
                        </select>
                    </div>

                    <div class="pt-4 border-t border-border-subtle flex justify-end gap-3">
                        <button type="button" @click="showModal = false" aria-label="Close register official data source" class="px-4 py-2 text-xs font-semibold text-ink-600 hover:text-ink-800 cursor-pointer">
                            Cancel
                        </button>
                        <x-ui.button type="submit" variant="primary" size="sm" icon="plus">
                            Register Source Feed
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.national-admin>
