<x-layouts.national-admin title="National Higher Education Analytics & Reports">
    <x-ui.page-header 
        title="National Higher Education Analytics & Reports"
        description="Macro insights into student discipline demand shifts, inter-provincial applicant migration flows, and tuition fee inflation benchmarks across Pakistan."
        badge="HEC Strategic Intelligence"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
                    ['label' => 'National Analytics']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="file-down">
                Download Annual HEC Report
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    @if(session('toast_success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between text-xs font-sans shadow-xs">
            <div class="flex items-center gap-2.5">
                <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0" />
                <span class="font-medium">{{ session('toast_success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 cursor-pointer">
                <x-icon name="x" class="w-4 h-4" />
            </button>
        </div>
    @endif

    <div class="space-y-8">
        <!-- Discipline Demand Breakdown -->
        <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Undergraduate Discipline Applicant Demand (Fall 2026)</h3>
                    <p class="text-xs text-ink-500 font-sans">Percentage of total first-preference applications submitted nationwide.</p>
                </div>
                <span class="text-xs font-mono text-ink-400">240,000 Total Candidates Sample</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                @foreach($discipline_demand as $d)
                    <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] font-mono text-ink-500 uppercase block mb-1">{{ $d['name'] }}</span>
                            <span class="font-heading font-extrabold text-2xl text-ink-950 block">{{ $d['share'] }}%</span>
                            <span class="text-[11px] font-mono text-brand-700 mt-0.5 block">{{ number_format($d['candidates']) }} applicants</span>
                        </div>
                        <div class="w-full bg-border-subtle h-2 rounded-full overflow-hidden mt-3">
                            <div class="bg-brand-600 h-full rounded-full" style="width: {{ $d['share'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Provincial Migration Inflow (7 Cols) -->
            <div class="lg:col-span-7 p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-base text-ink-950">Inter-Provincial Student Migration Flow</h3>
                        <p class="text-xs text-ink-500 font-sans">Net student outflow towards major federal education hubs.</p>
                    </div>
                    <span class="text-xs font-mono text-ink-400">Mobility Trends</span>
                </div>

                <div class="space-y-3 pt-2">
                    @foreach($provincial_inflow as $flow)
                        <div class="p-3.5 rounded-xl bg-surface-subtle border border-border-subtle flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center">
                                    <x-icon name="arrow-up-right" class="w-4 h-4" />
                                </div>
                                <div>
                                    <span class="font-heading font-bold text-xs text-ink-950 block">{{ $flow['from'] }}</span>
                                    <span class="text-[11px] text-ink-500 block font-sans">Destination: {{ $flow['destination'] }}</span>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-xs text-ink-800">
                                {{ number_format($flow['students_count']) }} Students
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Fee Inflation Index (5 Cols) -->
            <div class="lg:col-span-5 p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-base text-ink-950">Annual Tuition Inflation Index</h3>
                        <p class="text-xs text-ink-500 font-sans">Public vs Private semester averages.</p>
                    </div>
                    <span class="text-xs font-mono text-ink-400">PKR Benchmark</span>
                </div>

                <div class="rounded-xl border border-border-default overflow-hidden">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-surface-subtle border-b border-border-default text-[10px] font-mono uppercase text-ink-500">
                                <th class="py-2.5 px-3">Year</th>
                                <th class="py-2.5 px-3">Public Avg</th>
                                <th class="py-2.5 px-3 text-right">Private Avg</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle font-mono text-[11px]">
                            @foreach($fee_inflation_index as $row)
                                <tr class="hover:bg-surface-subtle/60">
                                    <td class="py-2.5 px-3 font-bold text-ink-950">{{ $row['year'] }}</td>
                                    <td class="py-2.5 px-3 text-ink-700">PKR {{ number_format($row['public_avg']) }}</td>
                                    <td class="py-2.5 px-3 text-right font-semibold text-brand-700">PKR {{ number_format($row['private_avg']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.national-admin>
