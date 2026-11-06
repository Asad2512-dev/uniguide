<x-layouts.student 
    title="Saved Degree Programs — UniGuide Pakistan"
    description="Your collection of saved undergraduate and graduate degrees with tuition fees, duration, and merit cutoff indicators."
>
<p class="mb-4 rounded-xl border border-border-default bg-surface p-3 text-sm text-ink-600">Saved records outside current coverage remain in your account history and are hidden from this active list.</p>

    <div x-data="{ searchTerm: '', deleteModalOpen: false, deleteActionUrl: '', deleteItemName: '' }" class="space-y-8 pb-12">
        
        <!-- Header Strip -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-border-default">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                    <x-icon name="bookmark" class="w-3.5 h-3.5 text-brand-600" />
                    <span>Academic Degree Collection</span>
                </div>
                <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                    Saved Degree Programs
                </h1>
                <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                    Compare tuition fees, program accreditations, and required entrance test cutoffs for your shortlisted programs.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('programs.index') }}" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-xs transition-smooth"
                >
                    <x-icon name="plus" class="w-3.5 h-3.5" />
                    <span>Browse Degree Catalog</span>
                </a>
            </div>
        </div>

        <!-- Saved Programs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($programs as $prog)
                <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                            <span class="font-mono font-bold text-xs text-brand-700 bg-brand-50 px-2 py-0.5 rounded">
                                {{ $prog['university_acronym'] }}
                            </span>
                            <span class="text-xs sm:text-[10px] font-mono text-ink-600">{{ $prog['duration'] }}</span>
                        </div>

                        <div>
                            <h3 class="font-heading font-bold text-base text-ink-950">
                                {{ $prog['name'] }}
                            </h3>
                            <p class="text-xs text-ink-500 font-sans mt-0.5">{{ $prog['campus'] }}</p>
                        </div>

                        <div class="p-3 rounded-xl bg-surface-subtle text-xs font-sans space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-ink-500">Semester Tuition:</span>
                                <span class="font-bold text-ink-900 font-mono">{{ $prog['fee_semester'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-ink-500">Your Aggregate:</span>
                                <span class="font-bold text-brand-700 font-mono">{{ $prog['calculated_aggregate'] }}% (vs {{ $prog['closing_merit_ref'] }})</span>
                            </div>
                        </div>

                        <div class="text-xs sm:text-[11px] font-mono text-success-700 flex items-center gap-1">
                            <x-icon name="shield-check" class="w-3.5 h-3.5 text-success-600" />
                            <span>{{ $prog['accreditation'] }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-border-subtle flex items-center justify-between text-xs mt-6">
                        <button type="button" @click="deleteActionUrl = '{{ route('student.saved.programs.destroy', $prog['slug']) }}'; deleteItemName = '{{ addslashes($prog['name']) }}'; deleteModalOpen = true" class="text-ink-400 hover:text-error-600 cursor-pointer flex items-center gap-1 transition-colors">
                            <x-icon name="trash-2" class="w-3.5 h-3.5" />
                            <span>Remove</span>
                        </button>

                        <div class="flex items-center gap-3">
                            <a 
                                href="{{ route('student.tracker') }}"
                                class="font-semibold text-brand-600 hover:text-brand-700"
                            >
                                Track in Admissions →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-12 rounded-2xl border border-dashed border-border-default bg-surface text-center space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-surface-subtle text-ink-400 flex items-center justify-center mx-auto">
                        <x-icon name="bookmark" class="w-6 h-6" />
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-heading font-bold text-base text-ink-900">No degree programs saved</h3>
                        <p class="text-xs text-ink-500 font-sans max-w-sm mx-auto">
                            Browse thousands of undergraduate computing, engineering, and medical degrees.
                        </p>
                    </div>
                    <a href="{{ route('programs.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold shadow-xs">
                        Browse Degree Programs
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Delete Confirmation Modal -->
        <template x-teleport="body">
            <div 
                x-show="deleteModalOpen" 
                x-cloak 
                class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 md:py-12"
                role="dialog" 
                aria-modal="true" 
                aria-labelledby="delete-prog-modal-title"
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
                                <h3 class="text-base font-heading font-bold text-ink-950" id="delete-prog-modal-title">Remove Degree Program?</h3>
                                <p class="mt-1 text-xs text-ink-600 font-sans leading-relaxed">
                                    Are you sure you want to remove <strong class="text-ink-950" x-text="deleteItemName"></strong> from your saved programs? You can re-add it anytime from the Degree Catalog.
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

