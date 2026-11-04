<x-layouts.university-admin 
    :title="'AI Data Verification & Source Audit — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <div x-data="{ editModalOpen: false, activeItem: null, editedValue: '' }">
        <x-ui.page-header 
            title="AI Data Extraction & Source Verification Queue"
            description="Audit structured fields automatically parsed from institutional prospectus PDFs and regulatory gazettes against verified official records."
            badge="{{ count(array_filter($queue, fn($q) => $q['status'] === 'pending')) }} Pending Verifications"
            badgeVariant="warning"
        >
            <x-slot:breadcrumbs>
                <x-ui.breadcrumbs 
                    :items="[
                        ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                        ['label' => 'AI Verification']
                    ]"
                />
            </x-slot:breadcrumbs>
        </x-ui.page-header>

        <!-- Informational Banner -->
        <div class="mb-8 p-5 rounded-2xl bg-brand-50/40 border border-brand-200/80 flex items-start gap-4">
            <div class="w-10 h-10 rounded-xl bg-brand-100 text-brand-700 flex items-center justify-center shrink-0">
                <x-icon name="sparkles" class="w-5 h-5" />
            </div>
            <div>
                <h3 class="font-heading font-bold text-sm text-brand-950">Automated Higher Education Crawler & Audit Protocol</h3>
                <p class="text-xs text-brand-800 font-sans mt-0.5 leading-relaxed">
                    UniGuide’s verified crawler extracts proposed updates from your published prospectuses and PEC/NCEAC notifications. No AI-extracted modifications are published to candidates without explicit human signoff from the admissions registrar.
                </p>
            </div>
        </div>

        <!-- Verification Cards Stack -->
        <div class="space-y-6">
            @foreach($queue as $item)
                <div class="p-6 sm:p-8 rounded-2xl bg-surface border {{ $item['status'] === 'approved' ? 'border-emerald-200 bg-emerald-50/10' : ($item['status'] === 'rejected' ? 'border-border-default opacity-60' : 'border-border-default hover:border-brand-300') }} shadow-card space-y-6 transition-all">
                    <!-- Top Identification Bar -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border-subtle">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-mono uppercase font-bold text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded">
                                    {{ $item['entity'] }}
                                </span>
                                <span class="text-ink-300">•</span>
                                <span class="text-xs font-mono text-ink-500">{{ $item['field_key'] }}</span>
                            </div>
                            <h3 class="font-heading font-bold text-lg text-ink-950">
                                {{ $item['field'] }}
                            </h3>
                        </div>

                        <!-- Status Badge & Confidence Score -->
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <span class="text-[10px] text-ink-400 font-mono block">AI Confidence</span>
                                <span class="text-xs font-mono font-bold text-brand-600">{{ $item['confidence_score'] }}%</span>
                            </div>

                            <span class="px-3 py-1 rounded-full text-xs font-mono font-bold uppercase {{ match($item['status']) {
                                'approved' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                'rejected' => 'bg-surface-subtle text-ink-600 border border-border-subtle',
                                'edited' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
                                default => 'bg-amber-50 text-amber-700 border border-amber-200 animate-pulse',
                            } }}">
                                {{ $item['status'] === 'pending' ? 'Pending Review' : ucfirst($item['status']) }}
                            </span>
                        </div>
                    </div>

                    <!-- Visual Diff View: Previous vs Proposed -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Previous Live Value -->
                        <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle space-y-1.5">
                            <span class="text-[10px] font-mono font-bold text-ink-400 uppercase tracking-wider block">
                                Current Official Live Value
                            </span>
                            <div class="text-sm font-bold text-ink-700 font-mono">
                                {{ $item['previous_value'] }}
                            </div>
                            <span class="text-[11px] text-ink-400 font-sans block">Currently rendered on search and comparison tools.</span>
                        </div>

                        <!-- Proposed AI Extracted Value -->
                        <div class="p-4 rounded-xl bg-brand-50/30 border border-brand-200/80 space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-mono font-bold text-brand-800 uppercase tracking-wider block">
                                    Proposed Extracted Modification
                                </span>
                                <span class="text-[10px] font-mono font-bold text-brand-700 bg-brand-100 px-2 py-0.2 rounded">
                                    {{ $item['value_diff'] }}
                                </span>
                            </div>
                            <div class="text-sm font-bold text-brand-900 font-mono">
                                {{ $item['proposed_value'] }}
                            </div>
                            <span class="text-[11px] text-brand-700 font-sans block">Extracted from newly crawled institutional release.</span>
                        </div>
                    </div>

                    <!-- Verbatim Source Excerpt Box -->
                    <div class="p-4 rounded-xl bg-canvas border border-border-default space-y-2">
                        <div class="flex items-center justify-between text-xs font-sans">
                            <span class="font-bold text-ink-900 flex items-center gap-1.5">
                                <x-icon name="file-text" class="w-4 h-4 text-ink-500" />
                                <span>Verbatim Source Context: {{ $item['source_title'] }}</span>
                            </span>
                            <a href="{{ $item['source_url'] }}" target="_blank" rel="noopener" class="text-brand-600 hover:text-brand-700 underline text-[11px] font-mono flex items-center gap-1">
                                <span>Inspect Source URL</span>
                                <x-icon name="external-link" class="w-3 h-3" />
                            </a>
                        </div>
                        <blockquote class="text-xs text-ink-800 font-sans italic border-l-2 border-brand-500 pl-3 leading-relaxed">
                            "{{ $item['source_text_snippet'] }}"
                        </blockquote>
                        <div class="text-[10px] font-mono text-ink-400">
                            Crawled & parsed on {{ $item['extraction_timestamp'] }}
                        </div>
                    </div>

                    <!-- Validation Warning (if any) -->
                    @if($item['validation_warning'])
                        <div class="p-3.5 rounded-xl bg-amber-50/50 border border-amber-200 text-xs text-amber-900 flex items-start gap-2.5">
                            <x-icon name="alert-circle" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
                            <div>
                                <strong class="font-semibold">Compliance Note:</strong>
                                <span>{{ $item['validation_warning'] }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Action Controls Bar -->
                    @if($item['status'] === 'pending')
                        <div class="pt-4 border-t border-border-subtle flex flex-wrap items-center justify-between gap-3">
                            <div class="text-xs text-ink-500 font-sans">
                                Sign-off required by Directorate of Admissions.
                            </div>

                            <div class="flex items-center gap-2">
                                <!-- Reject Action -->
                                <form action="{{ route('uni_admin.ai_verification.reject', $item['id']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3.5 py-2 rounded-xl border border-border-default hover:bg-surface-subtle text-ink-700 text-xs font-semibold transition-smooth cursor-pointer">
                                        ✕ Reject Change
                                    </button>
                                </form>

                                <!-- Edit Action -->
                                <button 
                                    type="button" 
                                    @click="activeItem = @js($item); editedValue = '{{ addslashes($item['proposed_value']) }}'; editModalOpen = true"
                                    class="px-3.5 py-2 rounded-xl border border-border-default hover:bg-surface-subtle text-ink-700 text-xs font-semibold transition-smooth cursor-pointer"
                                >
                                    ✎ Edit Manually
                                </button>

                                <!-- Approve Action -->
                                <form action="{{ route('uni_admin.ai_verification.approve', $item['id']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-smooth cursor-pointer flex items-center gap-1.5">
                                        <x-icon name="check" class="w-3.5 h-3.5" />
                                        <span>Approve & Publish to Registry</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="pt-3 border-t border-border-subtle text-xs text-ink-500 font-mono flex items-center justify-between">
                            <span>Audited by {{ $item['reviewed_by'] ?? 'Dr. Tariq Mahmood' }}</span>
                            <span>{{ $item['reviewed_at'] ?? '04 Nov 2026' }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Inline Edit Modal -->
        <div 
            x-show="editModalOpen" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-surface rounded-2xl border border-border-default p-6 max-w-md w-full shadow-elevated space-y-5" @click.outside="editModalOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Modify AI Extracted Value</h3>
                    <button type="button" @click="editModalOpen = false" class="text-ink-400 hover:text-ink-700 text-sm">✕</button>
                </div>

                <form :action="'{{ route('uni_admin.ai_verification') }}/' + (activeItem ? activeItem.id : '') + '/edit'" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <span class="text-[10px] font-mono uppercase text-ink-400 block mb-1">Target Field</span>
                        <div class="font-bold text-xs text-ink-900" x-text="activeItem ? activeItem.field : ''"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Verified Value to Publish</label>
                        <input 
                            type="text" 
                            name="edited_value" 
                            x-model="editedValue" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default text-xs font-mono"
                        />
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold">Approve Modified Value</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.university-admin>
