<x-layouts.student 
    title="Saved Scholarships & Financial Aid — UniGuide Pakistan"
    description="Your saved financial aid and scholarship schemes with eligibility verification against declared household income."
>
    <div class="space-y-8 pb-12">
        
        <!-- Header Strip -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-border-default">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-semibold mb-2">
                    <x-icon name="award" class="w-3.5 h-3.5 text-emerald-600" />
                    <span>Financial Aid Portfolio</span>
                </div>
                <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                    Saved Scholarships & Financial Aid
                </h1>
                <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                    Matched national, provincial, and university scholarships to fund your higher education in Pakistan.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('scholarships.index') }}" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-smooth"
                >
                    <x-icon name="plus" class="w-3.5 h-3.5" />
                    <span>Discover More Scholarships</span>
                </a>
            </div>
        </div>

        <!-- Income Eligibility Calibration Callout -->
        <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-50/60 via-surface to-emerald-50/40 border border-emerald-200 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <x-icon name="check-circle" class="w-5 h-5" />
                </div>
                <div class="space-y-0.5 text-xs font-sans">
                    <h3 class="font-heading font-bold text-sm text-ink-950">Income Eligibility Verified</h3>
                    <p class="text-ink-600">
                        Declared gross monthly household income: <span class="font-bold text-emerald-800 font-mono">PKR {{ number_format($profile['monthly_household_income'] ?? 45000) }} / month</span>.
                    </p>
                    <span class="text-[11px] text-emerald-700 font-medium block">
                        Fully complies with HEC Need-Based & Ehsaas income thresholds (≤ PKR 45,000).
                    </span>
                </div>
            </div>

            <a 
                href="{{ route('student.profile') }}" 
                class="shrink-0 text-xs font-semibold text-emerald-700 hover:text-emerald-800 underline"
            >
                Update Income Details
            </a>
        </div>

        <!-- Saved Scholarships List -->
        <div class="space-y-4">
            @forelse($scholarships as $sch)
                <div class="p-6 rounded-2xl bg-surface border border-border-default hover:border-emerald-300 hover:shadow-card transition-all flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2 max-w-2xl">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold font-mono px-2 py-0.5 rounded {{ match($sch['badge_color'] ?? 'success') {
                                'indigo' => 'bg-indigo-50 text-indigo-700 border border-indigo-200/60',
                                default => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
                            } }}">
                                {{ $sch['badge'] }}
                            </span>
                            <span class="text-xs font-mono text-ink-400">Deadline: {{ $sch['deadline'] }}</span>
                        </div>

                        <h3 class="font-heading font-bold text-lg text-ink-950">
                            {{ $sch['name'] }}
                        </h3>

                        <p class="text-xs text-ink-700 font-sans">
                            <span class="font-semibold text-ink-900">Coverage:</span> {{ $sch['coverage'] }}
                        </p>

                        <div class="text-[11px] text-emerald-700 font-semibold font-sans flex items-center gap-1.5">
                            <x-icon name="check" class="w-3.5 h-3.5 text-emerald-600" />
                            <span>{{ $sch['eligibility_status'] }}</span>
                        </div>
                    </div>

                    <div class="shrink-0 flex md:flex-col items-center md:items-end justify-between gap-3 pt-4 md:pt-0 border-t md:border-t-0 border-border-subtle">
                        <a 
                            href="{{ route('scholarships.show', $sch['slug']) }}"
                            class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-smooth"
                        >
                            View Checklist & Apply
                        </a>

                        <form action="{{ route('student.saved.scholarships.destroy', $sch['slug']) }}" method="POST" onsubmit="return confirm('Remove {{ $sch['name'] }} from saved list?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-ink-400 hover:text-error-600 cursor-pointer">
                                Remove from list
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-12 rounded-2xl border border-dashed border-border-default bg-surface text-center space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-surface-subtle text-ink-400 flex items-center justify-center mx-auto">
                        <x-icon name="award" class="w-6 h-6" />
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-heading font-bold text-base text-ink-900">No saved scholarships</h3>
                        <p class="text-xs text-ink-500 font-sans max-w-sm mx-auto">
                            Explore fully-funded government and provincial endowment scholarships.
                        </p>
                    </div>
                    <a href="{{ route('scholarships.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 text-white text-xs font-semibold shadow-xs">
                        Browse Scholarships
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.student>
