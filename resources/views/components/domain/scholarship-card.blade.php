@props([
    'title' => 'HEC Need-Based Undergraduate Scholarship',
    'provider' => 'Higher Education Commission of Pakistan',
    'coverage' => '100% Tuition Fee + PKR 6,000/mo Living Stipend',
    'eligibility' => 'Family income under PKR 45,000/mo • Enrolled in participating public university',
    'deadline' => '30 August 2026',
    'category' => 'Need & Merit Based',
    'href' => '#',
])

<div {{ $attributes->merge(['class' => 'bg-surface rounded-2xl border border-border-default hover:border-brand-200 hover:shadow-card p-5 transition-smooth flex flex-col justify-between']) }}>
    <div>
        <div class="flex items-start justify-between gap-3 mb-2">
            <div>
                <span class="inline-flex items-center text-[10px] font-semibold tracking-wider uppercase text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200 mb-1.5 font-sans">
                    {{ $category }}
                </span>
                <h4 class="font-heading font-bold text-base text-ink-950 hover:text-brand-600 transition-colors">
                    <a href="{{ $href }}">{{ $title }}</a>
                </h4>
                <p class="text-xs text-ink-500 font-sans mt-0.5">
                    {{ $provider }}
                </p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200/60">
                <x-icon name="award" class="w-4 h-4" />
            </div>
        </div>

        <div class="my-3 p-3 bg-surface-subtle rounded-xl border border-border-subtle text-xs font-sans space-y-1.5">
            <div class="flex items-start gap-1.5 text-ink-900">
                <x-icon name="check" class="w-3.5 h-3.5 text-success-600 shrink-0 mt-0.5" />
                <span class="font-semibold">{{ $coverage }}</span>
            </div>
            <p class="text-[11px] text-ink-500 pl-5">
                {{ $eligibility }}
            </p>
        </div>
    </div>

    <div class="mt-2 pt-3 border-t border-border-subtle flex items-center justify-between gap-2">
        <span class="text-xs text-ink-500 font-sans">
            Deadline: <span class="font-semibold text-ink-800">{{ $deadline }}</span>
        </span>
        <x-ui.button variant="soft" size="xs" :href="$href" iconRight="arrow-right">
            Apply Details
        </x-ui.button>
    </div>
</div>
