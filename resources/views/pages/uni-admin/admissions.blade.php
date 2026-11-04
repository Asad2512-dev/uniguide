<x-layouts.university-admin 
    :title="'Admission Cycles & Intake Management — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <div x-data="{ editCycleModal: false, activeCycle: null }">
        <x-ui.page-header 
            title="Admission Cycles & Quota Allocations"
            description="Manage annual undergraduate and postgraduate intake windows, entry test series dates, merit lists calendar, and statutory quota reservations."
            badge="Active Cycle: Fall 2026"
            badgeVariant="brand"
        >
            <x-slot:breadcrumbs>
                <x-ui.breadcrumbs 
                    :items="[
                        ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                        ['label' => 'Admission Cycles']
                    ]"
                />
            </x-slot:breadcrumbs>
        </x-ui.page-header>

        <!-- Admission Cycles Grid -->
        <div class="space-y-6">
            @foreach($cycles as $cyc)
                <div class="p-6 sm:p-8 rounded-2xl bg-surface border border-border-default shadow-card space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-border-subtle">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h3 class="font-heading font-bold text-lg text-ink-950">{{ $cyc['name'] }}</h3>
                                <x-ui.badge :variant="$cyc['status'] === 'open' ? 'success' : 'neutral'" size="sm" :dot="true">
                                    {{ $cyc['status_badge'] }}
                                </x-ui.badge>
                            </div>
                            <span class="text-xs text-ink-500 font-sans block mt-1">
                                Academic Level: <strong class="text-ink-800">{{ $cyc['level'] }}</strong> • Portal Opens: {{ $cyc['portal_open_date'] }}
                            </span>
                        </div>

                        <button 
                            type="button" 
                            @click="activeCycle = @js($cyc); editCycleModal = true"
                            class="px-4 py-2 rounded-xl border border-border-default hover:bg-surface-subtle text-xs font-semibold text-ink-700 transition-smooth cursor-pointer shrink-0"
                        >
                            Modify Timeline & Status →
                        </button>
                    </div>

                    <!-- Key Milestones Strip -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-sans">
                        <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle">
                            <span class="text-[10px] text-ink-400 uppercase font-mono block">Online Deadline</span>
                            <span class="font-bold text-ink-950 font-mono text-sm">{{ $cyc['deadline_date'] }}</span>
                        </div>
                        <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle">
                            <span class="text-[10px] text-ink-400 uppercase font-mono block">Entry Test Series</span>
                            <span class="font-bold text-brand-700 font-sans truncate block">{{ $cyc['entry_tests'] }}</span>
                        </div>
                        <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle">
                            <span class="text-[10px] text-ink-400 uppercase font-mono block">1st Merit List</span>
                            <span class="font-bold text-ink-950 font-mono">{{ $cyc['merit_list_1'] }}</span>
                        </div>
                        <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle">
                            <span class="text-[10px] text-ink-400 uppercase font-mono block">Total Seats / Candidates</span>
                            <span class="font-bold text-ink-950 font-mono">{{ number_format($cyc['total_seats']) }} / {{ number_format($cyc['applications_received']) }}</span>
                        </div>
                    </div>

                    <!-- Quota Allocations Breakdown -->
                    <div>
                        <h4 class="font-heading font-bold text-xs uppercase tracking-wider text-ink-400 mb-3">
                            Statutory Quota Allocation Scheme
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                            @foreach($cyc['quotas'] as $q)
                                <div class="p-3.5 rounded-xl border border-border-subtle bg-surface-subtle flex items-center justify-between text-xs font-sans">
                                    <div>
                                        <span class="font-semibold text-ink-900 block">{{ $q['category'] }}</span>
                                        <span class="text-[10px] text-ink-400 font-mono">{{ $q['seats'] }} Allocated Seats</span>
                                    </div>
                                    <span class="font-mono font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded">
                                        {{ $q['percentage'] }}%
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Edit Cycle Modal -->
        <div 
            x-show="editCycleModal" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-surface rounded-2xl border border-border-default p-6 max-w-lg w-full shadow-elevated space-y-5" @click.outside="editCycleModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Update Cycle Milestones</h3>
                    <button type="button" @click="editCycleModal = false" class="text-ink-400 hover:text-ink-700 text-sm">✕</button>
                </div>

                <form action="{{ route('uni_admin.admissions.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="cycle_id" :value="activeCycle ? activeCycle.id : ''" />

                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Cycle Status</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans">
                            <option value="open">Open (Accepting Applications)</option>
                            <option value="upcoming">Upcoming (Pre-announcement)</option>
                            <option value="closed">Closed (Cycle Concluded)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Online Application Deadline</label>
                        <input type="text" name="deadline_date" :value="activeCycle ? activeCycle.deadline_date : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-mono" />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Entry Test Series Details</label>
                        <input type="text" name="entry_tests" :value="activeCycle ? activeCycle.entry_tests : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">1st Merit List Date</label>
                            <input type="text" name="merit_list_1" :value="activeCycle ? activeCycle.merit_list_1 : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">2nd Merit List Date</label>
                            <input type="text" name="merit_list_2" :value="activeCycle ? activeCycle.merit_list_2 : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-mono" />
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                        <button type="button" @click="editCycleModal = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold">Save Cycle Timeline</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.university-admin>
