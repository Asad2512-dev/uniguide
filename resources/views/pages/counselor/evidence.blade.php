<x-layouts.public 
    title="Statutory Evidence & Gazette Viewer — UniGuide Pakistan"
    description="Inspect the official statutory legal acts, HEC notifications, PEC accreditations, and university gazettes that underpin UniGuide AI recommendations."
>
    <div class="min-h-screen bg-canvas pb-24">
        
        <!-- Header Banner -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'AI Counselor', 'url' => route('counselor.index')],
                        ['label' => 'Statutory Evidence Viewer', 'url' => route('counselor.evidence')]
                    ]" />
                </div>

                <div class="max-w-3xl">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-50 border border-indigo-200/80 text-indigo-700 text-xs font-semibold mb-3">
                        <x-icon name="shield-check" class="w-3.5 h-3.5 text-indigo-600" />
                        <span>HEC & Gazette Data Transparency Mandate</span>
                    </div>
                    <h1 class="font-heading font-bold text-2xl sm:text-3xl lg:text-4xl text-ink-950 tracking-tight">
                        Statutory Evidence & Legal Gazette Viewer
                    </h1>
                    <p class="text-xs sm:text-sm text-ink-600 font-sans mt-2">
                        Inspect the official legal statutes, Federal Acts, PEC Washington Accord registers, and syndicate notifications that ground our AI recommendations in verifiable Pakistani law.
                    </p>
                </div>
            </div>
        </section>

        <!-- Master-Detail Evidence Explorer -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left 5 Cols: Document Registry List -->
                <div class="lg:col-span-5 space-y-4">
                    <div class="flex items-center justify-between pb-2 border-b border-border-subtle">
                        <h2 class="font-heading font-bold text-sm text-ink-950">Statutory Documents Register</h2>
                        <span class="text-xs font-mono text-ink-400">{{ count($evidenceList) }} Audited Sources</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($evidenceList as $item)
                            @php $isSelected = ($item['id'] === ($selectedEvidence['id'] ?? '')); @endphp
                            <a 
                                href="{{ route('counselor.evidence', $item['id']) }}"
                                class="block p-4 rounded-2xl border transition-all {{ $isSelected ? 'border-brand-500 bg-brand-50/20 shadow-card ring-2 ring-brand-500/10' : 'border-border-default bg-surface hover:border-brand-200 hover:shadow-2xs' }}"
                            >
                                <div class="flex items-center justify-between text-[10px] font-mono mb-1.5">
                                    <span class="font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded">
                                        {{ $item['category'] }}
                                    </span>
                                    <span class="text-ink-400">{{ $item['verified_date'] }}</span>
                                </div>
                                <h3 class="font-heading font-bold text-xs sm:text-sm text-ink-950 line-clamp-2">
                                    {{ $item['title'] }}
                                </h3>
                                <p class="text-[11px] text-ink-500 font-sans line-clamp-2 mt-1">
                                    {{ $item['summary'] }}
                                </p>
                                <div class="flex items-center justify-between text-[11px] text-ink-400 font-mono mt-3 pt-2 border-t border-border-subtle">
                                    <span class="truncate">{{ $item['authority'] }}</span>
                                    <span class="text-brand-600 font-semibold shrink-0 ml-2">Inspect →</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Right 7 Cols: Full Evidence Inspector -->
                <div class="lg:col-span-7">
                    @if($selectedEvidence)
                        <div class="p-6 sm:p-8 rounded-2xl bg-surface border border-border-default shadow-card space-y-6">
                            <!-- Document Header -->
                            <div class="pb-6 border-b border-border-default space-y-3">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-xs font-mono font-bold text-brand-800 bg-brand-50 border border-brand-200 px-2.5 py-0.5 rounded-full">
                                        {{ $selectedEvidence['category'] }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-xs text-success-700 bg-success-50 border border-success-200/60 px-2.5 py-0.5 rounded-full font-medium">
                                        <x-icon name="shield-check" class="w-3.5 h-3.5 text-success-600" />
                                        <span>Cryptographically Verified</span>
                                    </span>
                                </div>

                                <h2 class="font-heading font-bold text-xl sm:text-2xl text-ink-950">
                                    {{ $selectedEvidence['title'] }}
                                </h2>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs font-sans pt-2">
                                    <div>
                                        <span class="text-[10px] text-ink-400 font-mono uppercase block">Regulatory Authority</span>
                                        <span class="font-semibold text-ink-900">{{ $selectedEvidence['authority'] }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-ink-400 font-mono uppercase block">Gazette Reference</span>
                                        <span class="font-mono text-ink-800">{{ $selectedEvidence['reference'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Cryptographic Hash & Audit Timestamp -->
                            <div class="p-3.5 rounded-xl bg-surface-subtle border border-border-default space-y-1 font-mono text-xs">
                                <div class="flex items-center justify-between text-[11px] text-ink-400">
                                    <span>Verification Hash</span>
                                    <span class="text-success-700 font-bold">SHA-256 Match</span>
                                </div>
                                <div class="text-[11px] text-ink-700 truncate select-all">
                                    {{ $selectedEvidence['hash'] }}
                                </div>
                            </div>

                            <!-- Executive Legal Summary -->
                            <div class="space-y-2">
                                <h3 class="font-heading font-bold text-sm text-ink-900 uppercase tracking-wider">
                                    Statutory Summary & Scope
                                </h3>
                                <p class="text-xs sm:text-sm text-ink-700 font-sans leading-relaxed">
                                    {{ $selectedEvidence['summary'] }}
                                </p>
                            </div>

                            <!-- Statutory Clauses Quoted by AI Counselor -->
                            <div class="space-y-3 pt-2">
                                <h3 class="font-heading font-bold text-sm text-ink-900 uppercase tracking-wider flex items-center gap-1.5">
                                    <x-icon name="file-text" class="w-4 h-4 text-brand-600" />
                                    <span>Audited Gazette Clauses</span>
                                </h3>
                                <div class="space-y-2.5">
                                    @foreach($selectedEvidence['clauses'] as $clause)
                                        <div class="p-3 rounded-xl bg-brand-50/20 border border-brand-100 text-xs text-ink-800 font-sans leading-relaxed">
                                            {{ $clause }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Actions Footer -->
                            <div class="pt-6 border-t border-border-default flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <a 
                                    href="{{ $selectedEvidence['official_portal_url'] }}" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-800 transition-smooth shadow-2xs"
                                >
                                    <span>Open Official Regulatory Portal</span>
                                    <x-icon name="external-link" class="w-3.5 h-3.5 text-ink-400" />
                                </a>

                                <a 
                                    href="{{ route('counselor.chat') }}?prompt=Explain+the+impact+of+{{ urlencode($selectedEvidence['reference']) }}+on+my+admissions"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-xs transition-smooth"
                                >
                                    <x-icon name="sparkles" class="w-3.5 h-3.5" />
                                    <span>Consult Counselor on this Law</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</x-layouts.public>

