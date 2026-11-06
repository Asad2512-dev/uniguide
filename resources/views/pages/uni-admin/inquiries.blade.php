<x-layouts.university-admin 
    :title="'Student Inquiry Management — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <div x-data="{ 
        statusFilter: 'all', 
        inquiries: @js($inquiries),
        selectedInquiry: null,
        replyDrawerOpen: false,

        openReply(inq) {
            this.selectedInquiry = inq;
            this.replyDrawerOpen = true;
        },

        filteredInquiries() {
            return this.inquiries.filter(i => {
                return this.statusFilter === 'all' || i.status === this.statusFilter;
            });
        }
    }">
        <x-ui.page-header 
            title="Applicant Admissions Inquiries & Triage Desk"
            description="Official communications hub resolving candidate eligibility queries, quota documentation assistance, and test center scheduling."
            badge="{{ count($inquiries) }} Total Inquiries"
            badgeVariant="brand"
        >
            <x-slot:breadcrumbs>
                <x-ui.breadcrumbs 
                    :items="[
                        ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                        ['label' => 'Inquiries']
                    ]"
                />
            </x-slot:breadcrumbs>
        </x-ui.page-header>

        <!-- Status Filter Toolbar -->
        <div class="p-4 rounded-2xl bg-surface border border-border-default mb-6 flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-2">
                <button 
                    type="button" 
                    @click="statusFilter = 'all'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer"
                    :class="statusFilter === 'all' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                >
                    All Inquiries
                </button>
                <button 
                    type="button" 
                    @click="statusFilter = 'new'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5"
                    :class="statusFilter === 'new' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                >
                    <span class="w-2 h-2 rounded-full bg-brand-400"></span>
                    <span>New / Unaddressed</span>
                </button>
                <button 
                    type="button" 
                    @click="statusFilter = 'in_progress'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5"
                    :class="statusFilter === 'in_progress' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                >
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    <span>In Progress</span>
                </button>
                <button 
                    type="button" 
                    @click="statusFilter = 'resolved'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5"
                    :class="statusFilter === 'resolved' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                >
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Resolved</span>
                </button>
            </div>

            <span class="text-xs font-mono text-ink-600">
                Direct Sync with Student Portal
            </span>
        </div>

        <!-- Inquiries List -->
        <div class="space-y-4">
            <template x-for="inq in filteredInquiries()" :key="inq.id">
                <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-border-subtle">
                            <div class="flex items-center gap-2.5">
                                <span class="font-heading font-bold text-sm text-ink-950" x-text="inq.candidate_name"></span>
                                <span class="text-ink-300">•</span>
                                <span class="text-xs font-mono text-ink-500" x-text="inq.qualification"></span>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="text-xs sm:text-[10px] font-mono font-bold uppercase px-2 py-0.5 rounded" :class="inq.priority === 'high' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-surface-subtle text-ink-600'" x-text="inq.priority + ' Priority'"></span>
                                <span class="px-2.5 py-0.5 rounded-full text-xs sm:text-[10px] font-mono font-bold uppercase" :class="inq.status === 'resolved' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (inq.status === 'new' ? 'bg-brand-50 text-brand-700 border border-brand-200' : 'bg-amber-50 text-amber-700 border border-amber-200')" x-text="inq.status"></span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs text-brand-700 font-semibold block" x-text="'Target: ' + inq.target_program"></span>
                            <h4 class="font-heading font-bold text-base text-ink-950 mt-1" x-text="inq.subject"></h4>
                            <p class="text-xs text-ink-600 font-sans mt-1.5 leading-relaxed" x-text="inq.message"></p>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                        <span class="text-xs sm:text-[11px] text-ink-600 font-mono" x-text="'Assigned: ' + inq.assigned_to + ' • ' + inq.received_at"></span>

                        <button 
                            type="button" 
                            @click="openReply(inq)"
                            class="px-4 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-semibold text-xs shadow-xs transition-smooth cursor-pointer flex items-center gap-1.5"
                        >
                            <span>Open Ticket & Reply</span>
                            <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Reply Ticket Drawer -->
        <div 
            x-show="replyDrawerOpen" 
            x-modal-focus="replyDrawerOpen"
            @keydown.escape.window="replyDrawerOpen = false"
            role="dialog"
            aria-modal="true"
            aria-label="Reply to student inquiry"
            x-cloak 
            class="fixed inset-0 z-50 overflow-hidden bg-ink-950/40 backdrop-blur-xs flex justify-end"
        >
            <div 
                x-show="replyDrawerOpen" 
                x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="w-full max-w-xl bg-surface border-l border-border-default shadow-elevated h-full overflow-y-auto flex flex-col justify-between p-6 sm:p-8"
                @click.outside="replyDrawerOpen = false"
            >
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-border-subtle">
                        <div>
                            <span class="text-xs sm:text-[10px] font-mono text-ink-600 uppercase block font-bold">Admissions Inquiry Ticket</span>
                            <h3 class="font-heading font-bold text-lg text-ink-950 mt-0.5" x-text="selectedInquiry ? selectedInquiry.candidate_name : ''"></h3>
                        </div>
                        <button type="button" @click="replyDrawerOpen = false" class="text-ink-400 hover:text-ink-700 text-base">✕</button>
                    </div>

                    <!-- Candidate Academic Card -->
                    <div class="p-4 rounded-xl bg-surface-subtle border border-border-default space-y-1.5 text-xs font-sans">
                        <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-600 font-bold block">Candidate Context</span>
                        <div class="font-bold text-ink-900" x-text="selectedInquiry ? selectedInquiry.qualification : ''"></div>
                        <div class="text-ink-600" x-text="'Target Discipline: ' + (selectedInquiry ? selectedInquiry.target_program : '')"></div>
                        <div class="text-xs sm:text-[11px] text-ink-400 font-mono" x-text="(selectedInquiry ? selectedInquiry.candidate_email : '') + ' • ' + (selectedInquiry ? selectedInquiry.candidate_phone : '')"></div>
                    </div>

                    <!-- Message History -->
                    <div class="space-y-3">
                        <div class="p-4 rounded-xl bg-canvas border border-border-default space-y-1">
                            <span class="text-xs sm:text-[10px] font-mono text-ink-600 font-bold uppercase block">Applicant Question</span>
                            <p class="text-xs text-ink-800 font-sans leading-relaxed" x-text="selectedInquiry ? selectedInquiry.message : ''"></p>
                        </div>

                        <!-- Previous Replies -->
                        <template x-if="selectedInquiry && selectedInquiry.replies.length > 0">
                            <div class="space-y-2">
                                <template x-for="r in selectedInquiry.replies" :key="r.timestamp">
                                    <div class="p-4 rounded-xl bg-brand-50/40 border border-brand-200/80 space-y-1">
                                        <div class="flex items-center justify-between text-xs sm:text-[10px] font-mono text-brand-700 font-bold">
                                            <span x-text="r.sender"></span>
                                            <span x-text="r.timestamp"></span>
                                        </div>
                                        <p class="text-xs text-brand-950 font-sans" x-text="r.body"></p>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Compose Official Response Form -->
                    <form :action="'{{ route('uni_admin.inquiries') }}/' + (selectedInquiry ? selectedInquiry.id : '') + '/reply'" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Official Response from Admissions Directorate</label>
                            <textarea 
                                name="reply" 
                                rows="4" 
                                required 
                                placeholder="Dear candidate, in accordance with the NUST Fall 2026 admission policy..." 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                            ></textarea>
                            <span class="text-xs sm:text-[10px] text-ink-600 mt-1 block">Candidate receives instant notification in their student portal and verified email.</span>
                        </div>

                        <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                            <button type="button" @click="replyDrawerOpen = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-xs">Dispatch Response</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.university-admin>

