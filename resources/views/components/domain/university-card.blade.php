@props([
    'name' => 'National University of Sciences & Technology',
    'acronym' => 'NUST',
    'location' => 'Islamabad, Federal Capital',
    'sector' => 'Public Sector',
    'hecRank' => '#1 General Category',
    'feeRange' => 'PKR 125,000 – 165,000',
    'programsCount' => '54 Programs',
    'acceptanceRate' => '~12% Highly Selective',
    'status' => 'open', // open, closing_soon, closed
    'deadline' => '15 July 2026',
    'featured' => false,
    'href' => '#',
])

<div {{ $attributes->merge([
    'class' => 'group relative bg-surface rounded-2xl border transition-all duration-200 overflow-hidden flex flex-col justify-between ' . 
    ($featured 
        ? 'border-brand-300 shadow-elevated ring-1 ring-brand-500/20' 
        : 'border-border-default hover:border-border-strong hover:shadow-card hover:-translate-y-0.5'
    )
]) }}>
    @if($featured)
        <div class="bg-gradient-to-r from-brand-600 to-brand-800 px-4 py-1 flex items-center justify-between text-white text-[11px] font-semibold tracking-wide">
            <span class="flex items-center gap-1.5">
                <x-icon name="sparkles" class="w-3.5 h-3.5 text-brand-200" />
                Featured National Institution
            </span>
            <span class="uppercase tracking-widest text-[9px] bg-white/20 px-1.5 py-0.2 rounded font-mono">
                HEC Accredited
            </span>
        </div>
    @endif

    <div class="p-6">
        <!-- Top Row: Monogram, Ranking, Bookmark -->
        <div class="flex items-start justify-between gap-3 mb-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-surface-subtle border border-border-default flex items-center justify-center font-heading font-extrabold text-base text-brand-700 shadow-2xs shrink-0 group-hover:border-brand-300 transition-colors">
                    {{ $acronym }}
                </div>
                <div>
                    <h3 class="font-heading font-bold text-base md:text-lg text-ink-950 group-hover:text-brand-600 transition-colors leading-snug">
                        <a href="{{ $href }}" class="focus:outline-none">
                            {{ $name }}
                        </a>
                    </h3>
                    <div class="flex items-center gap-2 text-xs text-ink-500 font-sans mt-0.5">
                        <span class="flex items-center gap-1">
                            <x-icon name="map-pin" class="w-3.5 h-3.5 text-ink-400" />
                            {{ $location }}
                        </span>
                        <span>•</span>
                        <span class="font-medium text-ink-700">{{ $sector }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-1.5 shrink-0">
                <button 
                    type="button" 
                    class="w-8 h-8 rounded-lg border border-border-default bg-surface hover:bg-surface-subtle text-ink-400 hover:text-brand-600 flex items-center justify-center transition-smooth"
                    title="Bookmark University"
                    aria-label="Bookmark {{ $name }}"
                >
                    <x-icon name="bookmark" class="w-4 h-4" />
                </button>
            </div>
        </div>

        <!-- Badges Row -->
        <div class="flex flex-wrap items-center gap-1.5 mb-5">
            <x-ui.badge variant="brand" size="sm" icon="award">
                HEC {{ $hecRank }}
            </x-ui.badge>
            <x-domain.verification-badge type="hec_recognized" detail="Category W" size="sm" />
        </div>

        <!-- Key Metrics Grid -->
        <div class="grid grid-cols-2 gap-3 p-3.5 rounded-xl bg-surface-subtle border border-border-subtle mb-5">
            <div>
                <span class="block text-[11px] font-sans text-ink-500 font-medium">Estimated Tuition</span>
                <span class="font-sans font-semibold text-xs text-ink-900 mt-0.5 block">
                    {{ $feeRange }}
                    <span class="text-[10px] text-ink-400 font-normal">/sem</span>
                </span>
            </div>
            <div>
                <span class="block text-[11px] font-sans text-ink-500 font-medium">Admissions Selectivity</span>
                <span class="font-sans font-semibold text-xs text-ink-900 mt-0.5 block">
                    {{ $acceptanceRate }}
                </span>
            </div>
        </div>

        <!-- Admission Status Banner -->
        <div class="pt-1">
            <x-domain.admission-status :status="$status" :deadline="$deadline" size="sm" />
        </div>
    </div>

    <!-- Footer Actions -->
    <div class="px-6 py-3.5 bg-surface-subtle/50 border-t border-border-subtle flex items-center justify-between gap-3">
        <span class="text-xs font-sans text-ink-500">
            {{ $programsCount }}
        </span>
        <div class="flex items-center gap-2">
            <button 
                type="button" 
                class="text-xs font-sans font-medium text-ink-600 hover:text-ink-900 px-2.5 py-1 rounded-md hover:bg-surface transition-smooth cursor-pointer border border-transparent hover:border-border-default"
            >
                + Compare
            </button>
            <x-ui.button variant="primary" size="sm" :href="$href" iconRight="arrow-right">
                Explore Programs
            </x-ui.button>
        </div>
    </div>
</div>
