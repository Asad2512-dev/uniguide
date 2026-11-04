<x-layouts.university-admin 
    :title="'Degree Programs & Curricula — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <div x-data="{ 
        searchTerm: '', 
        levelFilter: 'all', 
        programs: @js($programs),
        selectedProgram: null,
        drawerOpen: false,
        addModalOpen: false,

        openDrawer(prog) {
            this.selectedProgram = prog;
            this.drawerOpen = true;
        },

        filteredPrograms() {
            return this.programs.filter(p => {
                const matchesSearch = !this.searchTerm || (p.name + ' ' + p.code + ' ' + p.department).toLowerCase().includes(this.searchTerm.toLowerCase());
                const matchesLevel = this.levelFilter === 'all' || p.level.toLowerCase() === this.levelFilter.toLowerCase();
                return matchesSearch && matchesLevel;
            });
        }
    }">
        <x-ui.page-header 
            title="Degree Programs & Academic Curricula"
            description="Official degree catalog, accreditation council standings (PEC, NCEAC, NBEAC), approved semester fees, and intake quotas."
            badge="{{ count($programs) }} Published Degrees"
            badgeVariant="brand"
        >
            <x-slot:breadcrumbs>
                <x-ui.breadcrumbs 
                    :items="[
                        ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                        ['label' => 'Programs']
                    ]"
                />
            </x-slot:breadcrumbs>

            <x-slot:actions>
                <x-ui.button variant="outline" size="sm" icon="download">
                    Export Curriculum PDF
                </x-ui.button>
                <x-ui.button variant="primary" size="sm" icon="plus" @click="addModalOpen = true">
                    Add New Program
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Filters & Search Toolbar -->
        <div class="p-4 rounded-2xl bg-surface border border-border-default mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-2xs">
            <div class="flex items-center gap-2">
                <button 
                    type="button" 
                    @click="levelFilter = 'all'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer"
                    :class="levelFilter === 'all' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                >
                    All Disciplines
                </button>
                <button 
                    type="button" 
                    @click="levelFilter = 'undergraduate'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer"
                    :class="levelFilter === 'undergraduate' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                >
                    Undergraduate (BS/BE)
                </button>
                <button 
                    type="button" 
                    @click="levelFilter = 'postgraduate'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all cursor-pointer"
                    :class="levelFilter === 'postgraduate' ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle'"
                >
                    Postgraduate (MS/PhD)
                </button>
            </div>

            <div class="relative max-w-xs w-full">
                <x-icon name="search" class="w-4 h-4 text-ink-400 absolute left-3 top-1/2 -translate-y-1/2" />
                <input 
                    type="text" 
                    x-model="searchTerm" 
                    placeholder="Search by degree title, code, or department..." 
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-border-default bg-surface text-ink-900 placeholder:text-ink-400 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                />
            </div>
        </div>

        <!-- Programs Table Card -->
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" aria-label="Academic Degree Programs Catalog">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th scope="col" class="py-3 px-4">Discipline & Code</th>
                            <th scope="col" class="py-3 px-4">Department / School</th>
                            <th scope="col" class="py-3 px-4">Duration & Credits</th>
                            <th scope="col" class="py-3 px-4">Accreditation</th>
                            <th scope="col" class="py-3 px-4">Tuition / Sem</th>
                            <th scope="col" class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        <template x-for="p in filteredPrograms()" :key="p.id">
                            <tr class="hover:bg-surface-subtle/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-ink-950 font-heading block" x-text="p.name"></span>
                                    <span class="text-[11px] font-mono text-ink-500" x-text="p.code"></span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-ink-800" x-text="p.department"></span>
                                    <span class="text-[11px] text-ink-400 block" x-text="p.campus"></span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="text-ink-800 font-sans" x-text="p.duration"></span>
                                    <span class="text-[10px] font-mono text-ink-500 block" x-text="p.credit_hours + ' Credit Hours'"></span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-50 text-emerald-800 border border-emerald-200" x-text="p.accreditation"></span>
                                </td>

                                <td class="py-3.5 px-4">
                                    <span class="font-mono font-bold text-ink-950" x-text="p.tuition_semester"></span>
                                    <span class="text-[10px] text-brand-600 block" x-text="p.intake_seats + ' Approved Seats'"></span>
                                </td>

                                <td class="py-3.5 px-4 text-right">
                                    <button 
                                        type="button" 
                                        @click="openDrawer(p)"
                                        class="px-3 py-1.5 rounded-lg border border-border-default hover:bg-surface text-ink-700 font-semibold text-xs transition-smooth cursor-pointer"
                                    >
                                        Inspect & Edit →
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Slide-over Detail Drawer -->
        <div 
            x-show="drawerOpen" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-hidden bg-ink-950/40 backdrop-blur-xs flex justify-end"
        >
            <div 
                x-show="drawerOpen" 
                x-transition:enter="transform transition ease-in-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="w-full max-w-xl bg-surface border-l border-border-default shadow-elevated h-full overflow-y-auto flex flex-col justify-between p-6 sm:p-8"
                @click.outside="drawerOpen = false"
            >
                <div class="space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-border-subtle">
                        <div>
                            <span class="text-[10px] font-mono uppercase text-brand-700 font-bold bg-brand-50 px-2 py-0.5 rounded" x-text="selectedProgram ? selectedProgram.code : ''"></span>
                            <h3 class="font-heading font-bold text-xl text-ink-950 mt-1" x-text="selectedProgram ? selectedProgram.name : ''"></h3>
                        </div>
                        <button type="button" @click="drawerOpen = false" class="text-ink-400 hover:text-ink-700 text-base">✕</button>
                    </div>

                    <form :action="'{{ route('uni_admin.programs') }}/' + (selectedProgram ? selectedProgram.id : '')" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Discipline Name</label>
                            <input type="text" name="name" :value="selectedProgram ? selectedProgram.name : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-ink-800 mb-1">Approved Intake Seats</label>
                                <input type="number" name="intake_seats" :value="selectedProgram ? selectedProgram.intake_seats : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-mono" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ink-800 mb-1">Semester Tuition Fee</label>
                                <input type="text" name="tuition_semester" :value="selectedProgram ? selectedProgram.tuition_semester : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-mono" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-ink-800 mb-1">Degree Duration</label>
                                <input type="text" name="duration" :value="selectedProgram ? selectedProgram.duration : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ink-800 mb-1">Graduation Credit Hours</label>
                                <input type="number" name="credit_hours" :value="selectedProgram ? selectedProgram.credit_hours : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-mono" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Statutory Accreditation Standing</label>
                            <input type="text" name="accreditation" :value="selectedProgram ? selectedProgram.accreditation : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>

                        <!-- Read-only Statutory Policy Note -->
                        <div class="p-4 rounded-xl bg-surface-subtle border border-border-default space-y-1 text-xs">
                            <span class="text-[10px] font-mono uppercase text-ink-400 font-bold block">Eligibility Baseline</span>
                            <div class="text-ink-800" x-text="selectedProgram ? selectedProgram.min_eligibility : ''"></div>
                        </div>

                        <div class="pt-4 border-t border-border-subtle flex items-center justify-end gap-2">
                            <button type="button" @click="drawerOpen = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold">Save Program Updates</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Program Modal -->
        <div 
            x-show="addModalOpen" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-surface rounded-2xl border border-border-default p-6 max-w-xl w-full shadow-elevated space-y-5" @click.outside="addModalOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Register New Academic Degree</h3>
                    <button type="button" @click="addModalOpen = false" class="text-ink-400 hover:text-ink-700 text-sm">✕</button>
                </div>

                <form action="{{ route('uni_admin.programs.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Degree Title</label>
                        <input type="text" name="name" placeholder="e.g. BS Cyber Security" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Program Code</label>
                            <input type="text" name="code" placeholder="NUST-SEECS-BSCYS" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Level</label>
                            <select name="level" class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans">
                                <option value="Undergraduate">Undergraduate</option>
                                <option value="Postgraduate">Postgraduate</option>
                                <option value="Doctoral">Doctoral</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Department</label>
                            <select name="department" class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans">
                                @foreach($departments as $d)
                                    <option value="{{ $d['acronym'] }}">{{ $d['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Duration</label>
                            <input type="text" name="duration" placeholder="4 Years (8 Semesters)" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Credit Hours</label>
                            <input type="number" name="credit_hours" placeholder="134" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Accreditation Council</label>
                            <input type="text" name="accreditation" placeholder="NCEAC Category W" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Intake Capacity (Seats)</label>
                            <input type="number" name="intake_seats" placeholder="90" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Semester Tuition Fee</label>
                            <input type="text" name="tuition_semester" placeholder="PKR 195,000" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold">Publish Program</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.university-admin>

