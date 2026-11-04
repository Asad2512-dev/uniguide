<x-layouts.public 
    :title="$scholarship['title'] . ' — UniGuide Pakistan'"
    :description="$scholarship['coverage'] . ' by ' . $scholarship['provider']"
>
    <div x-data="{ 
        saved: false,
        saveScholarship() {
            this.saved = !this.saved;
            if (this.saved) {
                $store.toasts.add('Saved ' + '{{ $scholarship['title'] }}' + ' to your financial aid shortlist.', 'success');
            } else {
                $store.toasts.add('Removed from shortlist.', 'info');
            }
        }
    }" class="min-h-screen bg-canvas pb-24">
        
        <!-- Header Banner -->
        <header class="bg-surface border-b border-border-default/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Scholarships', 'url' => route('scholarships.index')],
                        ['label' => $scholarship['title'], 'url' => route('scholarships.show', $scholarship['slug'])]
                    ]" />
                </div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 font-sans uppercase font-mono">
                                {{ $scholarship['type_label'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-sans text-success-700 font-semibold bg-success-50 px-2 py-0.5 rounded-full border border-success-200/60">
                                <x-icon name="shield-check" class="w-3.5 h-3.5" />
                                <span>Official Verified Grant</span>
                            </span>
                            <span class="text-xs font-mono text-ink-400">
                                Verified: {{ $scholarship['verified_at'] }}
                            </span>
                        </div>

                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                            {{ $scholarship['title'] }}
                        </h1>

                        <p class="text-xs sm:text-sm font-sans text-ink-600 mt-2 flex items-center gap-1.5">
                            <x-icon name="building-2" class="w-4 h-4 text-ink-400 shrink-0" />
                            <span>{{ $scholarship['provider'] }}</span>
                            <span>•</span>
                            <span>Applicable: <strong>{{ $scholarship['universities_scope'] }}</strong></span>
                        </p>
                    </div>

                    <!-- Right CTAs: Save, Apply -->
                    <div class="flex items-center flex-wrap gap-2.5 shrink-0">
                        <button 
                            type="button" 
                            @click="saveScholarship()"
                            :class="saved ? 'bg-emerald-50 border-emerald-300 text-emerald-800' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border text-xs font-semibold font-sans shadow-2xs transition-smooth cursor-pointer"
                        >
                            <x-icon name="bookmark" class="w-4 h-4" ::class="saved ? 'fill-emerald-600 text-emerald-600' : ''" />
                            <span x-text="saved ? 'Shortlisted' : 'Save Scholarship'"></span>
                        </button>

                        <a 
                            href="{{ $scholarship['official_url'] }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-ink-900 hover:bg-brand-600 text-white text-xs font-semibold font-sans shadow-xs transition-smooth"
                        >
                            <span>Official Portal</span>
                            <x-icon name="external-link" class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Grid -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left 2 Cols: Scholarship Specifications -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- High-Level Benefits Banner -->
                    <div class="p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200/80 shadow-2xs space-y-3">
                        <span class="text-xs font-bold font-mono uppercase tracking-wider text-emerald-800">
                            Award Scope & Value
                        </span>
                        <h2 class="font-heading font-extrabold text-xl sm:text-2xl text-ink-950">
                            {{ $scholarship['coverage'] }}
                        </h2>
                        <p class="text-xs sm:text-sm text-ink-600 font-sans leading-relaxed">
                            {{ $scholarship['beneficiaries'] }}. Covers the complete official duration of undergraduate or postgraduate degrees.
                        </p>
                    </div>

                    <!-- Financial Benefits Breakdown -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                        <h3 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="check-circle" class="w-5 h-5 text-emerald-600" />
                            <span>Entitlements & Coverage Benefits</span>
                        </h3>

                        <div class="space-y-3">
                            @foreach($scholarship['benefits'] as $ben)
                                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-surface-subtle border border-border-subtle text-xs sm:text-sm font-sans text-ink-800">
                                    <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                    <span>{{ $ben }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Eligibility Criteria Checklist -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                        <h3 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="user-check" class="w-5 h-5 text-brand-600" />
                            <span>Eligibility Criteria & Income Thresholds</span>
                        </h3>

                        <div class="space-y-3">
                            @foreach($scholarship['eligibility_criteria'] as $crit)
                                <div class="flex items-start gap-3 p-3.5 rounded-xl border border-border-default bg-surface text-xs sm:text-sm font-sans text-ink-800">
                                    <x-icon name="arrow-right-circle" class="w-4 h-4 text-brand-600 shrink-0 mt-0.5" />
                                    <p class="leading-relaxed">{{ $crit }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Selection & Award Process -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-5">
                        <h3 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="list-ordered" class="w-5 h-5 text-brand-600" />
                            <span>Award Selection & Scrutiny Process</span>
                        </h3>

                        <div class="space-y-3">
                            @foreach($scholarship['selection_process'] as $idx => $step)
                                <div class="flex items-start gap-3.5 p-3.5 rounded-xl border border-border-default bg-surface text-xs sm:text-sm font-sans text-ink-800">
                                    <span class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                        {{ $idx + 1 }}
                                    </span>
                                    <p class="leading-relaxed">{{ $step }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Required Application Documents -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                                <x-icon name="file-text" class="w-5 h-5 text-brand-600" />
                                <span>Required Supporting Documents</span>
                            </h3>
                            <a href="{{ route('admissions.checklist') }}" class="text-xs font-semibold text-brand-600 hover:underline">
                                Attestation Guidelines →
                            </a>
                        </div>

                        <ul class="space-y-2.5 text-xs sm:text-sm font-sans text-ink-700">
                            @foreach($scholarship['required_documents'] as $doc)
                                <li class="flex items-start gap-2.5 p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                    <x-icon name="file-check" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
                                    <span>{{ $doc }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <!-- Right 1 Col: Urgent Deadline Card & Official Channels -->
                <aside class="space-y-6">
                    
                    <!-- Deadline Card -->
                    <div class="bg-surface rounded-2xl border border-emerald-200 shadow-elevated p-6 space-y-4 ring-1 ring-emerald-500/10">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 font-sans">
                                Application Deadline
                            </span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                {{ $scholarship['status'] === 'open' ? 'Active Cycle' : 'Upcoming' }}
                            </span>
                        </div>

                        <div>
                            <span class="block text-2xl font-heading font-extrabold text-ink-950">
                                {{ $scholarship['deadline'] }}
                            </span>
                            <span class="text-xs text-ink-500 font-sans mt-0.5 block">
                                Incomplete files without utility bills are rejected.
                            </span>
                        </div>

                        <div class="space-y-2.5 border-t border-border-subtle pt-4 text-xs font-sans">
                            <div class="flex items-center justify-between text-ink-600">
                                <span>Provider:</span>
                                <span class="font-semibold text-ink-900 text-right">{{ $scholarship['provider'] }}</span>
                            </div>
                            <div class="flex items-center justify-between text-ink-600">
                                <span>Beneficiary Scope:</span>
                                <span class="font-semibold text-ink-900">{{ $scholarship['universities_scope'] }}</span>
                            </div>
                        </div>

                        <a 
                            href="{{ $scholarship['official_url'] }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="block w-full text-center py-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-sans text-xs font-semibold shadow-xs transition-smooth"
                        >
                            Open Official Application Portal ↗
                        </a>
                    </div>

                    <!-- Statutory Source Citation Box -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 shadow-2xs space-y-3">
                        <span class="text-xs font-semibold text-ink-400 uppercase tracking-wider font-sans">Verification Source</span>
                        <h4 class="font-heading font-bold text-sm text-ink-950">
                            {{ $scholarship['source_citation'] }}
                        </h4>
                        <p class="text-xs text-ink-500 font-sans leading-relaxed">
                            Verified through official government gazettes, HEC Financial Aid Development rules, and university charter foundations.
                        </p>
                        <div class="pt-2">
                            <x-domain.source-citation 
                                :source="$scholarship['source_citation']"
                                :url="$scholarship['official_url']"
                                :verifiedAt="$scholarship['verified_at']"
                            />
                        </div>
                    </div>

                    <!-- Back to Explorer Shortcut -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 shadow-2xs space-y-3">
                        <span class="text-xs font-semibold text-ink-400 uppercase tracking-wider font-sans">More Opportunities</span>
                        <p class="text-xs text-ink-600 font-sans">
                            Explore provincial endowments like PEEF and BEEF, as well as institutional study loans.
                        </p>
                        <x-ui.button variant="outline" size="sm" :href="route('scholarships.index')" class="w-full">
                            All Scholarships Directory
                        </x-ui.button>
                    </div>

                </aside>

            </div>
        </main>

    </div>
</x-layouts.public>

