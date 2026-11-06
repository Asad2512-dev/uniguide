<x-layouts.university-admin 
    :title="'Scholarships & Financial Aid Management — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <div x-data="{ addSchModal: false }">
        <x-ui.page-header 
            title="Scholarships, Endowments & Need-Based Aid"
            description="Manage Need-Based allocations, university merit tuition waivers, Ehsaas social safety aid, and provincial quota grants."
            badge="PKR 127.5M Aid Portfolio"
            badgeVariant="brand"
        >
            <x-slot:breadcrumbs>
                <x-ui.breadcrumbs 
                    :items="[
                        ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                        ['label' => 'Scholarships']
                    ]"
                />
            </x-slot:breadcrumbs>

            <x-slot:actions>
                <x-ui.button variant="primary" size="sm" icon="plus" @click="addSchModal = true">
                    Register Aid Scheme
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Schemes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($scholarships as $sch)
                <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between pb-3 border-b border-border-subtle">
                            <div>
                                <span class="text-xs sm:text-[10px] font-mono font-bold uppercase text-brand-700 bg-brand-50 px-2 py-0.5 rounded">
                                    {{ $sch['category'] }}
                                </span>
                                <h3 class="font-heading font-bold text-base text-ink-950 mt-1.5">{{ $sch['name'] }}</h3>
                                <span class="text-xs text-ink-500 font-sans block mt-0.5">
                                    Funding Agency: <strong class="text-ink-800">{{ $sch['funding_agency'] }}</strong>
                                </span>
                            </div>

                            <span class="text-xs sm:text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2 py-0.5 rounded">
                                Active Scheme
                            </span>
                        </div>

                        <!-- Budget & Coverage KPIs -->
                        <div class="grid grid-cols-2 gap-3 text-xs font-sans">
                            <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">Annual Allocation</span>
                                <span class="font-mono font-bold text-sm text-ink-950">{{ $sch['annual_budget'] }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">Awarded / Target</span>
                                <span class="font-mono font-bold text-sm text-brand-600">{{ $sch['awarded_count'] }} / {{ $sch['beneficiaries_target'] }} Scholars</span>
                            </div>
                            <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle col-span-2">
                                <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">Coverage & Benefits</span>
                                <span class="font-semibold text-emerald-800">{{ $sch['coverage'] }}</span>
                            </div>
                        </div>

                        <!-- Eligibility Note -->
                        <div class="text-xs font-sans text-ink-600">
                            <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block font-bold mb-0.5">Qualifying Criteria:</span>
                            <p class="leading-relaxed">{{ $sch['eligibility_criteria'] }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                        <span class="text-xs sm:text-[11px] text-ink-600 font-mono">{{ $sch['application_window'] }}</span>
                        <a href="{{ route('uni_admin.documents') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">
                            Audit Beneficiaries →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add Scholarship Modal -->
        <div 
            x-show="addSchModal" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-surface rounded-2xl border border-border-default p-4 sm:p-6 max-w-lg w-full shadow-elevated space-y-5" @click.outside="addSchModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Register Financial Aid Scheme</h3>
                    <button type="button" @click="addSchModal = false" class="text-ink-400 hover:text-ink-700 text-sm">✕</button>
                </div>

                <form action="{{ route('uni_admin.scholarships.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Scholarship Title</label>
                        <input type="text" name="name" placeholder="e.g. Alumni Endowment Scholarship for Women in Computing" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Funding Agency</label>
                            <input type="text" name="funding_agency" placeholder="NUST Alumni Association" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Category</label>
                            <select name="category" class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans">
                                <option value="Need-Based">Need-Based Aid</option>
                                <option value="Merit-Based">Merit Concession</option>
                                <option value="Gender & Diversity">Gender & Diversity</option>
                                <option value="Provincial Quota">Provincial Quota</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Coverage & Benefits</label>
                        <input type="text" name="coverage" placeholder="100% Tuition Fee + Laptop Grant" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Annual Budget (PKR)</label>
                            <input type="text" name="annual_budget" placeholder="PKR 15,000,000" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Beneficiaries Target</label>
                            <input type="number" name="beneficiaries_target" placeholder="30" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-mono" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Eligibility Criteria</label>
                        <textarea name="eligibility_criteria" rows="2" placeholder="Undergraduate female computing candidates with family income < PKR 55,000" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans"></textarea>
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                        <button type="button" @click="addSchModal = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold">Publish Scheme</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.university-admin>

