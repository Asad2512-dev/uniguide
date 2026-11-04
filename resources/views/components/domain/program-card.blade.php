@props([
    'title' => 'BS Computer Science (BSCS)',
    'university' => 'FAST-NUCES Islamabad',
    'degreeLevel' => 'Undergraduate (BS)',
    'duration' => '4 Years • 8 Semesters',
    'feePerSemester' => 'PKR 145,000',
    'cutoffMerit' => '76.8% Aggregate',
    'accreditation' => 'NCEAC (Computing Council) • Category W',
    'status' => 'open',
    'href' => '#',
])

<div {{ $attributes->merge(['class' => 'bg-surface rounded-2xl border border-border-default hover:border-border-strong hover:shadow-card p-5 transition-smooth flex flex-col justify-between']) }}>
    <div>
        <div class="flex items-start justify-between gap-3 mb-2.5">
            <div>
                <span class="inline-flex items-center text-[11px] font-semibold tracking-wider uppercase text-brand-700 bg-brand-50 px-2 py-0.5 rounded border border-brand-200/60 mb-1.5 font-sans">
                    {{ $degreeLevel }}
                </span>
                <h4 class="font-heading font-bold text-base text-ink-950 hover:text-brand-600 transition-colors">
                    <a href="{{ $href }}">{{ $title }}</a>
                </h4>
                <p class="text-xs text-ink-500 font-sans mt-0.5">
                    {{ $university }}
                </p>
            </div>
            <x-ui.badge variant="success" size="sm" :dot="true">
                Fall 2026
            </x-ui.badge>
        </div>

        <div class="space-y-2 py-3 my-2 border-y border-border-subtle text-xs font-sans">
            <div class="flex items-center justify-between text-ink-600">
                <span class="flex items-center gap-1.5 text-ink-500">
                    <x-icon name="clock" class="w-3.5 h-3.5 text-ink-400" />
                    Program Duration
                </span>
                <span class="font-medium text-ink-800">{{ $duration }}</span>
            </div>
            <div class="flex items-center justify-between text-ink-600">
                <span class="flex items-center gap-1.5 text-ink-500">
                    <x-icon name="receipt" class="w-3.5 h-3.5 text-ink-400" />
                    Fee per Semester
                </span>
                <span class="font-semibold text-ink-900">{{ $feePerSemester }}</span>
            </div>
            <div class="flex items-center justify-between text-ink-600">
                <span class="flex items-center gap-1.5 text-ink-500">
                    <x-icon name="calculator" class="w-3.5 h-3.5 text-ink-400" />
                    Estimated Closing Merit
                </span>
                <span class="font-bold text-brand-700">{{ $cutoffMerit }}</span>
            </div>
        </div>

        <div class="flex items-center gap-1.5 text-[11px] text-ink-500 font-sans mt-2">
            <x-icon name="shield-check" class="w-3.5 h-3.5 text-success-600 shrink-0" />
            <span class="truncate">{{ $accreditation }}</span>
        </div>
    </div>

    <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between gap-2">
        <button type="button" class="text-xs text-ink-500 hover:text-brand-600 font-medium cursor-pointer">
            Calculate My Merit
        </button>
        <x-ui.button variant="outline" size="xs" :href="$href" iconRight="arrow-up-right">
            Syllabus & Details
        </x-ui.button>
    </div>
</div>
