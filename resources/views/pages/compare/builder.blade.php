<x-layouts.public 
    title="University Comparison Builder — UniGuide Pakistan"
    description="Select and compare up to four Pakistani universities across verified fees, merit formulas, hostel charges, and statutory accreditations."
>
    <div 
        x-data="{
            selected: ['nust', 'fast-nuces'],
            searchTerm: '',
            available: @js($availableUniversities),

            addUniversity(slug) {
                if (this.selected.length >= 4) {
                    alert('You can compare a maximum of 4 universities simultaneously.');
                    return;
                }
                if (!this.selected.includes(slug)) {
                    this.selected.push(slug);
                }
            },

            removeUniversity(slug) {
                if (this.selected.length <= 1) {
                    alert('Please keep at least 1 university to compare.');
                    return;
                }
                this.selected = this.selected.filter(s => s !== slug);
            },

            isSelected(slug) {
                return this.selected.includes(slug);
            },

            getLaunchUrl() {
                return '{{ route('compare.results') }}?unis=' + this.selected.join(',');
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
                        ['label' => 'Compare Universities', 'url' => route('compare.index')]
                    ]" />
                </div>

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-3">
                            <x-icon name="git-compare" class="w-3.5 h-3.5 text-brand-600" />
                            <span>Institutional Benchmark Workspace</span>
                        </div>
                        <h1 class="font-heading font-bold text-2xl sm:text-3xl lg:text-4xl text-ink-950 tracking-tight">
                            University Comparison Builder
                        </h1>
                        <p class="text-xs sm:text-sm text-ink-600 font-sans mt-2">
                            Select up to four Pakistani universities to evaluate side-by-side across verified fee schedules, entry test formulas, and HEC statutory credentials.
                        </p>
                    </div>

                    <!-- Comparison Action Sticky Card -->
                    <div class="shrink-0 p-4 rounded-2xl bg-surface-subtle border border-border-default flex items-center gap-4 shadow-2xs">
                        <div>
                            <span class="text-[10px] uppercase font-mono text-ink-400 block font-bold">Selected Options</span>
                            <span class="font-mono font-bold text-sm text-ink-900">
                                <span x-text="selected.length"></span> of 4 Institutions
                            </span>
                        </div>

                        <a 
                            :href="getLaunchUrl()"
                            class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold font-sans shadow-card transition-smooth inline-flex items-center gap-2"
                        >
                            <span>Launch Comparison</span>
                            <x-icon name="arrow-right" class="w-4 h-4" />
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-12">
            
            <!-- Curated Benchmark Presets -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="font-heading font-bold text-lg text-ink-950">Curated Benchmark Presets</h2>
                        <p class="text-xs text-ink-500 font-sans">Pre-configured side-by-side matrices comparing premier regional and disciplinary peers.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($presets as $pre)
                        <a 
                            href="{{ route('compare.results', ['unis' => implode(',', $pre['slugs'])]) }}"
                            class="p-5 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between group"
                        >
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition-colors">
                                        <x-icon :name="$pre['icon']" class="w-4.5 h-4.5" />
                                    </div>
                                    <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-full {{ match($pre['badge_color'] ?? 'brand') {
                                        'success' => 'bg-success-50 text-success-700 border border-success-200/60',
                                        'indigo' => 'bg-indigo-50 text-indigo-700 border border-indigo-200/60',
                                        'warning' => 'bg-warning-50 text-warning-700 border border-warning-200/60',
                                        default => 'bg-brand-50 text-brand-700 border border-brand-200/60',
                                    } }}">
                                        {{ $pre['badge'] }}
                                    </span>
                                </div>
                                <h3 class="font-heading font-bold text-sm text-ink-950 group-hover:text-brand-600 transition-colors">
                                    {{ $pre['title'] }}
                                </h3>
                                <p class="text-xs text-ink-500 font-sans mt-1 line-clamp-2">
                                    {{ $pre['description'] }}
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs font-semibold text-brand-600">
                                <span>{{ count($pre['slugs']) }} Universities</span>
                                <span class="group-hover:translate-x-1 transition-transform">Compare →</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Custom University Selector Grid -->
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="font-heading font-bold text-lg text-ink-950">Build Custom Comparison</h2>
                        <p class="text-xs text-ink-500 font-sans">Search and toggle institutions from our verified national directory.</p>
                    </div>

                    <div class="relative max-w-xs w-full">
                        <x-icon name="search" class="w-4 h-4 text-ink-400 absolute left-3 top-1/2 -translate-y-1/2" />
                        <input 
                            type="text" 
                            x-model="searchTerm"
                            placeholder="Filter universities by name or city..." 
                            class="w-full pl-9 pr-4 py-2 rounded-xl border border-border-default bg-surface text-ink-900 placeholder:text-ink-400 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="uni in available" :key="uni.slug">
                        <div 
                            x-show="!searchTerm || (uni.name + ' ' + uni.acronym + ' ' + uni.city).toLowerCase().includes(searchTerm.toLowerCase())"
                            class="p-4 rounded-xl border transition-all flex flex-col justify-between"
                            :class="isSelected(uni.slug) ? 'border-brand-500 bg-brand-50/20 ring-2 ring-brand-500/10 shadow-2xs' : 'border-border-default bg-surface hover:border-brand-200'"
                        >
                            <div>
                                <div class="flex items-center justify-between pb-2 mb-2 border-b border-border-subtle">
                                    <span class="font-heading font-bold text-xs text-ink-950" x-text="uni.acronym"></span>
                                    <span class="text-[10px] font-mono text-ink-400" x-text="uni.ranking"></span>
                                </div>
                                <h3 class="font-heading font-bold text-sm text-ink-900 truncate" x-text="uni.name"></h3>
                                <p class="text-[11px] text-ink-500 font-sans mt-0.5">
                                    <span x-text="uni.city"></span> • Sector: <span x-text="uni.sector"></span>
                                </p>
                            </div>

                            <div class="mt-4 pt-2.5 border-t border-border-subtle flex items-center justify-between text-xs">
                                <template x-if="isSelected(uni.slug)">
                                    <button 
                                        type="button" 
                                        @click="removeUniversity(uni.slug)"
                                        class="px-2.5 py-1 rounded-lg bg-surface border border-border-default text-error-700 hover:bg-error-50 font-semibold cursor-pointer flex items-center gap-1"
                                    >
                                        <x-icon name="x" class="w-3 h-3" />
                                        <span>Remove</span>
                                    </button>
                                </template>

                                <template x-if="!isSelected(uni.slug)">
                                    <button 
                                        type="button" 
                                        @click="addUniversity(uni.slug)"
                                        class="px-3 py-1 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-semibold cursor-pointer flex items-center gap-1"
                                    >
                                        <x-icon name="plus" class="w-3 h-3" />
                                        <span>Add to Compare</span>
                                    </button>
                                </template>

                                <a :href="'/universities/' + uni.slug" class="text-ink-500 hover:text-ink-900 font-medium">
                                    Profile →
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </main>
    </div>
</x-layouts.public>

