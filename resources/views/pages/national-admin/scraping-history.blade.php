<x-layouts.national-admin title="Scraping Job History & Execution Logs">
    <x-ui.page-header 
        title="Scraping Job History"
        description="Chronological audit trail of crawler daemon tasks, parser execution times, HTTP status codes, and entity extraction payloads."
        badge="Crawler Audit Log"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
                    ['label' => 'Web Scraping Center', 'url' => route('national_admin.scraping')],
                    ['label' => 'Execution Logs']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="activity" href="{{ route('national_admin.scraping') }}">
                Control Center
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div x-data="{ activeLog: null }" class="space-y-4">
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                        <th class="py-3.5 px-4 font-semibold">Job ID & Target</th>
                        <th class="py-3.5 px-4 font-semibold">Started Timestamp</th>
                        <th class="py-3.5 px-4 font-semibold">Duration</th>
                        <th class="py-3.5 px-4 text-center font-semibold">Pages</th>
                        <th class="py-3.5 px-4 text-center font-semibold">Records</th>
                        <th class="py-3.5 px-4 font-semibold">Status</th>
                        <th class="py-3.5 px-4 text-right font-semibold">Stdout</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle text-xs">
                    @foreach($history as $item)
                        <tr class="hover:bg-surface-subtle/80 transition-colors">
                            <td class="py-3.5 px-4">
                                <span class="font-mono text-[11px] text-ink-400 block">{{ $item['job_id'] }}</span>
                                <span class="font-bold text-ink-950 font-heading block">{{ $item['target'] }}</span>
                                <span class="text-[11px] font-mono text-ink-500 block truncate max-w-xs">{{ $item['url'] }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-ink-600 text-[11px]">
                                {{ $item['started_at'] }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-ink-800">
                                {{ $item['duration'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-ink-800">
                                {{ $item['pages'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-emerald-700">
                                {{ $item['records_extracted'] }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[11px] font-mono font-bold {{ $item['status'] === 'Completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                    {{ $item['status'] }} ({{ $item['http_code'] }})
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <button 
                                    type="button" 
                                    @click="activeLog = {{ json_encode($item) }}"
                                    class="px-2.5 py-1 rounded-lg border border-border-default hover:bg-brand-50 hover:text-brand-700 text-[11px] font-semibold text-ink-700 transition-colors cursor-pointer"
                                >
                                    View Terminal
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Terminal Log Viewer Modal (Alpine.js) -->
        <div 
            x-show="activeLog" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink-950/60 backdrop-blur-xs"
            @keydown.escape.window="activeLog = null"
        >
            <div 
                class="bg-surface rounded-2xl border border-border-default shadow-elevated w-full max-w-2xl overflow-hidden"
                @click.away="activeLog = null"
            >
                <div class="px-6 py-4 border-b border-border-default bg-surface-subtle flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="font-mono text-xs font-bold text-ink-900 ml-2" x-text="activeLog?.job_id + ' — ' + activeLog?.target"></span>
                    </div>
                    <button type="button" @click="activeLog = null" class="text-ink-400 hover:text-ink-700 cursor-pointer">
                        <x-icon name="x" class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-5 bg-ink-950 text-emerald-400 font-mono text-xs overflow-x-auto max-h-80 whitespace-pre-wrap leading-relaxed select-text" x-text="activeLog?.log_snippet">
                </div>
                <div class="px-6 py-3 border-t border-border-default bg-surface flex justify-end">
                    <x-ui.button variant="outline" size="sm" @click="activeLog = null">
                        Close Log Viewer
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.national-admin>
