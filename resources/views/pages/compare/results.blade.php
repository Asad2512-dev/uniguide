<x-layouts.public 
    title="Side-by-Side University Comparison — UniGuide Pakistan"
    description="Compare up to four Pakistani universities across statutory tuition fees, merit formulas, entry test requirements, and hostel facilities."
>
    <div 
        x-data="{
            highlightDifferences: false,
            mobileActiveIndex: 0,
            activeTab: 'all',
            universities: @js($universities),
            sections: @js($sections),
            removeColumn(slug) {
                const currentSlugs = this.universities.map(u => u.slug).filter(s => s !== slug);
                if (currentSlugs.length === 0) {
                    window.location.href = '{{ route('compare.builder') }}';
                } else {
                    window.location.href = '{{ route('compare.results') }}?unis=' + currentSlugs.join(',');
                }
            }
        }"
        class="min-h-screen bg-canvas pb-24"
    >
        <!-- Header Strip -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Compare Universities', 'url' => route('compare.builder')],
                        ['label' => 'Side-by-Side Results']
                    ]" />
                </div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                            <x-icon name="git-compare" class="w-3.5 h-3.5 text-brand-600" />
                            <span>Comparing {{ count($universities) }} Institutions</span>
                        </div>
                        <h1 class="font-heading font-bold text-2xl sm:text-3xl lg:text-4xl text-ink-950 tracking-tight">
                            Side-by-Side Institutional Benchmark
                        </h1>
                        <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                            Audited tuition fees, statutory entrance test formulas, accreditation standings, and financial aid policies.
                        </p>
                    </div>

                    <!-- Controls: Highlight Differences & Add University -->
                    <div class="flex flex-wrap items-center gap-3">
                        <button 
                            type="button" 
                            @click="highlightDifferences = !highlightDifferences"
                            class="px-3.5 py-2 rounded-xl border text-xs font-semibold font-sans transition-all cursor-pointer flex items-center gap-2"
                            :class="highlightDifferences ? 'bg-amber-50 border-amber-300 text-amber-900 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle'"
                        >
                            <span class="w-2 h-2 rounded-full" :class="highlightDifferences ? 'bg-amber-500 animate-pulse' : 'bg-ink-300'"></span>
                            <span>Highlight Differences</span>
                        </button>

                        <a 
                            href="{{ route('compare.builder') }}" 
                            class="px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold font-sans shadow-xs transition-smooth flex items-center gap-1.5"
                        >
                            <x-icon name="plus" class="w-3.5 h-3.5" />
                            <span>Add / Change Universities</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mobile University Switcher Tabs -->
        <div class="lg:hidden sticky top-16 z-30 bg-surface border-b border-border-default px-4 py-2.5">
            <span class="text-[10px] uppercase font-mono text-ink-400 block mb-1">Switch View Column:</span>
            <div class="flex items-center gap-2 overflow-x-auto pb-1">
                @foreach($universities as $index => $uni)
                    <button 
                        type="button" 
                        @click="mobileActiveIndex = {{ $index }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-smooth"
                        :class="mobileActiveIndex === {{ $index }} ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface-subtle border border-border-default text-ink-700'"
                    >
                        {{ $uni['acronym'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            
            <!-- Sticky Header Strip for Desktop -->
            <div class="hidden lg:grid grid-cols-12 gap-4 sticky top-16 z-30 bg-surface/95 backdrop-blur-xs p-4 rounded-2xl border border-border-default shadow-card mb-8">
                <div class="col-span-3 flex items-center">
                    <span class="font-heading font-bold text-xs uppercase text-ink-500 tracking-wider">Evaluation Criteria</span>
                </div>

                @php $colSpan = match(count($universities)) { 1 => 'col-span-9', 2 => 'col-span-4', 3 => 'col-span-3', default => 'col-span-2' }; @endphp

                @foreach($universities as $uni)
                    <div class="{{ $colSpan }} flex flex-col justify-between p-2 rounded-xl bg-surface-subtle border border-border-subtle">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="font-heading font-bold text-sm text-ink-950">{{ $uni['acronym'] }}</span>
                                <button 
                                    type="button" 
                                    @click="removeColumn('{{ $uni['slug'] }}')"
                                    class="text-ink-400 hover:text-error-600 p-0.5 cursor-pointer"
                                    title="Remove from comparison"
                                >
                                    <x-icon name="x" class="w-3.5 h-3.5" />
                                </button>
                            </div>
                            <span class="text-[11px] text-ink-500 font-sans block truncate">{{ $uni['name'] }}</span>
                        </div>

                        <div class="mt-2 pt-1 border-t border-border-subtle flex items-center justify-between text-[11px]">
                            <span class="font-mono text-brand-700 font-semibold">{{ $uni['fee_range'] }}</span>
                            <a href="{{ route('universities.show', $uni['slug']) }}" class="text-brand-600 hover:text-brand-700 font-semibold">
                                Profile →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Comparison Metric Sections -->
            <div class="space-y-8">
                @foreach($sections as $secKey => $sec)
                    <div class="bg-surface rounded-2xl border border-border-default shadow-card overflow-hidden">
                        <!-- Section Header -->
                        <div class="p-4 sm:p-5 bg-surface-subtle/80 border-b border-border-default flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center font-bold">
                                <x-icon :name="$sec['icon']" class="w-4 h-4" />
                            </div>
                            <h2 class="font-heading font-bold text-sm sm:text-base text-ink-950">
                                {{ $sec['title'] }}
                            </h2>
                        </div>

                        <!-- Rows Table Grid -->
                        <div class="divide-y divide-border-subtle text-xs font-sans">
                            @foreach($sec['metrics'] as $metricLabel => $values)
                                @php 
                                    $allSame = (count(array_unique(array_map('strval', $values))) === 1);
                                @endphp
                                <div 
                                    class="grid grid-cols-1 lg:grid-cols-12 gap-3 p-4 sm:p-5 transition-colors"
                                    :class="highlightDifferences && !{{ $allSame ? 'true' : 'false' }} ? 'bg-amber-50/30' : 'hover:bg-surface-subtle/50'"
                                >
                                    <!-- Metric Label -->
                                    <div class="lg:col-span-3 font-semibold text-ink-900 flex items-center justify-between">
                                        <span>{{ $metricLabel }}</span>
                                        @if(!$allSame)
                                            <span x-show="highlightDifferences" class="text-[9px] font-mono uppercase bg-amber-100 text-amber-800 font-bold px-1.5 py-0.2 rounded">
                                                Differs
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Values Desktop -->
                                    <div class="hidden lg:contents">
                                        @foreach($values as $vIndex => $val)
                                            <div class="{{ $colSpan }} text-ink-700 leading-relaxed font-sans">
                                                {{ $val }}
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Value Mobile (Active Index Only) -->
                                    <div class="lg:hidden text-ink-800 leading-relaxed pt-1 border-t border-border-subtle mt-1">
                                        @foreach($values as $vIndex => $val)
                                            <div x-show="mobileActiveIndex === {{ $vIndex }}">
                                                <span class="text-[10px] uppercase font-mono text-ink-400 block font-bold">
                                                    {{ $universities[$vIndex]['acronym'] }}
                                                </span>
                                                <span class="text-xs text-ink-900 font-medium">{{ $val }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Footer Action Strip -->
            <div class="mt-12 p-6 rounded-2xl bg-surface border border-border-default shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <h3 class="font-heading font-bold text-sm text-ink-950">Need a definitive recommendation?</h3>
                    <p class="text-xs text-ink-500 font-sans">Ask our AI counselor to weigh your specific board marks against these universities.</p>
                </div>

                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('counselor.chat') }}?prompt=Compare+{{ urlencode(implode(' and ', array_column($universities, 'name'))) }}+for+admissions"
                        class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-xs transition-smooth inline-flex items-center gap-1.5"
                    >
                        <x-icon name="sparkles" class="w-3.5 h-3.5" />
                        <span>Consult AI Counselor on this Comparison</span>
                    </a>
                </div>
            </div>
        </main>
    </div>
</x-layouts.public>
