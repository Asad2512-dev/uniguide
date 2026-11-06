<x-layouts.national-admin title="National Higher Education Analytics & Reports">
    <x-ui.page-header 
        title="National Higher Education Analytics & Reports"
        description="Macro insights into student discipline demand shifts, inter-provincial applicant migration flows, and tuition fee inflation benchmarks across Punjab and Islamabad."
        badge="Platform Analytics"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Platform Administration', 'url' => route('national_admin.dashboard')],
                    ['label' => 'National Analytics']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button 
                variant="outline" 
                size="sm" 
                icon="file-down"
                @click="$store.toasts?.add({ title: 'Federal Report Dispatched', message: 'Annual Higher Education Trends 2026 PDF dispatched to your inbox.', type: 'success' })"
            >
                Download Annual Report
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
        <!-- Visual Discipline Demand Breakdown & Chart -->
        <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Undergraduate Discipline Applicant Demand (Fall 2026)</h3>
                    <p class="text-xs text-ink-500 font-sans">Percentage of total first-preference applications submitted within current regional coverage.</p>
                </div>
                <span class="text-xs font-mono text-ink-600">240,000 Total Candidates Sample</span>
            </div>

            <!-- Chart.js Visualization Canvas -->
            <div class="h-64 w-full bg-surface-subtle/50 rounded-xl p-4 border border-border-subtle relative">
                <canvas id="disciplineDemandChart"></canvas>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-2">
                @foreach($discipline_demand as $d)
                    <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle flex flex-col justify-between">
                        <div>
                            <span class="text-xs sm:text-[11px] font-mono text-ink-500 uppercase block mb-1">{{ $d['name'] }}</span>
                            <span class="font-heading font-extrabold text-2xl text-ink-950 block">{{ $d['share'] }}%</span>
                            <span class="text-xs sm:text-[11px] font-mono text-brand-700 mt-0.5 block">{{ number_format($d['candidates']) }} applicants</span>
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
            <div class="lg:col-span-7 p-4 sm:p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-base text-ink-950">Inter-Provincial Student Migration Flow</h3>
                        <p class="text-xs text-ink-500 font-sans">Net student outflow towards major federal education hubs.</p>
                    </div>
                    <span class="text-xs font-mono text-ink-600">Mobility Trends</span>
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
                                    <span class="text-xs sm:text-[11px] text-ink-500 block font-sans">Destination: {{ $flow['destination'] }}</span>
                                </div>
                            </div>
                            <span class="font-mono font-bold text-xs text-ink-800">
                                {{ number_format($flow['students_count']) }} Students
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Fee Inflation Index (5 Cols) with Trend Chart -->
            <div class="lg:col-span-5 p-4 sm:p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-base text-ink-950">Annual Tuition Inflation Index</h3>
                        <p class="text-xs text-ink-500 font-sans">Public vs Private semester averages (PKR).</p>
                    </div>
                    <span class="text-xs font-mono text-ink-600">PKR Benchmark</span>
                </div>

                <!-- Inflation Chart Canvas -->
                <div class="h-48 w-full bg-surface-subtle/50 rounded-xl p-3 border border-border-subtle relative">
                    <canvas id="feeTrendChart"></canvas>
                </div>

                <div class="rounded-xl border border-border-default overflow-hidden">
                    <div class="overflow-x-auto">
                        <table data-mobile-table class="w-full text-left border-collapse text-xs" aria-label="Annual Tuition Inflation Index">
                            <thead>
                                <tr class="bg-surface-subtle border-b border-border-default text-xs sm:text-[10px] font-mono uppercase text-ink-500">
                                    <th scope="col" class="py-2.5 px-3">Year</th>
                                    <th scope="col" class="py-2.5 px-3">Public Avg</th>
                                    <th scope="col" class="py-2.5 px-3 text-right">Private Avg</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-subtle font-mono text-xs sm:text-[11px]">
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
    </div>

    <!-- Chart.js Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.createUniGuideChart === 'function') {
                // 1. Discipline Demand Horizontal Bar Chart
                const disciplineLabels = @json(array_column($discipline_demand, 'name'));
                const disciplineData = @json(array_column($discipline_demand, 'share'));

                window.createUniGuideChart('disciplineDemandChart', {
                    type: 'bar',
                    data: {
                        labels: disciplineLabels,
                        datasets: [{
                            label: 'Applicant Share %',
                            data: disciplineData,
                            backgroundColor: ['#0D9488', '#0284C7', '#6366F1', '#D97706'],
                            borderRadius: 8,
                            maxBarThickness: 32,
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                max: 60,
                                ticks: {
                                    callback: function(val) { return val + '%'; }
                                }
                            }
                        }
                    }
                });

                // 2. Fee Inflation Trend Line Chart
                const feeYears = @json(array_column($fee_inflation_index, 'year'));
                const publicFees = @json(array_column($fee_inflation_index, 'public_avg'));
                const privateFees = @json(array_column($fee_inflation_index, 'private_avg'));

                window.createUniGuideChart('feeTrendChart', {
                    type: 'line',
                    data: {
                        labels: feeYears,
                        datasets: [
                            {
                                label: 'Public Universities',
                                data: publicFees,
                                borderColor: '#0D9488',
                                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                                tension: 0.3,
                                fill: true,
                            },
                            {
                                label: 'Private Universities',
                                data: privateFees,
                                borderColor: '#0284C7',
                                backgroundColor: 'rgba(2, 132, 199, 0.1)',
                                tension: 0.3,
                                fill: true,
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(val) { return 'PKR ' + (val / 1000) + 'k'; }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-layouts.national-admin>
