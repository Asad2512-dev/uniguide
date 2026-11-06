<x-layouts.national-admin title="Data Verification Queue — Central Publishing Gates">
    <x-ui.page-header 
        title="Data Verification Queue"
        description="Federal pre-publication staging queue for institutional profile updates, tuition adjustments, and new program seat allocations."
        badge="Staging Gate Active"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Platform Administration', 'url' => route('national_admin.dashboard')],
                    ['label' => 'Verification Queue']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="sparkles" href="{{ route('national_admin.ai_extraction') }}">
                AI Extraction Workspace
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
            showModal: false, 
            selectedItem: null,
            openDiff(item) {
                this.selectedItem = item;
                this.showModal = true;
            }
        }" 
        class="space-y-4"
    >
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table data-mobile-table class="w-full text-left border-collapse" aria-label="Data Verification Queue">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-xs sm:text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th scope="col" class="py-3.5 px-4 font-semibold">Entity & Target Institution</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Field / Schedule Change</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Submitted By</th>
                            <th scope="col" class="py-3.5 px-4 text-center font-semibold">Priority</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Status</th>
                            <th scope="col" class="py-3.5 px-4 text-right font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        @foreach($queue as $item)
                            <tr class="hover:bg-surface-subtle/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-ink-950 font-heading block">{{ $item['institution'] }}</span>
                                    <span class="text-xs sm:text-[11px] font-mono text-brand-700 block">{{ $item['entity_type'] }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-ink-800 block">{{ $item['field'] }}</span>
                                    <span class="text-xs sm:text-[10px] font-mono text-ink-600 block">{{ $item['submitted_at'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-ink-700">
                                    {{ $item['submitted_by'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs sm:text-[10px] font-mono font-bold uppercase {{ $item['priority'] === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($item['priority'] === 'Medium' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-surface-subtle text-ink-600 border border-border-subtle') }}">
                                        {{ $item['priority'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs sm:text-[11px] font-mono font-medium {{ $item['status'] === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            type="button" 
                                            @click="openDiff({{ json_encode($item) }})"
                                            class="px-2.5 py-1 rounded-lg border border-border-default hover:bg-brand-50 hover:text-brand-700 text-xs sm:text-[11px] font-semibold text-ink-700 transition-colors cursor-pointer"
                                        >
                                            Audit Diff →
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Verification Diff Audit Modal (Alpine.js) -->
        <div 
            x-show="showModal" 
            x-modal-focus="showModal"
            role="dialog"
            aria-modal="true"
            aria-label="Review verification changes"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink-950/60 backdrop-blur-xs"
            @keydown.escape.window="showModal = false"
        >
            <div 
                class="bg-surface rounded-2xl border border-border-default shadow-elevated max-h-[calc(100dvh-2rem)] overflow-y-auto w-full max-w-xl"
                @click.away="showModal = false"
            >
                <template x-if="selectedItem">
                    <div>
                        <div class="px-6 py-4 border-b border-border-default bg-surface-subtle flex items-center justify-between">
                            <div>
                                <span class="text-xs sm:text-[10px] font-mono uppercase tracking-wider text-brand-700 font-bold block" x-text="selectedItem.institution + ' • ' + selectedItem.entity_type"></span>
                                <h3 class="font-heading font-bold text-sm text-ink-950" x-text="selectedItem.field"></h3>
                            </div>
                            <button type="button" @click="showModal = false" class="text-ink-400 hover:text-ink-700 cursor-pointer">
                                <x-icon name="x" class="w-4 h-4" />
                            </button>
                        </div>

                        <div class="p-4 sm:p-6 space-y-4 text-xs">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-3.5 rounded-xl bg-surface-subtle border border-border-subtle">
                                    <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-500 block mb-1">Current Master Value</span>
                                    <span class="font-mono text-xs text-rose-700 font-semibold line-through block" x-text="selectedItem.before_value || 'None on file'"></span>
                                </div>
                                <div class="p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-200">
                                    <span class="text-xs sm:text-[10px] font-mono uppercase text-emerald-800 block mb-1">Proposed Discovery Value</span>
                                    <span class="font-mono text-xs text-emerald-950 font-bold block" x-text="selectedItem.after_value || 'Proposed value'"></span>
                                </div>
                            </div>

                            <div class="p-3.5 rounded-xl bg-canvas border border-border-subtle flex items-center justify-between text-xs sm:text-[11px] font-mono text-ink-600">
                                <span class="flex items-center gap-1.5">
                                    <x-icon name="file-text" class="w-3.5 h-3.5 text-brand-600" />
                                    Source: <strong class="text-ink-900" x-text="selectedItem.source_doc || 'University Prospectus Feed'"></strong>
                                </span>
                                <span class="text-emerald-700 font-bold" x-text="'Confidence: ' + (selectedItem.confidence || 98.5) + '%'"></span>
                            </div>

                            <div class="text-xs sm:text-[11px] text-ink-500 font-sans">
                                Submitted by <span class="font-semibold text-ink-800" x-text="selectedItem.submitted_by"></span> at <span x-text="selectedItem.submitted_at"></span>. Approving will immediately sync this record to the public catalog and AI vector store.
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-border-default bg-surface-subtle flex items-center justify-between">
                            <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-semibold text-ink-600 hover:text-ink-800 cursor-pointer">
                                Close
                            </button>

                            <div class="flex items-center gap-2">
                                <form method="POST" :action="'/admin/national/verification/' + selectedItem.id" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="flagged">
                                    <x-ui.button type="submit" variant="outline" size="sm" icon="alert-triangle" class="text-amber-700 hover:bg-amber-50 border-amber-200">
                                        Flag Discrepancy
                                    </x-ui.button>
                                </form>

                                <form method="POST" :action="'/admin/national/verification/' + selectedItem.id" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approved">
                                    <x-ui.button type="submit" variant="primary" size="sm" icon="check">
                                        Publish to Live Registry
                                    </x-ui.button>
                                </form>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-layouts.national-admin>
