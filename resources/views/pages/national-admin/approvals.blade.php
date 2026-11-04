<x-layouts.national-admin title="Charter Approval Center — Institutional Accreditation">
    <x-ui.page-header 
        title="University Approval Center"
        description="Statutory review board for constituent campus approvals, degree charter level upgrades, and statutory council accreditations."
        badge="HEC Regulatory Board"
        badgeVariant="warning"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
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

    <div class="space-y-6">
        @foreach($approvals as $app)
            <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card hover:border-brand-200 transition-all">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-border-subtle">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-mono uppercase tracking-wider font-bold {{ $app['status'] === 'approved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                {{ $app['status_label'] }}
                            </span>
                            <span class="text-xs font-mono text-ink-400">Filed {{ $app['submission_date'] }}</span>
                        </div>
                        <h3 class="font-heading font-extrabold text-base text-ink-950">{{ $app['institution'] }}</h3>
                        <p class="text-xs font-semibold text-brand-700 font-sans mt-0.5">{{ $app['application_type'] }}</p>
                    </div>

                    <!-- Approval Workflow Action Buttons -->
                    @if($app['status'] !== 'approved')
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('national_admin.approvals.process', $app['id']) }}" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="approved">
                                <x-ui.button type="submit" variant="primary" size="sm" icon="check">
                                    Approve & Issue Gazette
                                </x-ui.button>
                            </form>

                            <form method="POST" action="{{ route('national_admin.approvals.process', $app['id']) }}" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="rejected">
                                <x-ui.button type="submit" variant="outline" size="sm" icon="x" class="text-rose-700 hover:bg-rose-50 border-rose-200">
                                    Reject / Deficiency Notified
                                </x-ui.button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                            <x-icon name="check-circle" class="w-4 h-4 text-emerald-600" />
                            Gazette SRO Ratified & Published
                        </div>
                    @endif
                </div>

                <div class="pt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-[11px] font-mono uppercase text-ink-400 block mb-1">Dossier Summary</span>
                        <p class="text-ink-700 font-sans leading-relaxed">{{ $app['details'] }}</p>
                    </div>
                    <div>
                        <span class="text-[11px] font-mono uppercase text-ink-400 block mb-1">Inspection Committee</span>
                        <p class="text-ink-700 font-sans font-medium">{{ $app['inspection_committee'] }}</p>
                        <span class="text-[11px] text-ink-500 block mt-1">Applicant Rector: {{ $app['applicant_rector'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-layouts.national-admin>
