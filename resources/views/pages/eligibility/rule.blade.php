<x-layouts.public 
    title="{{ $selectedRule['title'] ?? 'Statutory Rule Detail' }} — UniGuide Pakistan"
    description="Inspect the official statutory eligibility rules, Pakistan Engineering Council regulations, and HEC directives."
>
    <div class="min-h-screen bg-canvas pb-24">
        
        <!-- Header Strip -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Eligibility Checker', 'url' => route('eligibility.index')],
                        ['label' => 'Statutory Rule Library', 'url' => route('eligibility.rule')]
                    ]" />
                </div>

                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-semibold mb-2">
                        <x-icon name="shield-check" class="w-3.5 h-3.5 text-emerald-600" />
                        <span>Statutory Rules Architecture</span>
                    </div>
                    <h1 class="font-heading font-bold text-2xl sm:text-3xl lg:text-4xl text-ink-950 tracking-tight">
                        Statutory Rules & Gazette Clauses
                    </h1>
                    <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                        Our eligibility determinations are computed through audited legal regulations, not invented by generative models.
                    </p>
                </div>
            </div>
        </section>

        <!-- Master-Detail Layout -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left 5 Cols: Rules Directory -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-border-subtle">
                        <h2 class="font-heading font-bold text-sm text-ink-950">Statutory Rules Library</h2>
                        <span class="text-xs font-mono text-ink-400">{{ count($rules) }} Audited Regulations</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($rules as $ruleKey => $r)
                            @php $isSelected = ($selectedRule['id'] === $r['id']); @endphp
                            <a 
                                href="{{ route('eligibility.rule', $r['id']) }}"
                                class="block p-4 rounded-2xl border transition-all {{ $isSelected ? 'border-emerald-600 bg-emerald-50/20 shadow-card ring-2 ring-emerald-500/10' : 'border-border-default bg-surface hover:border-emerald-300' }}"
                            >
                                <div class="flex items-center justify-between text-[10px] font-mono mb-1">
                                    <span class="font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded">
                                        {{ $r['category'] }}
                                    </span>
                                    <span class="text-ink-400">{{ $r['verified_date'] }}</span>
                                </div>
                                <h3 class="font-heading font-bold text-sm text-ink-950 line-clamp-1">
                                    {{ $r['title'] }}
                                </h3>
                                <p class="text-[11px] text-ink-500 font-sans line-clamp-2 mt-1">
                                    {{ $r['summary'] }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Right 7 Cols: Full Rule Inspector -->
                <div class="lg:col-span-7">
                    @if($selectedRule)
                        <div class="p-6 sm:p-8 rounded-2xl bg-surface border border-border-default shadow-card space-y-6">
                            <div class="pb-6 border-b border-border-default space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                                        {{ $selectedRule['category'] }}
                                    </span>
                                    <span class="text-xs font-mono text-ink-400">{{ $selectedRule['verified_date'] }}</span>
                                </div>

                                <h2 class="font-heading font-bold text-xl sm:text-2xl text-ink-950">
                                    {{ $selectedRule['title'] }}
                                </h2>

                                <div class="text-xs font-sans text-ink-600 pt-1">
                                    Regulatory Authority: <strong class="text-ink-900 font-semibold">{{ $selectedRule['authority'] }}</strong>
                                </div>
                            </div>

                            <!-- Statutory Reference Box -->
                            <div class="p-4 rounded-xl bg-surface-subtle border border-border-default space-y-1 font-mono text-xs">
                                <span class="text-[10px] text-ink-400 uppercase block font-bold">Statutory Gazette Reference</span>
                                <div class="font-bold text-ink-900">{{ $selectedRule['statutory_reference'] }}</div>
                            </div>

                            <!-- Scope & Legal Summary -->
                            <div class="space-y-2 text-xs sm:text-sm font-sans">
                                <h3 class="font-heading font-bold text-sm text-ink-900 uppercase tracking-wider">Scope of Application</h3>
                                <p class="text-ink-700 leading-relaxed">{{ $selectedRule['scope'] }}</p>
                            </div>

                            <!-- Summary -->
                            <div class="space-y-2 text-xs sm:text-sm font-sans">
                                <h3 class="font-heading font-bold text-sm text-ink-900 uppercase tracking-wider">Statutory Mandate</h3>
                                <p class="text-ink-700 leading-relaxed">{{ $selectedRule['summary'] }}</p>
                            </div>

                            <!-- Formula & Calculation Note -->
                            <div class="p-4 rounded-xl bg-emerald-50/30 border border-emerald-200 space-y-1 text-xs font-sans">
                                <span class="font-bold text-emerald-900 block flex items-center gap-1.5">
                                    <x-icon name="calculator" class="w-4 h-4 text-emerald-700" />
                                    <span>Rules Engine Mathematical Enforcement</span>
                                </span>
                                <p class="text-emerald-800 leading-relaxed">{{ $selectedRule['formula_note'] }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="pt-6 border-t border-border-default flex items-center justify-between">
                                <a 
                                    href="{{ route('eligibility.index') }}"
                                    class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-smooth inline-flex items-center gap-1.5"
                                >
                                    <span>Test Your Eligibility Under this Rule</span>
                                    <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</x-layouts.public>

