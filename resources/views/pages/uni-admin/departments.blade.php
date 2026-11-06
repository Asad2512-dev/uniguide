<x-layouts.university-admin 
    :title="'Academic Departments Management — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <div x-data="{ addDeptModal: false }">
        <x-ui.page-header 
            title="Academic Departments & Faculties"
            description="Manage university schools, departmental deans, doctoral faculty ratios, and undergraduate discipline quotas."
            badge="{{ count($departments) }} Accredited Departments"
            badgeVariant="brand"
        >
            <x-slot:breadcrumbs>
                <x-ui.breadcrumbs 
                    :items="[
                        ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                        ['label' => 'Departments']
                    ]"
                />
            </x-slot:breadcrumbs>

            <x-slot:actions>
                <x-ui.button variant="primary" size="sm" icon="plus" @click="addDeptModal = true">
                    Create Department
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Departments Table Card -->
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <x-ui.table>
                <thead>
                    <tr>
                        <x-ui.table.header>Faculty / School</x-ui.table.header>
                        <x-ui.table.header>Dean / Department Head</x-ui.table.header>
                        <x-ui.table.header>Constituent Campus</x-ui.table.header>
                        <x-ui.table.header>Faculty Strength</x-ui.table.header>
                        <x-ui.table.header>UG Quota</x-ui.table.header>
                        <x-ui.table.header align="right">Status</x-ui.table.header>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $dept)
                        <x-ui.table.row>
                            <x-ui.table.cell>
                                <div>
                                    <span class="font-bold text-ink-950 font-heading text-sm">{{ $dept['name'] }}</span>
                                    <div class="text-xs text-ink-500 font-mono">{{ $dept['acronym'] }} • Est. {{ $dept['established'] }}</div>
                                </div>
                            </x-ui.table.cell>

                            <x-ui.table.cell>
                                <span class="font-semibold text-xs text-ink-900 block">{{ $dept['head'] }}</span>
                                <span class="text-xs sm:text-[11px] text-ink-500 font-sans">{{ $dept['head_title'] }}</span>
                            </x-ui.table.cell>

                            <x-ui.table.cell>
                                <span class="text-xs text-ink-700 font-sans">{{ $dept['campus'] }}</span>
                            </x-ui.table.cell>

                            <x-ui.table.cell>
                                <span class="font-mono text-xs font-bold text-ink-900 block">{{ $dept['faculty_count'] }} Faculty</span>
                                <span class="font-mono text-xs sm:text-[10px] text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200/60 inline-block mt-0.5">
                                    {{ $dept['phd_faculty_ratio'] }}
                                </span>
                            </x-ui.table.cell>

                            <x-ui.table.cell>
                                <span class="font-mono text-xs font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded">
                                    {{ $dept['undergraduate_seats'] }} Seats
                                </span>
                            </x-ui.table.cell>

                            <x-ui.table.cell align="right">
                                <x-ui.badge variant="success" size="sm" :dot="true">Active</x-ui.badge>
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @endforeach
                </tbody>
            </x-ui.table>
        </div>

        <!-- Add Department Modal -->
        <div 
            x-show="addDeptModal" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-surface rounded-2xl border border-border-default p-4 sm:p-6 max-w-lg w-full shadow-elevated space-y-5" @click.outside="addDeptModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Create Academic Department</h3>
                    <button type="button" @click="addDeptModal = false" class="text-ink-400 hover:text-ink-700 text-sm">✕</button>
                </div>

                <form action="{{ route('uni_admin.departments.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Department / School Name</label>
                        <input type="text" name="name" placeholder="e.g. School of Chemical & Materials Engineering" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Acronym / Code</label>
                            <input type="text" name="acronym" placeholder="SCME" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Campus</label>
                            <select name="campus" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans">
                                @foreach($campuses as $c)
                                    <option value="{{ $c['name'] }}">{{ $c['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Dean / Head of Department</label>
                            <input type="text" name="head" placeholder="Dr. Arshad Hussain" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Official Designation</label>
                            <input type="text" name="head_title" placeholder="Principal & Dean" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Total Faculty Members</label>
                            <input type="number" name="faculty_count" placeholder="48" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">PhD Faculty Ratio</label>
                            <input type="text" name="phd_faculty_ratio" placeholder="62% PhD Holders" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                        <button type="button" @click="addDeptModal = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold">Register Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.university-admin>

