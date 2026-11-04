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
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
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

    <div class="space-y-4">
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" aria-label="Data Verification Queue">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
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
                                    <span class="text-[11px] font-mono text-brand-700 block">{{ $item['entity_type'] }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-ink-800 block">{{ $item['field'] }}</span>
                                    <span class="text-[10px] font-mono text-ink-400 block">{{ $item['submitted_at'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-ink-700">
                                    {{ $item['submitted_by'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase {{ $item['priority'] === 'High' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($item['priority'] === 'Medium' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-surface-subtle text-ink-600 border border-border-subtle') }}">
                                        {{ $item['priority'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-[11px] font-mono font-medium {{ $item['status'] === 'Approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('national_admin.ai_extraction') }}" class="px-2.5 py-1 rounded-lg border border-border-default hover:bg-brand-50 hover:text-brand-700 text-[11px] font-semibold text-ink-700 transition-colors inline-block">
                                        Audit Diff →
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.national-admin>

