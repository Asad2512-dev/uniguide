<x-layouts.student 
    title="Shortlisted Universities — UniGuide Pakistan"
    description="Your saved Pakistani universities collection with deadline alerts, fee ranges, and institutional comparison."
>
<p class="mb-4 rounded-xl border border-border-default bg-surface p-3 text-sm text-ink-600">Saved records outside current coverage remain in your account history and are hidden from this active list.</p>

    <div x-data="{ searchTerm: '', deleteModalOpen: false, deleteActionUrl: '', deleteItemName: '' }" class="space-y-8 pb-12">
        
        <!-- Header Strip -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-border-default">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                    <x-icon name="building-2" class="w-3.5 h-3.5 text-brand-600" />
                    <span>Candidate Shortlist</span>
                </div>
                <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                    Shortlisted Universities
                </h1>
                <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                    Track closing cutoffs, application deadlines, and fee structures for your targeted institutions.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('universities.index') }}" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-xs transition-smooth"
                >
                    <x-icon name="plus" class="w-3.5 h-3.5" />
                    <span>Explore & Add More Universities</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Filter -->
            <div class="relative max-w-xs w-full">
                <x-icon name="search" class="w-4 h-4 text-ink-400 absolute left-3 top-1/2 -translate-y-1/2" />
                <input 
                    type="text" 
                    x-model="searchTerm"
                    placeholder="Filter saved universities..." 
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-border-default bg-surface text-ink-900 placeholder:text-ink-400 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                />
            </div>

            <!-- Sort Controls -->
            <div class="flex items-center gap-2 text-xs font-sans">
                <span class="text-ink-500">Sort by:</span>
                <a 
                    href="{{ route('student.saved.universities', ['sort' => 'rank']) }}" 
                    class="px-3 py-1.5 rounded-lg border transition-smooth {{ $activeSort === 'rank' ? 'bg-brand-50 border-brand-300 text-brand-700 font-semibold' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                >
                    Rank
                </a>
                <a 
                    href="{{ route('student.saved.universities', ['sort' => 'fee_low']) }}" 
                    class="px-3 py-1.5 rounded-lg border transition-smooth {{ $activeSort === 'fee_low' ? 'bg-brand-50 border-brand-300 text-brand-700 font-semibold' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                >
                    Tuition: Low to High
                </a>
                <a 
                    href="{{ route('student.saved.universities', ['sort' => 'deadline']) }}" 
                    class="px-3 py-1.5 rounded-lg border transition-smooth {{ $activeSort === 'deadline' ? 'bg-brand-50 border-brand-300 text-brand-700 font-semibold' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                >
                    Nearest Deadline
                </a>
            </div>
        </div>

        <!-- Saved Universities Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($universities as $uni)
                <div 
                    x-show="!searchTerm || '{{ strtolower($uni['name'] . ' ' . $uni['acronym'] . ' ' . $uni['city']) }}'.includes(searchTerm.toLowerCase())"
                    class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between"
                >
                    <div class="space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                            
                            <span class="text-xs font-mono text-ink-600">Deadline: {{ $uni['deadline'] }}</span>
                        </div>

                        <div>
                            <span class="text-xs text-ink-600 uppercase font-mono font-semibold">{{ $uni['acronym'] }} • {{ $uni['city'] }}</span>
                            <h3 class="font-heading font-bold text-lg text-ink-950 mt-0.5">
                                <a href="{{ route('universities.show', $uni['slug']) }}" class="hover:text-brand-600 transition-colors">
                                    {{ $uni['name'] }}
                                </a>
                            </h3>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Sector: <span class="capitalize font-medium text-ink-800">{{ $uni['sector'] }}</span> • Region: {{ $uni['province_label'] }}
                            </p>
                        </div>

                        <div class="p-3 rounded-xl bg-surface-subtle text-xs font-sans flex items-center justify-between">
                            <div>
                                <span class="text-xs sm:text-[10px] text-ink-600 block font-mono">Estimated Semester Tuition</span>
                                <span class="font-bold text-ink-900 font-mono">{{ $uni['fee_range'] }}</span>
                            </div>
                            <span class="text-xs font-semibold text-brand-700 bg-brand-50 border border-brand-200/60 px-2 py-0.5 rounded-full">
                                {{ $uni['match_tier'] }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-border-subtle flex items-center justify-between text-xs mt-6">
                        <button type="button" @click="deleteActionUrl = '{{ route('student.saved.universities.destroy', $uni['slug']) }}'; deleteItemName = '{{ addslashes($uni['name']) }}'; deleteModalOpen = true" class="text-ink-400 hover:text-error-600 font-medium cursor-pointer flex items-center gap-1 transition-colors">
                            <x-icon name="trash-2" class="w-3.5 h-3.5" />
                            <span>Remove</span>
                        </button>

                        <div class="flex items-center gap-3">
                            <a 
                                href="{{ route('counselor.chat') }}?prompt=Give+me+an+admissions+strategy+for+{{ urlencode($uni['name']) }}"
                                class="font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1"
                            >
                                <x-icon name="sparkles" class="w-3 h-3" />
                                <span>AI Strategy</span>
                            </a>
                            <a href="{{ route('universities.show', $uni['slug']) }}" class="font-semibold text-ink-800 hover:text-brand-600">
                                View Profile →
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-12 rounded-2xl border border-dashed border-border-default bg-surface text-center space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-surface-subtle text-ink-400 flex items-center justify-center mx-auto">
                        <x-icon name="building-2" class="w-6 h-6" />
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-heading font-bold text-base text-ink-900">Your university shortlist is empty</h3>
                        <p class="text-xs text-ink-500 font-sans max-w-sm mx-auto">
                            Browse the University Directory to shortlist listed institutions.
                        </p>
                    </div>
                    <a href="{{ route('universities.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold shadow-xs">
                        Browse {{ $coverageCounts['universities'] }} Universities
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
                aria-labelledby="delete-modal-title"
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
                                <h3 class="text-base font-heading font-bold text-ink-950" id="delete-modal-title">Remove University?</h3>
                                <p class="mt-1 text-xs text-ink-600 font-sans leading-relaxed">
                                    Are you sure you want to remove <strong class="text-ink-950" x-text="deleteItemName"></strong> from your saved shortlist? You can re-add it anytime from the University Directory.
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

