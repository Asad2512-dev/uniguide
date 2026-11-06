<x-layouts.national-admin title="AI Extraction Review Workspace — Federal Verification">
    <x-ui.page-header 
        title="AI Extraction Review Workspace"
        description="High-throughput human-in-the-loop audit workspace for reviewing machine-extracted admissions rules, fee structures, and eligibility changes before public propagation."
        badge="4 Staged Extractions"
        badgeVariant="warning"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Platform Administration', 'url' => route('national_admin.dashboard')],
                    ['label' => 'AI Extraction Review']
                ]"
            />
        </x-slot:breadcrumbs>
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

    <div x-data="{ editingId: null, editedValue: '' }" class="space-y-6">
        @foreach($extractions as $ext)
            <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default shadow-card hover:border-brand-200 transition-all">
                <!-- Header Strip -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-border-subtle">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-heading font-extrabold text-sm text-brand-700">{{ $ext['university'] }}</span>
                            <span class="text-ink-600">•</span>
                            <span class="text-xs font-medium text-ink-600">{{ $ext['entity'] }}</span>
                            <span class="text-ink-600">•</span>
                            <span class="text-xs sm:text-[11px] font-mono text-ink-600">{{ $ext['timestamp'] }}</span>
                        </div>
                        <h3 class="font-heading font-bold text-base text-ink-950">{{ $ext['field_name'] }}</h3>
                    </div>

                    <!-- Confidence Badge & Status -->
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-600 block">AI Confidence</span>
                            <span class="font-mono font-extrabold text-sm {{ $ext['confidence'] >= 95 ? 'text-emerald-700' : 'text-amber-700' }}">
                                {{ $ext['confidence'] }}%
                            </span>
                        </div>

                        @if($ext['status'] === 'pending')
                            <div class="flex items-center gap-2">
                                <form method="POST" action="{{ route('national_admin.ai_extraction.process', $ext['id']) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approved">
                                    <x-ui.button type="submit" variant="primary" size="sm" icon="check">
                                        Approve
                                    </x-ui.button>
                                </form>

                                <button 
                                    type="button" 
                                    @click="editingId = '{{ $ext['id'] }}'; editedValue = '{{ $ext['after_value'] }}'"
                                    class="px-3 py-1.5 rounded-xl border border-border-default hover:bg-surface-subtle text-xs font-semibold text-ink-700 transition-colors cursor-pointer"
                                >
                                    Edit
                                </button>

                                <form method="POST" action="{{ route('national_admin.ai_extraction.process', $ext['id']) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="rejected">
                                    <x-ui.button type="submit" variant="outline" size="sm" icon="x" class="text-rose-700 hover:bg-rose-50 border-rose-200">
                                        Reject
                                    </x-ui.button>
                                </form>
                            </div>
                        @else
                            <span class="px-3 py-1 rounded-xl text-xs font-semibold font-mono {{ $ext['status'] === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-surface-subtle text-ink-600' }}">
                                {{ ucfirst($ext['status']) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Before & After Diff Box -->
                <div class="py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-3.5 rounded-xl bg-surface-subtle border border-border-subtle">
                        <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-600 block mb-1">Previous Published Value</span>
                        <span class="font-mono text-xs text-rose-700 font-semibold line-through block">
                            {{ $ext['before_value'] }}
                        </span>
                    </div>
                    <div class="p-3.5 rounded-xl bg-emerald-50/50 border border-emerald-200/80">
                        <span class="text-xs sm:text-[10px] font-mono uppercase text-emerald-800 block mb-1">Extracted Value (Proposed)</span>
                        <span class="font-mono text-xs text-emerald-900 font-bold block">
                            {{ $ext['after_value'] }}
                        </span>
                    </div>
                </div>

                <!-- Source Document Snippet -->
                <div class="pt-3 border-t border-border-subtle flex flex-col md:flex-row md:items-center justify-between gap-3 text-xs">
                    <div class="space-y-1">
                        <span class="text-xs sm:text-[11px] font-mono text-ink-500">Source: {{ $ext['source_document'] }}</span>
                        <p class="font-mono text-xs sm:text-[11px] text-ink-700 italic bg-canvas p-2.5 rounded-lg border border-border-subtle">
                            "{{ $ext['extracted_snippet'] }}"
                        </p>
                    </div>
                    <div class="shrink-0 text-right">
                        <span class="text-xs sm:text-[11px] font-medium {{ $ext['has_warning'] ? 'text-amber-700 font-semibold' : 'text-emerald-700' }}">
                            {{ $ext['validation_status'] }}
                        </span>
                    </div>
                </div>

                <!-- Edit Value Drawer Form (Alpine.js) -->
                <div 
                    x-show="editingId === '{{ $ext['id'] }}'" 
                    x-cloak 
                    class="mt-4 p-4 rounded-xl bg-canvas border border-brand-200 space-y-3"
                >
                    <h4 class="font-heading font-bold text-xs text-ink-900">Edit and Ratify Value</h4>
                    <form method="POST" action="{{ route('national_admin.ai_extraction.process', $ext['id']) }}" class="flex items-center gap-3">
                        @csrf
                        <input type="hidden" name="action" value="edited">
                        <input 
                            type="text" 
                            name="edited_value" 
                            x-model="editedValue"
                            class="flex-1 px-3 py-1.5 text-xs rounded-xl border border-border-default bg-surface focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"
                        />
                        <x-ui.button type="submit" variant="primary" size="sm">
                            Save & Ratify
                        </x-ui.button>
                        <button type="button" @click="editingId = null" class="text-xs text-ink-500 hover:text-ink-700 cursor-pointer">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</x-layouts.national-admin>

