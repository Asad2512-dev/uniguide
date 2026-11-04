<x-layouts.university-admin 
    :title="'Document Management & Verification Locker — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <div x-data="{ uploadDocModal: false }">
        <x-ui.page-header 
            title="Document Management & Applicant Verification"
            description="Audit official university gazettes, prospectus PDFs, and verify applicant academic certificates and IBCC equivalence dossiers."
            badge="842 Dossiers Processed"
            badgeVariant="brand"
        >
            <x-slot:breadcrumbs>
                <x-ui.breadcrumbs 
                    :items="[
                        ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                        ['label' => 'Documents']
                    ]"
                />
            </x-slot:breadcrumbs>

            <x-slot:actions>
                <x-ui.button variant="primary" size="sm" icon="upload" @click="uploadDocModal = true">
                    Upload Institutional Gazette
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Institutional Document Vault (Top Section) -->
        <div class="mb-10 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Official Institutional Documents Vault</h3>
                    <p class="text-xs text-ink-500 font-sans">Statutory PDFs uploaded to the national public portal.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($documents['institutional_documents'] as $idoc)
                    <div class="p-5 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center">
                                    <x-icon name="file-text" class="w-4 h-4" />
                                </div>
                                <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2 py-0.5 rounded">
                                    {{ $idoc['verification_badge'] }}
                                </span>
                            </div>

                            <div>
                                <h4 class="font-heading font-bold text-sm text-ink-950 line-clamp-2">{{ $idoc['title'] }}</h4>
                                <span class="text-[11px] font-mono text-ink-400 block mt-1">{{ $idoc['file_name'] }} • {{ $idoc['file_size'] }}</span>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                            <span class="text-[10px] text-ink-400 font-mono">{{ $idoc['uploaded_at'] }}</span>
                            <a href="#" class="font-bold text-brand-600 hover:text-brand-700 flex items-center gap-1">
                                <span>Download</span>
                                <x-icon name="download" class="w-3.5 h-3.5" />
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Candidate Submitted Documents Verification Queue -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Applicant Credentials Verification Queue</h3>
                    <p class="text-xs text-ink-500 font-sans">Verify FBISE/BISE attestations, IBCC equivalence certificates, and income affidavits.</p>
                </div>
            </div>

            <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th class="py-3 px-4">Applicant & ID</th>
                            <th class="py-3 px-4">Applied Program</th>
                            <th class="py-3 px-4">Credential Document</th>
                            <th class="py-3 px-4">Submission Date</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 text-right">Verification Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        @foreach($documents['candidate_submissions'] as $sub)
                            <tr class="hover:bg-surface-subtle/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-ink-950 block font-heading">{{ $sub['applicant_name'] }}</span>
                                    <span class="text-[11px] font-mono text-ink-400">{{ $sub['applicant_id'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-ink-800">{{ $sub['program'] }}</td>
                                <td class="py-3.5 px-4 text-ink-900 font-medium">{{ $sub['doc_name'] }}</td>
                                <td class="py-3.5 px-4 font-mono text-ink-500 text-[11px]">{{ $sub['submitted_at'] }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold {{ match($sub['status']) {
                                        'verified' => 'bg-emerald-50 text-emerald-800 border border-emerald-200',
                                        'flagged' => 'bg-amber-50 text-amber-800 border border-amber-200',
                                        default => 'bg-brand-50 text-brand-700 border border-brand-200',
                                    } }}">
                                        {{ $sub['status_label'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($sub['status'] !== 'verified')
                                            <form action="{{ route('uni_admin.documents.verify', $sub['id']) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="verified" />
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold text-xs border border-emerald-200 cursor-pointer">
                                                    ✓ Approve
                                                </button>
                                            </form>
                                        @endif

                                        @if($sub['status'] !== 'flagged')
                                            <form action="{{ route('uni_admin.documents.verify', $sub['id']) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="status" value="flagged" />
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 font-semibold text-xs border border-amber-200 cursor-pointer">
                                                    ! Flag Discrepancy
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Upload Gazette Modal -->
        <div 
            x-show="uploadDocModal" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-surface rounded-2xl border border-border-default p-6 max-w-md w-full shadow-elevated space-y-5" @click.outside="uploadDocModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Upload Institutional Document</h3>
                    <button type="button" @click="uploadDocModal = false" class="text-ink-400 hover:text-ink-700 text-sm">✕</button>
                </div>

                <form action="{{ route('uni_admin.documents.upload') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Document Title</label>
                        <input type="text" name="title" placeholder="e.g. Approved Academic Calendar 2026-2027" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Document Category</label>
                        <select name="type" class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans">
                            <option value="prospectus">Undergraduate / Graduate Prospectus</option>
                            <option value="gazette">Accreditation Council Gazette Notification</option>
                            <option value="fee_gazette">Senate Approved Fee Schedule</option>
                            <option value="policy">Statutory Admission Regulations</option>
                        </select>
                    </div>

                    <div class="p-6 rounded-xl border border-dashed border-border-default text-center space-y-2">
                        <x-icon name="upload-cloud" class="w-6 h-6 text-brand-600 mx-auto" />
                        <span class="text-xs font-semibold text-ink-800 block">Select PDF Document (Max 50MB)</span>
                        <span class="text-[10px] text-ink-400 font-sans block">Official documents must bear institutional seal</span>
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                        <button type="button" @click="uploadDocModal = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold">Upload to Vault</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.university-admin>
