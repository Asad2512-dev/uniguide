<x-layouts.national-admin title="HEC National Command — Regulatory Oversight & Registry">
    <x-ui.page-header 
        title="National Higher Education Command Console"
        description="Federal oversight, institutional charter verification, regulatory compliance monitoring, and national student admissions intelligence."
        badge="HEC Statutory Authority"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'HEC National Command', 'url' => route('preview.national_admin')],
                    ['label' => 'Federal University Registry']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="primary" size="sm" icon="sliders" href="{{ route('national_admin.dashboard') }}">
                Launch Full Platform (15 Modules)
            </x-ui.button>
            <x-ui.button variant="outline" size="sm" icon="refresh-cw">
                Sync Provincial Charters
            </x-ui.button>
            <x-ui.button variant="destructive" size="sm" icon="alert-triangle" @click="$dispatch('open-modal', 'hec-alert-modal')">
                Issue Regulatory Warning
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <!-- Federal Overview Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        @foreach($overview as $ov)
            <x-ui.metric-card 
                :title="$ov['title']"
                :value="$ov['value']"
                :change="$ov['change']"
                :changeType="$ov['changeType']"
                :icon="$ov['icon']"
                :iconColor="$ov['iconColor']"
            />
        @endforeach
    </div>

    <!-- National Registry Table & Provincial Enrollment Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Registry Table (8 cols) -->
        <div class="lg:col-span-8 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-base font-heading font-bold text-ink-950">
                        Federal Higher Education Registry
                    </h3>
                    <p class="text-xs text-ink-500 font-sans mt-0.5">
                        Statutory charters recognized under Higher Education Commission Ordinance 2002
                    </p>
                </div>
                <x-form.search id="registry-search" placeholder="Search charter by institute or city..." />
            </div>

            <x-ui.table>
                <thead>
                    <tr>
                        <x-ui.table.header>Institution & Charter</x-ui.table.header>
                        <x-ui.table.header>Province / Jurisdiction</x-ui.table.header>
                        <x-ui.table.header>HEC Category</x-ui.table.header>
                        <x-ui.table.header>Compliance Status</x-ui.table.header>
                        <x-ui.table.header align="right">Audit Record</x-ui.table.header>
                    </tr>
                </thead>
                <tbody>
                    <x-ui.table.row>
                        <x-ui.table.cell>
                            <span class="font-bold text-ink-950 font-heading">Quaid-i-Azam University</span>
                            <div class="text-xs text-ink-500">Federal Act of Parliament 1967</div>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="text-xs font-medium text-ink-700">Islamabad (ICT)</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-bold text-success-700 bg-success-50 px-2 py-0.5 rounded border border-success-200">
                                Category W
                            </span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <x-ui.badge variant="success" size="sm" :dot="true">Full Accreditation</x-ui.badge>
                        </x-ui.table.cell>
                        <x-ui.table.cell align="right">
                            <span class="font-mono text-xs text-ink-500">Audit Oct 2026</span>
                        </x-ui.table.cell>
                    </x-ui.table.row>

                    <x-ui.table.row>
                        <x-ui.table.cell>
                            <span class="font-bold text-ink-950 font-heading">University of the Punjab</span>
                            <div class="text-xs text-ink-500">Govt. of the Punjab Charter 1882</div>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="text-xs font-medium text-ink-700">Lahore, Punjab</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-bold text-success-700 bg-success-50 px-2 py-0.5 rounded border border-success-200">
                                Category W
                            </span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <x-ui.badge variant="success" size="sm" :dot="true">Full Accreditation</x-ui.badge>
                        </x-ui.table.cell>
                        <x-ui.table.cell align="right">
                            <span class="font-mono text-xs text-ink-500">Audit Sep 2026</span>
                        </x-ui.table.cell>
                    </x-ui.table.row>

                    <x-ui.table.row>
                        <x-ui.table.cell>
                            <span class="font-bold text-ink-950 font-heading">Institute of Business Administration (IBA)</span>
                            <div class="text-xs text-ink-500">Govt. of Sindh Act 1994</div>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="text-xs font-medium text-ink-700">Karachi, Sindh</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-bold text-success-700 bg-success-50 px-2 py-0.5 rounded border border-success-200">
                                Category W
                            </span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <x-ui.badge variant="success" size="sm" :dot="true">Full Accreditation</x-ui.badge>
                        </x-ui.table.cell>
                        <x-ui.table.cell align="right">
                            <span class="font-mono text-xs text-ink-500">Audit Nov 2026</span>
                        </x-ui.table.cell>
                    </x-ui.table.row>
                </tbody>
            </x-ui.table>

            <x-ui.pagination :currentPage="1" :totalPages="25" :totalItems="248" :perPage="3" />
        </div>

        <!-- Provincial Distribution Chart (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <x-ui.card padding="p-5">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider">
                        Enrollment By Province
                    </h4>
                    <span class="text-[11px] font-mono text-brand-600 font-semibold">1.84M Total</span>
                </div>
                <p class="text-xs text-ink-500 font-sans mb-4">
                    Higher education student distribution across Pakistan's 5 territorial jurisdictions.
                </p>

                <div class="h-60 w-full relative">
                    <canvas id="provincialEnrollmentChart"></canvas>
                </div>
            </x-ui.card>
        </div>
    </div>

    <!-- HEC Regulatory Warning Modal -->
    <x-ui.confirm-dialog 
        id="hec-alert-modal"
        title="Issue Public HEC Regulatory Warning Notice"
        message="This notice will be published immediately on the national portal and flagged to all student applicants regarding unaccredited sub-campuses or unauthorized degree offerings."
        confirmText="Publish Public Notice"
        cancelText="Discard"
        variant="destructive"
    />

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.createUniGuideChart) {
                window.createUniGuideChart('provincialEnrollmentChart', {
                    type: 'doughnut',
                    data: {
                        labels: ['Punjab', 'Sindh', 'Khyber Pakhtunkhwa', 'Islamabad (ICT)', 'Balochistan & GB/AJK'],
                        datasets: [{
                            data: [54, 23, 12, 7, 4],
                            backgroundColor: ['#4355E8', '#12866C', '#7C3AED', '#C58B24', '#667085'],
                            borderWidth: 2,
                            borderColor: '#FFFFFF',
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom',
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.national-admin>

