<x-layouts.national-admin title="Chartered University Registry — National Directory">
    <x-ui.page-header 
        title="National University Registry"
        description="Master registry of all chartered higher education institutions under federal and provincial legislative acts."
        :badge="count($universities) . ' Registry Records'"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Platform Administration', 'url' => route('national_admin.dashboard')],
                    ['label' => 'University Registry']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="file-down">
                Export National Gazette
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" icon="plus" href="{{ route('national_admin.approvals') }}">
                Charter Applications
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

    <!-- Statistics Overview Strip -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-xs sm:text-[11px] font-mono uppercase text-ink-500 block mb-1">Total Chartered</span>
            <span class="font-heading font-extrabold text-2xl text-ink-950">{{ count($universities) }}</span>
            <span class="text-xs sm:text-[11px] text-emerald-700 mt-1 flex items-center gap-1 font-medium">
                <x-icon name="check" class="w-3.5 h-3.5" /> Verification tracked per record
            </span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-xs sm:text-[11px] font-mono uppercase text-ink-500 block mb-1">Public Sector</span>
            <span class="font-heading font-extrabold text-2xl text-brand-700">{{ collect($universities)->where('sector', 'Public')->count() }}</span>
            <span class="text-xs sm:text-[11px] text-ink-500 mt-1 block">Federal and provincial institutions</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-xs sm:text-[11px] font-mono uppercase text-ink-500 block mb-1">Private Sector</span>
            <span class="font-heading font-extrabold text-2xl text-indigo-700">{{ collect($universities)->where('sector', 'Private')->count() }}</span>
            <span class="text-xs sm:text-[11px] text-ink-500 mt-1 block">Private institutions in registry</span>
        </div>
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card">
            <span class="text-xs sm:text-[11px] font-mono uppercase text-ink-500 block mb-1">Verified Records</span>
            <span class="font-heading font-extrabold text-2xl text-emerald-700">{{ collect($universities)->where('status', 'Verified')->count() }}</span>
            <span class="text-xs sm:text-[11px] text-ink-500 mt-1 block">Independent record review completed</span>
        </div>
    </div>

    <!-- Master Registry Filter & Interactive Table (Alpine.js) -->
    <div 
        x-data="{
            search: '',
            sectorFilter: 'all',
            provinceFilter: 'all',
            selectedUni: null,
            matches(uni) {
                const s = this.search.toLowerCase();
                const matchesSearch = !s || uni.name.toLowerCase().includes(s) || uni.acronym.toLowerCase().includes(s) || uni.charter_type.toLowerCase().includes(s);
                const matchesSector = this.sectorFilter === 'all' || uni.sector.toLowerCase() === this.sectorFilter.toLowerCase();
                const matchesProvince = this.provinceFilter === 'all' || uni.province.toLowerCase().includes(this.provinceFilter.toLowerCase());
                return matchesSearch && matchesSector && matchesProvince;
            }
        }"
        class="space-y-4"
    >
        <!-- Filter Controls -->
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <x-icon name="search" class="w-4 h-4 text-ink-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Search chartered university, acronym, or legislative act..."
                    class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-border-default bg-canvas focus:bg-surface focus:outline-none focus:ring-2 focus:ring-brand-500 transition-colors"
                />
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <select 
                    x-model="sectorFilter"
                    class="text-xs rounded-xl border border-border-default bg-surface px-3 py-2 text-ink-700 focus:outline-none focus:ring-2 focus:ring-brand-500"
                >
                    <option value="all">All Sectors</option>
                    <option value="Public">Public Sector</option>
                    <option value="Private">Private Sector</option>
                    <option value="Chartered">Chartered Autonomous</option>
                </select>

                <select 
                    x-model="provinceFilter"
                    class="text-xs rounded-xl border border-border-default bg-surface px-3 py-2 text-ink-700 focus:outline-none focus:ring-2 focus:ring-brand-500"
                >
                    <option value="all">All Jurisdictions</option>
                    <option value="Islamabad">Islamabad (ICT)</option>
                    <option value="Punjab">Punjab</option>
                </select>
            </div>
        </div>

        <!-- Registry Table -->
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table data-mobile-table class="registry-table w-full text-left border-collapse" aria-label="Chartered University Registry">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-xs sm:text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th scope="col" class="py-3.5 px-4 font-semibold">Institution Name & Legislative Act</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Sector & Region</th>
                            <th scope="col" class="py-3.5 px-4 text-center font-semibold">Campuses</th>
                            <th scope="col" class="py-3.5 px-4 text-center font-semibold">Programs</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Last Audit</th>
                            <th scope="col" class="py-3.5 px-4 text-right font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        @foreach($universities as $uni)
                            <tr 
                                x-show="matches({{ json_encode($uni) }})"
                                class="hover:bg-surface-subtle/80 transition-colors"
                            >
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-brand-50 border border-brand-200/80 text-brand-700 flex items-center justify-center font-bold text-xs shrink-0 font-heading">
                                            {{ $uni['acronym'] }}
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('national_admin.universities') }}" class="font-heading font-bold text-ink-950 hover:text-brand-700 transition-colors block truncate">
                                                {{ $uni['name'] }}
                                            </a>
                                            <span class="text-xs sm:text-[11px] font-mono text-ink-500 block truncate">
                                                {{ $uni['charter_type'] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-ink-800 block">{{ $uni['sector'] }}</span>
                                    <span class="text-xs sm:text-[11px] text-ink-500 block">{{ $uni['province'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-ink-800">
                                    {{ $uni['active_campuses_count'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-ink-800">
                                    {{ $uni['active_programs_count'] }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-ink-600 text-xs sm:text-[11px]">
                                    {{ $uni['last_audit_date'] }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs sm:text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Verified
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.national-admin>

