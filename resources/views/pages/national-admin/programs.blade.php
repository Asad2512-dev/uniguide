<x-layouts.national-admin title="Campus & Program Registry — Accredited Offerings">
    <x-ui.page-header 
        title="Campus and Program Registry"
        description="National master inventory of accredited undergraduate and graduate degree programs across all constituent university campuses."
        badge="{{ number_format($total_programs) }} Degree Offerings"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
                    ['label' => 'Programs & Campuses']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="file-down">
                Export Programs CSV
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

    <!-- Programs Filter & Master Table -->
    <div 
        x-data="{
            search: '',
            levelFilter: 'all',
            matches(p) {
                const s = this.search.toLowerCase();
                const matchesSearch = !s || p.name.toLowerCase().includes(s) || p.university.toLowerCase().includes(s) || p.campus.toLowerCase().includes(s) || p.council.toLowerCase().includes(s);
                const matchesLevel = this.levelFilter === 'all' || p.level.toLowerCase() === this.levelFilter.toLowerCase();
                return matchesSearch && matchesLevel;
            }
        }"
        class="space-y-4"
    >
        <!-- Filter Controls -->
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <x-icon name="search" class="w-4 h-4 text-ink-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Search program, council (PEC, NCEAC), or campus..."
                    class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-border-default bg-canvas focus:bg-surface focus:outline-none focus:ring-2 focus:ring-brand-500 transition-colors"
                />
            </div>

            <div class="flex items-center gap-3">
                <select 
                    x-model="levelFilter"
                    class="text-xs rounded-xl border border-border-default bg-surface px-3 py-2 text-ink-700 focus:outline-none focus:ring-2 focus:ring-brand-500"
                >
                    <option value="all">All Study Levels</option>
                    <option value="Undergraduate">Undergraduate (16-Yr)</option>
                    <option value="Graduate">Postgraduate (MS / PhD)</option>
                </select>
            </div>
        </div>

        <!-- Programs Table -->
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" aria-label="Accredited Degree Programs Inventory">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th scope="col" class="py-3.5 px-4 font-semibold">Degree Program & Level</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Institution & Campus</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Accreditation Body</th>
                            <th scope="col" class="py-3.5 px-4 text-center font-semibold">Quota Seats</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Annual Tuition</th>
                            <th scope="col" class="py-3.5 px-4 text-right font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        @foreach($programs as $p)
                            <tr 
                                x-show="matches({{ json_encode($p) }})"
                                class="hover:bg-surface-subtle/80 transition-colors"
                            >
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-ink-950 font-heading block">{{ $p['name'] }}</span>
                                    <span class="text-[11px] font-mono text-brand-700 block">{{ $p['level'] }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-ink-800 block">{{ $p['university'] }}</span>
                                    <span class="text-[11px] text-ink-500 block">{{ $p['campus'] }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-mono font-medium bg-surface-subtle text-ink-700 border border-border-subtle">
                                        {{ $p['council'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center font-mono font-bold text-ink-900">
                                    {{ $p['quota_seats'] }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-semibold text-ink-900">
                                    {{ $p['annual_fee'] }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $p['status'] }}
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

