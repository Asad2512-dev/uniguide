<x-layouts.student title="Student Portal — Admissions & Merit Dashboard">
    <!-- Page Header with Breadcrumbs & Actions -->
    <x-ui.page-header 
        title="Student Admissions Command"
        description="Track your active university admission applications, official entry test marks, and calculated merit percentiles across Pakistan."
        badge="Applicant ID: #UG-2026-8841"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Student Portal', 'url' => route('preview.student')],
                    ['label' => 'Admissions Overview']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="calculator">
                Recalculate Merit
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" icon="plus" @click="$dispatch('open-modal', 'new-app-modal')">
                Apply to New University
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <!-- Top Metrics Overview Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        <x-ui.metric-card 
            title="Calculated Aggregate"
            :value="$applicant['calculatedAggregate']"
            change="Top 4% Merit"
            changeType="up"
            icon="calculator"
            iconColor="brand"
            period="Based on Matric + FSc + NET"
        />

        <x-ui.metric-card 
            title="Active Applications"
            value="3 Submissions"
            change="1 Selected"
            changeType="up"
            icon="file-text"
            iconColor="success"
            period="Fall 2026 Cycles"
        />

        <x-ui.metric-card 
            title="Saved Universities"
            value="8 Campuses"
            change="+2 this week"
            changeType="up"
            icon="bookmark"
            iconColor="lavender"
            period="Monitored for Merit Lists"
        />

        <x-ui.metric-card 
            title="Scholarship Eligibility"
            value="4 Matched"
            change="100% Tuition"
            changeType="up"
            icon="award"
            iconColor="warning"
            period="Ehsaas & HEC Need-Based"
        />
    </div>

    <!-- Application Progression Step Indicator -->
    <div class="p-6 bg-surface rounded-2xl border border-border-default shadow-card mb-8">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-sm font-heading font-bold text-ink-950">
                Application Cycle Progression — Fall 2026
            </h3>
            <span class="text-xs font-sans text-brand-600 font-semibold">Step 2 of 4 Active</span>
        </div>
        <x-ui.step-indicator orientation="horizontal" />
    </div>

    <!-- Grid: Active Applications Table & Merit Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Applications Table (8 cols) -->
        <div class="lg:col-span-8 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-heading font-bold text-ink-950">
                        My Submitted University Applications
                    </h3>
                    <p class="text-xs text-ink-500 font-sans mt-0.5">
                        Real-time status synced with institutional admissions offices
                    </p>
                </div>
                <x-ui.badge variant="success" size="sm" :dot="true">
                    Live Status
                </x-ui.badge>
            </div>

            <x-ui.table>
                <thead>
                    <tr>
                        <x-ui.table.header>Institution & Program</x-ui.table.header>
                        <x-ui.table.header>Admission Status</x-ui.table.header>
                        <x-ui.table.header>Merit Position</x-ui.table.header>
                        <x-ui.table.header align="right">Next Action</x-ui.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                        <x-ui.table.row>
                            <x-ui.table.cell>
                                <div class="font-bold text-ink-950 font-heading">{{ $app['university'] }}</div>
                                <div class="text-xs text-ink-500">{{ $app['program'] }}</div>
                            </x-ui.table.cell>
                            <x-ui.table.cell>
                                <x-ui.badge :variant="$app['statusVariant']" size="sm" :dot="true">
                                    {{ $app['status'] }}
                                </x-ui.badge>
                            </x-ui.table.cell>
                            <x-ui.table.cell>
                                <span class="font-mono text-xs font-semibold text-ink-900">{{ $app['meritPosition'] }}</span>
                                <div class="text-[11px] text-ink-400">{{ $app['deadline'] }}</div>
                            </x-ui.table.cell>
                            <x-ui.table.cell align="right">
                                <x-ui.button variant="outline" size="xs">
                                    Download Challan
                                </x-ui.button>
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @endforeach
                </tbody>
            </x-ui.table>
        </div>

        <!-- Merit Chart & AI Intelligence (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <x-ui.card padding="p-5">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider">
                        Merit Percentile Benchmark
                    </h4>
                    <span class="text-[11px] font-mono text-brand-600 font-semibold">82.45% Agg</span>
                </div>
                <p class="text-xs text-ink-500 font-sans mb-4">
                    Your aggregate score compared against historical 5-year closing cutoffs at top institutions.
                </p>

                <div class="h-52 w-full relative">
                    <canvas id="studentMeritChart"></canvas>
                </div>
            </x-ui.card>
        </div>
    </div>

    <!-- AI Advisor Guidance Widget -->
    <div class="mb-8">
        <x-domain.ai-message 
            advisorName="UniGuide Personalized Academic Advisor"
            confidence="97% Match Accuracy"
            timestamp="Live Analysis"
            :citations="[
                ['source' => 'NUST SEECS Official Admission Gazette 2026', 'reference' => 'Quota Allocation Order No. 42', 'date' => 'Verified Yesterday']
            ]"
            :prompts="[
                'Generate fee payment challan for NUST SEECS',
                'Upload remaining domicile and FSc character certificate',
                'Explore FAST Islamabad hostel accommodation options'
            ]"
        >
            <p>
                Congratulations Ahmad! Your <strong>82.45%</strong> aggregate safely placed you in the <strong>First Merit List</strong> for BS Software Engineering at NUST Islamabad.
            </p>
            <p>
                To confirm your admission seat, submit your initial admission fee challan (<strong>PKR 142,500</strong>) at any Habib Bank Limited (HBL) branch before <strong>18 July 2026 (5:00 PM PKT)</strong>.
            </p>
        </x-domain.ai-message>
    </div>

    <!-- Application Modal -->
    <x-ui.modal id="new-app-modal" title="Submit New University Application" description="Select from 248 recognized Pakistani institutions.">
        <form class="space-y-4">
            <x-form.select name="university" label="Target University" :options="['nust' => 'National University of Sciences & Technology (NUST)', 'fast' => 'FAST-NUCES Islamabad', 'lums' => 'Lahore University of Management Sciences (LUMS)', 'giki' => 'Ghulam Ishaq Khan Institute (GIKI)']" />
            <x-form.select name="program" label="Target Program" :options="['bscs' => 'BS Computer Science (BSCS)', 'se' => 'BS Software Engineering (BSSE)', 'ai' => 'BS Artificial Intelligence', 'ee' => 'BE Electrical Engineering']" />
            <x-form.input name="net_score" label="Entry Test Score (NET / ECAT / NAT)" placeholder="e.g. 156" />
            <x-form.checkbox name="hostel_req" label="Apply for on-campus student housing hostel" description="Available for outstation students outside Islamabad/Rawalpindi" />
        </form>
        <x-slot:footer>
            <x-ui.button variant="ghost" size="sm" @click="show = false">
                Cancel
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" @click="$store.toasts.add({ title: 'Application Initiated', message: 'University application draft created successfully.', type: 'success' }); show = false">
                Submit Application
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.createUniGuideChart) {
                window.createUniGuideChart('studentMeritChart', {
                    type: 'bar',
                    data: {
                        labels: ['Your Score', 'NUST Cutoff', 'FAST Cutoff', 'GIKI Cutoff', 'COMSATS Cutoff'],
                        datasets: [{
                            label: 'Aggregate %',
                            data: [82.45, 80.12, 77.80, 79.40, 75.30],
                            backgroundColor: ['#4355E8', '#12866C', '#667085', '#667085', '#667085'],
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                min: 70,
                                max: 90,
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.student>

