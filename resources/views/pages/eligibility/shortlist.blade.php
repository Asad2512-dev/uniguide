<x-layouts.public 
    title="Personalized Shortlist Portfolio — UniGuide Pakistan"
    description="Your curated shortlist of eligible Pakistani universities with deadline alerts, verified tuition fees, and one-click side-by-side comparison."
>
    <div class="min-h-screen bg-canvas pb-24">
        
        <!-- Header Strip -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Eligibility Checker', 'url' => route('eligibility.index')],
                        ['label' => 'Personalized Shortlist Portfolio']
                    ]" />
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                            <x-icon name="bookmark" class="w-3.5 h-3.5 text-brand-600" />
                            <span>Curated Candidate Portfolio</span>
                        </div>
                        <h1 class="font-heading font-bold text-2xl sm:text-3xl lg:text-4xl text-ink-950 tracking-tight">
                            Personalized University Shortlist
                        </h1>
                        <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                            Institutions where you meet statutory prerequisites, saved for quick comparison and application tracking.
                        </p>
                    </div>

                    <!-- Compare All Shortlist Button -->
                    @if(count($universities) > 1)
                        @php $slugsParam = implode(',', array_column($universities, 'slug')); @endphp
                        <a 
                            href="{{ route('compare.results', ['unis' => $slugsParam]) }}"
                            class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold font-sans shadow-card transition-smooth inline-flex items-center gap-2"
                        >
                            <x-icon name="git-compare" class="w-4 h-4" />
                            <span>Compare Shortlist Side-by-Side</span>
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <!-- Main Shortlist Content -->
        <main class="landing-container mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($universities as $uni)
                    <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                                <span class="font-heading font-bold text-xs uppercase px-2 py-0.5 rounded bg-brand-50 text-brand-700 font-mono">
                                    {{ $uni['hec_ranking'] ?? 'University Registry' }}
                                </span>
                                <span class="text-xs font-mono text-ink-600">{{ $uni['deadline'] ?? '15 July 2026' }}</span>
                            </div>

                            <div>
                                <span class="text-xs text-ink-600 uppercase font-mono font-semibold">{{ $uni['acronym'] }} • {{ $uni['city'] }}</span>
                                <h3 class="font-heading font-bold text-lg text-ink-950 mt-0.5">
                                    <a href="{{ route('universities.show', $uni['slug']) }}" class="hover:text-brand-600 transition-colors">
                                        {{ $uni['name'] }}
                                    </a>
                                </h3>
                                <p class="text-xs text-ink-500 font-sans mt-1">
                                    Tuition: <strong class="text-ink-900 font-mono">{{ $uni['fee_range'] }}</strong>
                                </p>
                            </div>

                            <div class="p-3 rounded-xl bg-surface-subtle text-xs font-sans flex items-center justify-between">
                                <span class="text-ink-500">Eligibility Status:</span>
                                <span class="font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2 py-0.5 rounded-full">
                                    {{ $uni['match_tier'] ?? 'Eligible Target' }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-border-subtle flex items-center justify-between text-xs mt-6">
                            <a 
                                href="{{ route('counselor.chat') }}?prompt=What+is+my+chances+at+{{ urlencode($uni['name']) }}"
                                class="font-semibold text-brand-600 hover:text-brand-700 flex items-center gap-1"
                            >
                                <x-icon name="sparkles" class="w-3.5 h-3.5" />
                                <span>AI Counselor Strategy</span>
                            </a>

                            <a href="{{ route('universities.show', $uni['slug']) }}" class="text-ink-600 hover:text-ink-950 font-medium">
                                Profile →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-12 rounded-2xl border border-dashed border-border-default bg-surface text-center space-y-4">
                        <x-icon name="bookmark" class="w-8 h-8 text-ink-400 mx-auto" />
                        <h3 class="font-heading font-bold text-base text-ink-900">Your shortlist is currently empty</h3>
                        <p class="text-xs text-ink-500 font-sans max-w-sm mx-auto">
                            Run the Academic Eligibility Checker or compare universities to add options to your shortlist.
                        </p>
                        <a href="{{ route('eligibility.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold shadow-xs">
                            Run Eligibility Checker
                        </a>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
</x-layouts.public>

