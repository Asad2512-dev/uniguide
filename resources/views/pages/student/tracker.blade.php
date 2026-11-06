<x-layouts.student 
    title="Admission Tracker — UniGuide Pakistan"
    description="Manage your college application lifecycles from test registration to final fee challan deposit across Punjab and Islamabadi universities."
>
    <div 
        x-data="{
            newModalOpen: false,
            editModalOpen: false,
            deleteModalOpen: false,
            deleteActionUrl: '',
            deleteItemName: '',
            filterStage: 'all',
            activeApp: null,

            openEditModal(app) {
                this.activeApp = JSON.parse(JSON.stringify(app));
                this.editModalOpen = true;
            }
        }" 
        class="space-y-8 pb-12"
    >
        <!-- Header Strip -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-border-default">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                    <x-icon name="file-text" class="w-3.5 h-3.5 text-brand-600" />
                    <span>Application Lifecycle Engine</span>
                </div>
                <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                    My Admission Tracker
                </h1>
                <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                    Track deadlines, entrance test schedules, challan payment status, and admission offers across institutions.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button 
                    type="button" 
                    @click="newModalOpen = true"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-xs transition-smooth cursor-pointer"
                >
                    <x-icon name="plus" class="w-3.5 h-3.5" />
                    <span>Track New Application</span>
                </button>
            </div>
        </div>

        <!-- Disclaimer & Trust Strip -->
        <div class="p-4 rounded-xl bg-surface-subtle border border-border-default flex items-start gap-3 text-xs text-ink-600 font-sans">
            <x-icon name="info" class="w-4 h-4 text-brand-600 shrink-0 mt-0.5" />
            <div>
                <span class="font-semibold text-ink-900">Candidate-Managed Admission Tracker:</span>
                This portal tracks your progress locally. Application submissions, challan payments, and final seat offers must always be officially submitted through each university's designated online admission portal.
            </div>
        </div>

        <!-- Filter Stage Tabs -->
        <div class="flex flex-wrap items-center gap-2 pb-2">
            <button 
                type="button" 
                @click="filterStage = 'all'"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-smooth cursor-pointer"
                :class="filterStage === 'all' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
            >
                All Applications ({{ count($applications) }})
            </button>
            @foreach($stages as $stKey => $stLabel)
                <button 
                    type="button" 
                    @click="filterStage = '{{ $stKey }}'"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-smooth cursor-pointer"
                    :class="filterStage === '{{ $stKey }}' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                >
                    {{ $stLabel }}
                </button>
            @endforeach
        </div>

        <!-- Applications List Cards -->
        <div class="space-y-4">
            @forelse($applications as $app)
                <div 
                    x-show="filterStage === 'all' || filterStage === '{{ $app['stage'] }}'"
                    class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all space-y-4"
                >
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-border-subtle">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 text-brand-700 flex items-center justify-center font-heading font-bold text-sm shrink-0">
                                {{ $app['university_acronym'] }}
                            </div>
                            <div>
                                <h3 class="font-heading font-bold text-base text-ink-950">
                                    {{ $app['program_name'] }}
                                </h3>
                                <p class="text-xs text-ink-500 font-sans">
                                    {{ $app['university_name'] }} • {{ $app['campus'] }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold font-mono {{ match($app['stage']) {
                                'offer_received' => 'bg-success-50 text-success-700 border border-success-200/80 font-bold',
                                'test_scheduled' => 'bg-warning-50 text-warning-700 border border-warning-200/80 font-bold',
                                'applied' => 'bg-brand-50 text-brand-700 border border-brand-200/80 font-bold',
                                default => 'bg-surface-subtle text-ink-700 border border-border-default',
                            } }}">
                                {{ $app['stage_label'] }}
                            </span>
                        </div>
                    </div>

                    <!-- Application Details Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3 text-xs font-sans">
                        <div class="p-3 rounded-xl bg-surface-subtle">
                            <span class="text-xs sm:text-[10px] text-ink-600 font-mono block">Reference / App ID</span>
                            <span class="font-bold text-ink-900 font-mono">{{ $app['application_ref'] }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-surface-subtle">
                            <span class="text-xs sm:text-[10px] text-ink-600 font-mono block">Application Deadline</span>
                            <span class="font-semibold text-ink-800">{{ $app['deadline'] }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-surface-subtle">
                            <span class="text-xs sm:text-[10px] text-ink-600 font-mono block">Challan Status</span>
                            <span class="font-semibold text-ink-800">{{ $app['fee_challan_status'] }}</span>
                        </div>
                        <div class="p-3 rounded-xl bg-surface-subtle">
                            <span class="text-xs sm:text-[10px] text-ink-600 font-mono block">Test / Result</span>
                            <span class="font-semibold text-ink-800">{{ $app['test_date'] }}</span>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if(!empty($app['notes']))
                        <div class="p-3 rounded-xl bg-brand-50/20 border border-brand-100 text-xs text-ink-700 font-sans flex items-start gap-2">
                            <x-icon name="message-square" class="w-3.5 h-3.5 text-brand-600 shrink-0 mt-0.5" />
                            <span><strong class="font-semibold text-ink-900">Notes:</strong> {{ $app['notes'] }}</span>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                        <span class="text-xs sm:text-[11px] text-ink-600 font-mono">Updated {{ $app['last_updated'] }}</span>

                        <div class="flex items-center gap-3">
                            <button 
                                type="button" 
                                @click="openEditModal({{ json_encode($app) }})"
                                class="font-semibold text-brand-600 hover:text-brand-700 cursor-pointer"
                            >
                                Edit Status & Notes
                            </button>

                            <button 
                                type="button" 
                                @click="deleteActionUrl = '{{ route('student.tracker.destroy', $app['id']) }}'; deleteItemName = '{{ addslashes($app['university']) }} ({{ addslashes($app['program']) }})'; deleteModalOpen = true"
                                class="text-ink-400 hover:text-error-600 cursor-pointer p-1 transition-colors"
                                title="Remove from tracker"
                                aria-label="Remove {{ $app['university'] }} application from tracker"
                            >
                                <x-icon name="trash-2" class="w-3.5 h-3.5" />
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 rounded-2xl border border-dashed border-border-default bg-surface text-center space-y-4">
                    <x-icon name="file-text" class="w-8 h-8 text-ink-400 mx-auto" />
                    <h3 class="font-heading font-bold text-base text-ink-900">No applications in your tracker</h3>
                    <p class="text-xs text-ink-500 font-sans max-w-sm mx-auto">
                        Add universities you are planning to apply for, or track existing admissions.
                    </p>
                    <button type="button" @click="newModalOpen = true" class="px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold">
                        + Add First Application
                    </button>
                </div>
            @endforelse
        </div>

        <!-- Add Application Modal -->
        <div 
            x-show="newModalOpen" 
            x-modal-focus="newModalOpen"
            role="dialog" aria-modal="true" aria-label="Add university application"
            @keydown.escape.window="newModalOpen = false"

            x-cloak 
            class="fixed inset-0 z-50 bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div 
                @click.away="newModalOpen = false" 
                class="bg-surface rounded-2xl border border-border-default shadow-modal max-w-lg max-h-[calc(100dvh-2rem)] overflow-y-auto w-full p-4 sm:p-6 space-y-5"
            >
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Add University Application</h3>
                    <button type="button" @click="newModalOpen = false" aria-label="Close add university application" class="text-ink-400 hover:text-ink-700">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <form action="{{ route('student.tracker.store') }}" method="POST" class="space-y-4 text-xs font-sans">
                    @csrf
                    <label class="block space-y-2"><span>University, campus and degree offering</span>
                        <select name="program_id" required class="w-full rounded-xl border-border-default bg-surface p-3">
                            <option value="">Select a recorded offering</option>
                            @foreach($coveragePrograms as $offering)
                                <option value="{{ $offering->id }}">{{ $offering->university->name }} — {{ $offering->campus->name }} — {{ $offering->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="block font-semibold text-ink-800">Application Stage</label>
                            <select name="stage" class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface text-xs">
                                <option value="interested">Interested</option>
                                <option value="preparing">Preparing</option>
                                <option value="applied" selected>Applied</option>
                                <option value="test_scheduled">Test Scheduled</option>
                                <option value="awaiting_result">Awaiting Result</option>
                                <option value="offer_received">Offer Received</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="block font-semibold text-ink-800">Deadline</label>
                            <input type="text" name="deadline" placeholder="15 July 2026" class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface" />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block font-semibold text-ink-800">Reference Number / Roll Number</label>
                        <input type="text" name="application_ref" placeholder="App # NUST-2026-UG-09412" class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface font-mono" />
                    </div>

                    <div class="space-y-1">
                        <label class="block font-semibold text-ink-800">Personal Notes / Strategy</label>
                        <textarea name="notes" rows="2" placeholder="Test preparation notes, challan branch, etc." class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface"></textarea>
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                        <button type="button" @click="newModalOpen = false" aria-label="Close add university application" class="px-4 py-2 rounded-xl border border-border-default bg-surface text-ink-700">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold">
                            Save to Tracker
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Application Modal -->
        <div 
            x-show="editModalOpen" 
            x-modal-focus="editModalOpen"
            role="dialog" aria-modal="true" aria-label="Update application progress"
            @keydown.escape.window="editModalOpen = false"

            x-cloak 
            class="fixed inset-0 z-50 bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div 
                @click.away="editModalOpen = false" 
                class="bg-surface rounded-2xl border border-border-default shadow-modal max-w-lg max-h-[calc(100dvh-2rem)] overflow-y-auto w-full p-4 sm:p-6 space-y-5"
            >
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Update Application Progress</h3>
                    <button type="button" @click="editModalOpen = false" aria-label="Close update application progress" class="text-ink-400 hover:text-ink-700">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <template x-if="activeApp">
                    <form :action="'/student/tracker/' + activeApp.id" method="POST" class="space-y-4 text-xs font-sans">
                        @csrf
                        @method('PUT')

                        <div>
                            <span class="font-bold text-ink-900 block text-sm" x-text="activeApp.program_name"></span>
                            <span class="text-ink-500 font-mono" x-text="activeApp.university_name"></span>
                        </div>

                        <div class="space-y-1">
                            <label class="block font-semibold text-ink-800">Current Stage</label>
                            <select name="stage" x-model="activeApp.stage" class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface text-xs font-semibold">
                                <option value="interested">Interested</option>
                                <option value="preparing">Preparing</option>
                                <option value="applied">Applied</option>
                                <option value="test_scheduled">Test Scheduled</option>
                                <option value="awaiting_result">Awaiting Result</option>
                                <option value="offer_received">Offer Received</option>
                                <option value="closed">Closed / Withdrawn</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="block font-semibold text-ink-800">Challan / Payment Status</label>
                            <input type="text" name="fee_challan_status" x-model="activeApp.fee_challan_status" class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface" />
                        </div>

                        <div class="space-y-1">
                            <label class="block font-semibold text-ink-800">Entrance Test Schedule</label>
                            <input type="text" name="test_date" x-model="activeApp.test_date" class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface" />
                        </div>

                        <div class="space-y-1">
                            <label class="block font-semibold text-ink-800">Candidate Notes</label>
                            <textarea name="notes" x-model="activeApp.notes" rows="3" class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface"></textarea>
                        </div>

                        <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                            <button type="button" @click="editModalOpen = false" aria-label="Close update application progress" class="px-4 py-2 rounded-xl border border-border-default bg-surface text-ink-700">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-brand-600 text-white font-semibold">
                                Update Progress
                            </button>
                        </div>
                    </form>
                </template>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <template x-teleport="body">
            <div 
                x-show="deleteModalOpen" 
                x-cloak 
                class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 md:py-12"
                role="dialog" 
                aria-modal="true" 
                aria-labelledby="delete-tracker-modal-title"
                @keydown.escape.window="deleteModalOpen = false"
            >
                <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 bg-ink-950/40 backdrop-blur-xs" @click="deleteModalOpen = false"></div>
                <div class="flex min-h-full items-center justify-center p-2 text-center sm:p-0">
                    <div x-show="deleteModalOpen" x-transition class="relative transform overflow-hidden rounded-2xl bg-surface border border-border-default text-left shadow-modal w-full max-w-md p-6" @click.stop>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-error-50 text-error-600 border border-error-200">
                                <x-icon name="trash-2" class="w-5 h-5" />
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-heading font-bold text-ink-950" id="delete-tracker-modal-title">Remove Application?</h3>
                                <p class="mt-1 text-xs text-ink-600 font-sans leading-relaxed">
                                    Are you sure you want to remove <strong class="text-ink-950" x-text="deleteItemName"></strong> from your admission tracker?
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-border-subtle">
                            <button type="button" @click="deleteModalOpen = false" class="inline-flex min-h-11 items-center justify-center px-4 py-2 text-xs font-semibold text-ink-700 hover:bg-surface-subtle rounded-lg border border-border-default transition-colors">
                                Cancel
                            </button>
                            <form :action="deleteActionUrl" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-white bg-error-600 hover:bg-error-700 active:bg-error-800 rounded-lg shadow-xs transition-colors">
                                    <x-icon name="trash-2" class="size-4" />
                                    <span>Yes, Remove</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-layouts.student>

