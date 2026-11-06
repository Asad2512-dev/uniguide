<x-layouts.national-admin title="Charter Approval Center — Institutional Accreditation">
    <x-ui.page-header 
        title="University Approval Center"
        description="Statutory review board for constituent campus approvals, degree charter level upgrades, and statutory council accreditations."
        badge="Regulatory Board"
        badgeVariant="warning"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Platform Administration', 'url' => route('national_admin.dashboard')],
                    ['label' => 'Charter Approvals']
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

    <div 
        x-data="{ 
            activeTab: 'all', 
            selectedDossier: null, 
            showModal: false,
            openDossier(app) {
                this.selectedDossier = app;
                this.showModal = true;
            }
        }" 
        class="space-y-6"
    >
        <!-- Status Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-border-default pb-3 overflow-x-auto">
            <button 
                type="button"
                @click="activeTab = 'all'"
                :class="activeTab === 'all' ? 'bg-brand-50 text-brand-700 border-brand-200 font-semibold' : 'text-ink-600 hover:text-ink-950 border-transparent hover:bg-surface-subtle'"
                class="px-3.5 py-1.5 rounded-xl border text-xs transition-colors flex items-center gap-2 shrink-0 cursor-pointer"
            >
                <span>All Filings</span>
                <span class="px-1.5 py-0.5 rounded-full text-xs sm:text-[10px] bg-surface font-mono text-ink-600 border border-border-subtle">
                    {{ count($approvals) }}
                </span>
            </button>

            <button 
                type="button"
                @click="activeTab = 'pending'"
                :class="activeTab === 'pending' ? 'bg-amber-50 text-amber-800 border-amber-200 font-semibold' : 'text-ink-600 hover:text-ink-950 border-transparent hover:bg-surface-subtle'"
                class="px-3.5 py-1.5 rounded-xl border text-xs transition-colors flex items-center gap-2 shrink-0 cursor-pointer"
            >
                <span>Pending Statutory Review</span>
                <span class="px-1.5 py-0.5 rounded-full text-xs sm:text-[10px] bg-amber-100 font-mono text-amber-800 font-bold">
                    {{ count(array_filter($approvals, fn($a) => $a['status'] !== 'approved')) }}
                </span>
            </button>

            <button 
                type="button"
                @click="activeTab = 'approved'"
                :class="activeTab === 'approved' ? 'bg-emerald-50 text-emerald-800 border-emerald-200 font-semibold' : 'text-ink-600 hover:text-ink-950 border-transparent hover:bg-surface-subtle'"
                class="px-3.5 py-1.5 rounded-xl border text-xs transition-colors flex items-center gap-2 shrink-0 cursor-pointer"
            >
                <span>Gazette Ratified</span>
                <span class="px-1.5 py-0.5 rounded-full text-xs sm:text-[10px] bg-emerald-100 font-mono text-emerald-800 font-bold">
                    {{ count(array_filter($approvals, fn($a) => $a['status'] === 'approved')) }}
                </span>
            </button>
        </div>

        <!-- Approval Cards Roster -->
        <div class="space-y-4">
            @foreach($approvals as $app)
                <div 
                    x-show="activeTab === 'all' || (activeTab === 'pending' && '{{ $app['status'] }}' !== 'approved') || (activeTab === 'approved' && '{{ $app['status'] }}' === 'approved')"
                    class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default shadow-card hover:border-brand-200 transition-all"
                >
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-border-subtle">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2.5 py-0.5 rounded text-xs sm:text-[10px] font-mono uppercase tracking-wider font-bold {{ $app['status'] === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                    {{ $app['status_label'] }}
                                </span>
                                <span class="text-xs font-mono text-ink-600">Filed {{ $app['submission_date'] }}</span>
                            </div>
                            <h3 class="font-heading font-extrabold text-base text-ink-950">{{ $app['institution'] }}</h3>
                            <p class="text-xs font-semibold text-brand-700 font-sans mt-0.5">{{ $app['application_type'] }}</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2">
                            <button 
                                type="button"
                                @click="openDossier({{ json_encode($app) }})"
                                class="px-3 py-1.5 rounded-xl border border-border-default hover:bg-surface-subtle text-xs font-semibold text-ink-700 transition-colors cursor-pointer flex items-center gap-1.5"
                            >
                                <x-icon name="file-text" class="w-3.5 h-3.5 text-ink-500" />
                                <span>Inspect Dossier</span>
                            </button>

                            @if($app['status'] !== 'approved')
                                <form method="POST" action="{{ route('national_admin.approvals.process', $app['id']) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approved">
                                    <x-ui.button type="submit" variant="primary" size="sm" icon="check">
                                        Approve
                                    </x-ui.button>
                                </form>

                                <form method="POST" action="{{ route('national_admin.approvals.process', $app['id']) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="rejected">
                                    <x-ui.button type="submit" variant="outline" size="sm" icon="x" class="text-rose-700 hover:bg-rose-50 border-rose-200">
                                        Reject
                                    </x-ui.button>
                                </form>
                            @else
                                <div class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                                    <x-icon name="check-circle" class="w-4 h-4 text-emerald-600" />
                                    Gazette Published
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-xs sm:text-[11px] font-mono uppercase text-ink-600 block mb-1">Dossier Summary</span>
                            <p class="text-ink-700 font-sans leading-relaxed">{{ $app['details'] }}</p>
                        </div>
                        <div>
                            <span class="text-xs sm:text-[11px] font-mono uppercase text-ink-600 block mb-1">Inspection Committee</span>
                            <p class="text-ink-700 font-sans font-medium">{{ $app['inspection_committee'] }}</p>
                            <span class="text-xs sm:text-[11px] text-ink-500 block mt-1">Applicant Rector: {{ $app['applicant_rector'] }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Full Statutory Dossier Inspection Modal (Alpine.js) -->
        <div 
            x-show="showModal" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink-950/60 backdrop-blur-xs"
            @keydown.escape.window="showModal = false"
        >
            <div 
                class="bg-surface rounded-2xl border border-border-default shadow-elevated w-full max-w-2xl overflow-hidden"
                @click.away="showModal = false"
            >
                <template x-if="selectedDossier">
                    <div>
                        <div class="px-6 py-4 border-b border-border-default bg-surface-subtle flex items-center justify-between">
                            <div>
                                <span class="text-xs sm:text-[10px] font-mono uppercase tracking-wider text-brand-700 font-bold block" x-text="selectedDossier.application_type"></span>
                                <h3 class="font-heading font-bold text-base text-ink-950" x-text="selectedDossier.institution"></h3>
                            </div>
                            <button type="button" @click="showModal = false" class="text-ink-400 hover:text-ink-700 cursor-pointer">
                                <x-icon name="x" class="w-4 h-4" />
                            </button>
                        </div>

                        <div class="p-4 sm:p-6 space-y-6 text-xs max-h-[75vh] overflow-y-auto">
                            <!-- Summary Block -->
                            <div class="p-4 rounded-xl bg-canvas border border-border-subtle space-y-2">
                                <span class="text-xs sm:text-[11px] font-mono uppercase text-ink-500 font-semibold block">Official Application Filing</span>
                                <p class="text-ink-800 font-sans text-xs leading-relaxed" x-text="selectedDossier.details"></p>
                                <div class="pt-2 flex items-center justify-between text-xs sm:text-[11px] font-mono text-ink-500 border-t border-border-subtle">
                                    <span>Rector / Petitioner: <strong class="text-ink-900" x-text="selectedDossier.applicant_rector"></strong></span>
                                    <span>Submission Date: <span x-text="selectedDossier.submission_date"></span></span>
                                </div>
                            </div>

                            <!-- Statutory Compliance Metrics -->
                            <div>
                                <h4 class="font-heading font-bold text-ink-950 text-xs uppercase tracking-wider mb-3">Federal Accreditation Criteria Evaluation</h4>
                                <div class="grid grid-cols-2 gap-3 font-sans">
                                    <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle flex items-start gap-2.5">
                                        <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                        <div>
                                            <span class="font-bold text-ink-900 block">Land Requirement (25+ Acres)</span>
                                            <span class="text-ink-500 text-xs sm:text-[11px]">Freehold institutional ownership verified via Punjab Land Records Authority.</span>
                                        </div>
                                    </div>
                                    <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle flex items-start gap-2.5">
                                        <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                        <div>
                                            <span class="font-bold text-ink-900 block">Faculty PhD Density</span>
                                            <span class="text-ink-500 text-xs sm:text-[11px]">42% full-time PhD faculty meets Charter Guideline Tier-I.</span>
                                        </div>
                                    </div>
                                    <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle flex items-start gap-2.5">
                                        <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                        <div>
                                            <span class="font-bold text-ink-900 block">PERN-II Digital Library</span>
                                            <span class="text-ink-500 text-xs sm:text-[11px]">100 Mbps fiber optic connection established with access to IEEE and Springer.</span>
                                        </div>
                                    </div>
                                    <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle flex items-start gap-2.5">
                                        <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                        <div>
                                            <span class="font-bold text-ink-900 block">Statutory Council Clearance</span>
                                            <span class="text-ink-500 text-xs sm:text-[11px]">PEC & NCEAC inspection delegations have ratified initial infrastructure.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Inspection Committee Evaluation -->
                            <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle">
                                <span class="text-xs sm:text-[11px] font-mono uppercase text-ink-500 font-semibold block mb-1">Inspection Committee Finding</span>
                                <p class="text-ink-800 font-sans" x-text="selectedDossier.inspection_committee"></p>
                                <p class="text-xs sm:text-[11px] text-ink-500 font-sans mt-1">Recommended for unconditional Gazette SRO publication subject to standard annual QA audit.</p>
                            </div>
                        </div>

                        <!-- Footer Actions in Modal -->
                        <div class="px-6 py-4 border-t border-border-default bg-surface-subtle flex items-center justify-between">
                            <span class="text-xs font-mono text-ink-500" x-text="'Dossier Ref: ' + selectedDossier.id"></span>

                            <div class="flex items-center gap-3">
                                <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-semibold text-ink-600 hover:text-ink-800 cursor-pointer">
                                    Close Dossier
                                </button>

                                <template x-if="selectedDossier.status !== 'approved'">
                                    <form method="POST" :action="'/admin/national/approvals/' + selectedDossier.id" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="approved">
                                        <x-ui.button type="submit" variant="primary" size="sm" icon="check">
                                            Ratify & Issue Gazette SRO
                                        </x-ui.button>
                                    </form>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-layouts.national-admin>
