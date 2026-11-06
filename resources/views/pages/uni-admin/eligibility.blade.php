<x-layouts.university-admin 
    :title="'Eligibility Rules & Merit Formulation — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <x-ui.page-header 
        title="Eligibility Mandates & Merit Formulas"
        description="Configure institutional merit aggregate formulas and review statutory legal baselines (PEC Act V of 1976 and NCEAC Computing Regulations)."
        badge="PEC & NCEAC Regulated"
        badgeVariant="success"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                    ['label' => 'Eligibility Rules']
                ]"
            />
        </x-slot:breadcrumbs>
    </x-ui.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left 7 Cols: Merit Weightage Formula Form -->
        <div class="lg:col-span-7">
            <form action="{{ route('uni_admin.eligibility.update') }}" method="POST" class="p-6 sm:p-8 rounded-2xl bg-surface border border-border-default shadow-card space-y-6">
                @csrf

                <div class="pb-4 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Undergraduate Merit Aggregate Formula</h3>
                    <p class="text-xs text-ink-500 font-sans mt-0.5">
                        Defines how standardized test scores, intermediate percentages, and matriculation results are weighted to establish closing merit ranks.
                    </p>
                </div>

                <div class="space-y-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-semibold text-ink-800">NUST Entry Test (NET) Weightage (%)</label>
                            <span class="text-xs font-mono font-bold text-brand-700 bg-brand-50 px-2 py-0.5 rounded">Primary Factor</span>
                        </div>
                        <input 
                            type="number" 
                            name="net_weight" 
                            value="{{ old('net_weight', $eligibility['merit_weightage_formula']['net_test_weight']) }}" 
                            min="0" 
                            max="100" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default text-xs font-mono"
                        />
                        <span class="text-xs sm:text-[10px] text-ink-600 mt-1 block">Best score among NET-1, NET-2, NET-3, and NET-4 is automatically utilized.</span>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-semibold text-ink-800">Intermediate / HSSC Part-1 Weightage (%)</label>
                            <span class="text-xs font-mono font-bold text-ink-600 bg-surface-subtle px-2 py-0.5 rounded">Board Sanad</span>
                        </div>
                        <input 
                            type="number" 
                            name="fsc_weight" 
                            value="{{ old('fsc_weight', $eligibility['merit_weightage_formula']['fsc_hssc_weight']) }}" 
                            min="0" 
                            max="100" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default text-xs font-mono"
                        />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="text-xs font-semibold text-ink-800">Matriculation / O-Level SSC Weightage (%)</label>
                            <span class="text-xs font-mono font-bold text-ink-600 bg-surface-subtle px-2 py-0.5 rounded">Secondary Credential</span>
                        </div>
                        <input 
                            type="number" 
                            name="matric_weight" 
                            value="{{ old('matric_weight', $eligibility['merit_weightage_formula']['matric_ssc_weight']) }}" 
                            min="0" 
                            max="100" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default text-xs font-mono"
                        />
                    </div>

                    <div class="p-4 rounded-xl bg-surface-subtle border border-border-default text-xs font-sans text-ink-600">
                        <div class="flex items-center justify-between font-bold text-ink-900 pb-2 border-b border-border-subtle">
                            <span>Total Aggregate Sum:</span>
                            <span class="font-mono text-sm text-brand-600">100%</span>
                        </div>
                        <p class="mt-2 text-xs sm:text-[11px] text-ink-500">
                            Last ratified under: {{ $eligibility['merit_weightage_formula']['last_amended'] }}.
                        </p>
                    </div>
                </div>

                <div class="pt-4 border-t border-border-subtle flex items-center justify-end">
                    <x-ui.button type="submit" variant="primary" size="md" icon="check">
                        Save Merit Weights
                    </x-ui.button>
                </div>
            </form>
        </div>

        <!-- Right 5 Cols: Statutory Hard Locks -->
        <div class="lg:col-span-5 space-y-4">
            <div class="pb-2">
                <h3 class="font-heading font-bold text-base text-ink-950">Statutory Legal Hard Locks</h3>
                <p class="text-xs text-ink-500 font-sans">Mandatory criteria enforced by federal council gazettes that cannot be relaxed by university senate.</p>
            </div>

            @foreach($eligibility['statutory_enforcements'] as $rule)
                <div class="p-5 rounded-2xl bg-surface border border-border-default shadow-card space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-[10px] font-mono font-bold uppercase text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded">
                            {{ $rule['authority'] }}
                        </span>
                        <x-icon name="lock" class="w-3.5 h-3.5 text-ink-400" />
                    </div>

                    <div>
                        <h4 class="font-heading font-bold text-sm text-ink-950">{{ $rule['title'] }}</h4>
                        <span class="text-xs font-mono text-ink-500 block mt-0.5">{{ $rule['reference'] }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle text-xs font-sans">
                        <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">Enforced Baseline</span>
                        <strong class="text-ink-900 font-semibold">{{ $rule['threshold'] }}</strong>
                    </div>

                    <div class="text-xs sm:text-[11px] text-ink-500 font-sans">
                        Scope: {{ $rule['scope'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.university-admin>

