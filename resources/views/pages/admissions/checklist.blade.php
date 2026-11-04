<x-layouts.public 
    title="National University Admission Document Checklist — UniGuide Pakistan"
    description="Interactive document preparation and attestation checklist for Pakistani university admissions, BISE board verifications, IBCC equivalence, and financial aid dossiers."
>
    <div x-data="{ 
        checkedItems: {},
        totalItems: {{ collect($checklist)->sum(fn($cat) => count($cat['items'])) }},
        toggleItem(id) {
            this.checkedItems[id] = !this.checkedItems[id];
        },
        get completedCount() {
            return Object.values(this.checkedItems).filter(Boolean).length;
        },
        get progressPercentage() {
            return Math.round((this.completedCount / this.totalItems) * 100);
        },
        resetAll() {
            this.checkedItems = {};
            $store.toasts.add('Document checklist reset.', 'info');
        },
        printChecklist() {
            window.print();
        }
    }" class="min-h-screen bg-canvas pb-24">
        
        <!-- Header Strip -->
        <header class="bg-surface border-b border-border-default/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Admissions', 'url' => route('admissions.index')],
                        ['label' => 'Document Checklist', 'url' => route('admissions.checklist')]
                    ]" />
                </div>

                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-brand-50 border border-brand-200/60 text-brand-700 text-xs font-semibold font-sans mb-3">
                            <x-icon name="clipboard-check" class="w-3.5 h-3.5 text-brand-600" />
                            <span>Official Pakistani Higher Education Verification Protocol</span>
                        </div>
                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                            Admission Document & Attestation Checklist
                        </h1>
                        <p class="text-sm sm:text-base text-ink-600 font-sans max-w-2xl mt-1.5 leading-relaxed">
                            Complete roadmap of certified academic transcripts, NADRA identification, IBCC equivalence, and attestation protocols required across all HEC chartered universities.
                        </p>
                    </div>

                    <!-- Progress Gauge Box -->
                    <div class="bg-surface-subtle p-5 rounded-2xl border border-border-default shrink-0 min-w-64 space-y-2">
                        <div class="flex items-center justify-between text-xs font-sans">
                            <span class="font-semibold text-ink-700">Dossier Readiness</span>
                            <span class="font-mono font-bold text-brand-700 text-sm" x-text="progressPercentage + '%'"></span>
                        </div>
                        <div class="w-full bg-border-default rounded-full h-2 overflow-hidden">
                            <div 
                                class="bg-brand-600 h-2 rounded-full transition-all duration-300"
                                :style="'width: ' + progressPercentage + '%'"
                            ></div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-ink-500 font-sans pt-1">
                            <span><strong class="text-ink-900" x-text="completedCount"></strong> of <strong class="text-ink-900" x-text="totalItems"></strong> items prepared</span>
                            <button type="button" @click="resetAll()" class="text-brand-600 hover:underline cursor-pointer">Reset</button>
                        </div>
                    </div>
                </div>

                <!-- Action Toolbar: Print & Direct Links -->
                <div class="flex items-center justify-between pt-6 mt-6 border-t border-border-subtle text-xs font-sans">
                    <div class="flex items-center gap-2 text-ink-500">
                        <x-icon name="info" class="w-4 h-4 text-brand-600 shrink-0" />
                        <span>Check off items as you obtain and attest copies to monitor your application readiness.</span>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <button 
                            type="button" 
                            @click="printChecklist()"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border-default bg-surface hover:bg-surface-subtle text-ink-700 font-semibold cursor-pointer shadow-2xs"
                        >
                            <x-icon name="printer" class="w-3.5 h-3.5 text-ink-500" />
                            <span>Print Checklist</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-10">
            
            <!-- Document Categories -->
            @foreach($checklist as $cat)
                <section class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 border border-brand-200/60 flex items-center justify-center shrink-0">
                            <x-icon :name="$cat['icon']" class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="font-heading font-bold text-lg text-ink-950">
                                {{ $cat['category'] }}
                            </h2>
                            <p class="text-xs text-ink-500 font-sans mt-0.5">
                                {{ $cat['description'] }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($cat['items'] as $item)
                            <div 
                                @click="toggleItem('{{ $item['id'] }}')"
                                :class="checkedItems['{{ $item['id'] }}'] ? 'bg-brand-50/40 border-brand-300 ring-1 ring-brand-500/20' : 'bg-surface border-border-default hover:border-brand-200'"
                                class="rounded-2xl border p-5 transition-smooth cursor-pointer flex flex-col justify-between space-y-3"
                            >
                                <div class="flex items-start gap-3.5">
                                    <div class="pt-0.5">
                                        <div 
                                            class="w-5 h-5 rounded-md border flex items-center justify-center transition-colors"
                                            :class="checkedItems['{{ $item['id'] }}'] ? 'bg-brand-600 border-brand-600 text-white' : 'border-border-strong bg-surface'"
                                        >
                                            <x-icon name="check" class="w-3.5 h-3.5" x-show="checkedItems['{{ $item['id'] }}']" />
                                        </div>
                                    </div>

                                    <div class="space-y-1">
                                        <h3 
                                            class="font-heading font-bold text-sm text-ink-950 transition-colors"
                                            :class="checkedItems['{{ $item['id'] }}'] ? 'line-through text-ink-500' : ''"
                                        >
                                            {{ $item['title'] }}
                                        </h3>
                                        
                                        <p class="text-xs font-sans text-ink-500">
                                            <strong>Issuing Authority:</strong> {{ $item['issuer'] }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-2 pt-2 border-t border-border-subtle text-xs font-sans">
                                    <div class="flex items-center justify-between text-ink-600">
                                        <span class="text-ink-400">Attestation Rule:</span>
                                        <span class="font-medium text-ink-800 text-right">{{ $item['attestation'] }}</span>
                                    </div>
                                    <div class="flex items-center justify-between text-ink-600">
                                        <span class="text-ink-400">Recommended Copies:</span>
                                        <span class="font-semibold text-brand-700">{{ $item['required_copies'] }}</span>
                                    </div>
                                    @if(!empty($item['tips']))
                                        <p class="text-[11px] text-ink-500 bg-surface-subtle p-2 rounded-lg leading-relaxed mt-1">
                                            💡 {{ $item['tips'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach

            <!-- Attestation Authority Guidelines Notice -->
            <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                <h3 class="font-heading font-bold text-base text-ink-950 flex items-center gap-2">
                    <x-icon name="shield-check" class="w-5 h-5 text-brand-600" />
                    <span>Official Pakistani Attestation Authorities</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-sans text-ink-700">
                    <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle space-y-1.5">
                        <span class="font-bold text-ink-950 block">BISE Board Verification</span>
                        <p class="text-ink-500 leading-relaxed">
                            Matriculation and Intermediate certificates must first be verified by the issuing secondary education board before federal university submission.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle space-y-1.5">
                        <span class="font-bold text-ink-950 block">IBCC Attestation & Equivalence</span>
                        <p class="text-ink-500 leading-relaxed">
                            Mandatory for Cambridge O/A-Levels and American High School diplomas. Equivalence converts letter grades into Pakistani BISE percentage scales.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle space-y-1.5">
                        <span class="font-bold text-ink-950 block">Grade 17+ Gazetted Officer</span>
                        <p class="text-ink-500 leading-relaxed">
                            Photocopies of CNIC, Domicile, and photographs must carry the signed name, designation, and official stamp of a Grade 17 or higher government officer.
                        </p>
                    </div>
                </div>
            </div>

        </main>

    </div>
</x-layouts.public>

