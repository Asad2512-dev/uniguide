<x-layouts.university-admin title="Institutional Console — NUST Admissions Directorate">
    <x-ui.page-header 
        title="Institutional Admissions Console"
        description="Manage candidate credentials, entry test validations, merit quota distributions, and statutory HEC reporting."
        badge="NUST Main Campus H-12"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'University Console', 'url' => route('preview.university_admin')],
                    ['label' => 'Admissions Management']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="download">
                Export Gazette CSV
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" icon="sliders-horizontal" @click="$dispatch('open-drawer', 'admin-filter-drawer')">
                Cycle Settings
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
        @foreach($stats as $st)
            <x-ui.metric-card 
                :title="$st['title']"
                :value="$st['value']"
                :change="$st['change']"
                :changeType="$st['changeType']"
                :icon="$st['icon']"
                :iconColor="$st['iconColor']"
            />
        @endforeach
    </div>

    <!-- Active Admission Cycle Timeline & Program Quotas Table -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        <!-- Quotas Table (8 cols) -->
        <div class="lg:col-span-8 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-base font-heading font-bold text-ink-950">
                        Undergraduate Programs & Quota Allocations
                    </h3>
                    <p class="text-xs text-ink-500 font-sans mt-0.5">
                        Fall 2026 Academic Year • Pakistan Engineering Council & HEC Approved Seats
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <x-form.filter-chip label="SEECS" active="true" />
                    <x-form.filter-chip label="SMME" />
                    <x-form.filter-chip label="NBS" />
                </div>
            </div>

            <x-ui.table>
                <thead>
                    <tr>
                        <x-ui.table.header>Degree Discipline</x-ui.table.header>
                        <x-ui.table.header>Total Seats</x-ui.table.header>
                        <x-ui.table.header>Applicants</x-ui.table.header>
                        <x-ui.table.header>Closing Cutoff</x-ui.table.header>
                        <x-ui.table.header align="right">Status</x-ui.table.header>
                    </tr>
                </thead>
                <tbody>
                    <x-ui.table.row>
                        <x-ui.table.cell>
                            <span class="font-bold text-ink-950 font-heading">BS Software Engineering</span>
                            <div class="text-xs text-ink-500">School of Electrical Eng. & Computer Science</div>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-semibold text-ink-900">120 Seats</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-semibold text-brand-600">8,420</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-bold text-ink-900">80.12%</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell align="right">
                            <x-ui.badge variant="success" size="sm" :dot="true">List 1 Declared</x-ui.badge>
                        </x-ui.table.cell>
                    </x-ui.table.row>

                    <x-ui.table.row>
                        <x-ui.table.cell>
                            <span class="font-bold text-ink-950 font-heading">BS Computer Science</span>
                            <div class="text-xs text-ink-500">SEECS Islamabad Campus</div>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-semibold text-ink-900">140 Seats</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-semibold text-brand-600">9,850</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-bold text-ink-900">81.40%</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell align="right">
                            <x-ui.badge variant="success" size="sm" :dot="true">List 1 Declared</x-ui.badge>
                        </x-ui.table.cell>
                    </x-ui.table.row>

                    <x-ui.table.row>
                        <x-ui.table.cell>
                            <span class="font-bold text-ink-950 font-heading">BE Mechanical Engineering</span>
                            <div class="text-xs text-ink-500">SMME Islamabad Campus</div>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-semibold text-ink-900">160 Seats</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-semibold text-brand-600">5,140</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell>
                            <span class="font-mono text-xs font-bold text-ink-900">72.30%</span>
                        </x-ui.table.cell>
                        <x-ui.table.cell align="right">
                            <x-ui.badge variant="warning" size="sm" :dot="true">List 2 Pending</x-ui.badge>
                        </x-ui.table.cell>
                    </x-ui.table.row>
                </tbody>
            </x-ui.table>

            <x-ui.pagination :currentPage="1" :totalPages="6" :totalItems="54" :perPage="3" />
        </div>

        <!-- Timeline & Distribution Chart (4 cols) -->
        <div class="lg:col-span-4 space-y-6">
            <x-ui.card padding="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider">
                        Admission Milestones
                    </h4>
                    <span class="text-[10px] bg-brand-50 text-brand-700 px-2 py-0.5 rounded font-mono font-bold">Fall 2026</span>
                </div>
                <x-ui.timeline />
            </x-ui.card>
        </div>
    </div>

    <!-- Filter Drawer -->
    <x-ui.drawer id="admin-filter-drawer" title="Admissions Cycle Configuration" description="Configure quota reserves and HEC statutory limits.">
        <div class="space-y-4">
            <x-form.select name="active_session" label="Active Academic Session" :options="['fall_2026' => 'Fall 2026 Undergraduate', 'spring_2027' => 'Spring 2027 Postgraduate']" />
            <x-form.toggle name="online_portal" label="Accept Online Registrations" description="Permits candidates to submit NET credentials" checked="true" />
            <x-form.toggle name="challan_sync" label="1-Link Bank Challan API Sync" description="Automatic verification with Habib Bank & Meezan" checked="true" />
            <x-form.input name="provincial_quota" label="Balochistan & FATA Reserved Seats" value="48 Seats" />
        </div>
        <x-slot:footer>
            <x-ui.button variant="ghost" size="sm" @click="show = false">
                Cancel
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" @click="$store.toasts.add({ title: 'Settings Saved', message: 'Institutional parameters updated.', type: 'success' }); show = false">
                Save Parameters
            </x-ui.button>
        </x-slot:footer>
    </x-ui.drawer>
</x-layouts.university-admin>
