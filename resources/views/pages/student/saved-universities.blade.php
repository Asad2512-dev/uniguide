<x-layouts.student 
    title="Shortlisted Universities — UniGuide Pakistan"
    description="Your saved Pakistani universities collection with deadline alerts, fee ranges, and institutional comparison."
>
    <div x-data="{ searchTerm: '' }" class="space-y-8 pb-12">
        
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
                    HEC Rank
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
                    class="p-6 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between"
                >
                    <div class="space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                            <span class="font-heading font-bold text-xs uppercase px-2 py-0.5 rounded bg-brand-50 text-brand-700 font-mono">
                                {{ $uni['hec_ranking'] }}
                            </span>
                            <span class="text-xs font-mono text-ink-400">Deadline: {{ $uni['deadline'] }}</span>
                        </div>

                        <div>
                            <span class="text-xs text-ink-400 uppercase font-mono font-semibold">{{ $uni['acronym'] }} • {{ $uni['city'] }}</span>
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
                                <span class="text-[10px] text-ink-400 block font-mono">Estimated Semester Tuition</span>
                                <span class="font-bold text-ink-900 font-mono">{{ $uni['fee_range'] }}</span>
                            </div>
                            <span class="text-xs font-semibold text-brand-700 bg-brand-50 border border-brand-200/60 px-2 py-0.5 rounded-full">
                                {{ $uni['match_tier'] }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-border-subtle flex items-center justify-between text-xs mt-6">
                        <form action="{{ route('student.saved.universities.destroy', $uni['slug']) }}" method="POST" onsubmit="return confirm('Remove {{ $uni['acronym'] }} from your shortlist?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-ink-400 hover:text-error-600 font-medium cursor-pointer flex items-center gap-1">
                                <x-icon name="trash-2" class="w-3.5 h-3.5" />
                                <span>Remove</span>
                            </button>
                        </form>

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
                            Browse the National University Directory to shortlist HEC-recognized institutions.
                        </p>
                    </div>
                    <a href="{{ route('universities.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold shadow-xs">
                        Browse 248+ Universities
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.student>

