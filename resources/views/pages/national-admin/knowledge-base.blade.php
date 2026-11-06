<x-layouts.national-admin title="Knowledge Base (RAG) — Vector Store Management">
    <x-ui.page-header 
        title="Knowledge Base Management (RAG)"
        description="Vector embeddings index and statutory knowledge store powering UniGuide's AI Counselor with hallucination-resistant grounded citations."
        badge="{{ number_format($stats['embedded_chunks']) }} Embedded Vectors"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Platform Administration', 'url' => route('national_admin.dashboard')],
                    ['label' => 'Knowledge Base']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <form method="POST" action="{{ route('national_admin.knowledge_base.reindex') }}" class="inline">
                @csrf
                <x-ui.button type="submit" variant="primary" size="sm" icon="refresh-cw">
                    Re-index Knowledge Base
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

    <!-- Vector Store Performance Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-500 block mb-1">Total Corpus</span>
            <span class="font-heading font-extrabold text-2xl text-ink-950">{{ $stats['total_documents'] }}</span>
            <span class="text-xs sm:text-[10px] text-ink-500 block mt-0.5">Statutory Dossiers</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-500 block mb-1">Vector Chunks</span>
            <span class="font-heading font-extrabold text-2xl text-brand-700">{{ number_format($stats['embedded_chunks']) }}</span>
            <span class="text-xs sm:text-[10px] text-brand-600 block mt-0.5">Semantic Embeddings</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-500 block mb-1">Embedding Model</span>
            <span class="font-heading font-extrabold text-xs text-ink-950 truncate block mt-2">text-embedding-3</span>
            <span class="text-xs sm:text-[10px] font-mono text-ink-600 block mt-0.5">1536 Dimensions</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-500 block mb-1">Index State</span>
            <span class="font-heading font-extrabold text-xs text-emerald-700 truncate block mt-2">Active & Synced</span>
            <span class="text-xs sm:text-[10px] text-emerald-600 block mt-0.5">Zero Lag</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-500 block mb-1">Search Latency</span>
            <span class="font-heading font-extrabold text-2xl text-ink-950">{{ $stats['search_latency'] }}</span>
            <span class="text-xs sm:text-[10px] text-ink-500 block mt-0.5">p99 Cosine Vector Sim</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-500 block mb-1">Chunk Failures</span>
            <span class="font-heading font-extrabold text-2xl text-emerald-700">{{ $stats['failed_chunks'] }}</span>
            <span class="text-xs sm:text-[10px] text-emerald-600 block mt-0.5">100% Parsing Health</span>
        </div>
    </div>

    <!-- Statutory Corpus Documents Registry -->
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-heading font-bold text-base text-ink-950">Statutory & Regulatory Corpus Repository</h3>
            <span class="text-xs font-mono text-ink-600">Official Federal Sources</span>
        </div>

        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table data-mobile-table class="w-full text-left border-collapse" aria-label="Statutory Corpus Repository">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-xs sm:text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th scope="col" class="py-3.5 px-4 font-semibold">Statutory Document & Authority</th>
                            <th scope="col" class="py-3.5 px-4 text-center font-semibold">Vector Chunks</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Last Embedded</th>
                            <th scope="col" class="py-3.5 px-4 text-right font-semibold">Index Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        @foreach($documents as $doc)
                            <tr class="hover:bg-surface-subtle/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-ink-950 font-heading block">{{ $doc['title'] }}</span>
                                    <span class="text-xs sm:text-[11px] font-mono text-brand-700 block">{{ $doc['authority'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-ink-900">
                                    {{ $doc['chunks_count'] }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-ink-600 text-xs sm:text-[11px]">
                                    {{ $doc['last_embedded'] }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs sm:text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $doc['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.national-admin>

