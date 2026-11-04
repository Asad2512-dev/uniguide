<x-layouts.university-admin 
    :title="'Campus & Facilities Management — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <div x-data="{ addModalOpen: false, editModalOpen: false, activeCampus: null }">
        <x-ui.page-header 
            title="Campus & Infrastructure Management"
            description="Manage flagship residential campuses and regional constituent colleges across Islamabad, Rawalpindi, and Karachi."
            badge="{{ count($campuses) }} Active Campuses"
            badgeVariant="brand"
        >
            <x-slot:breadcrumbs>
                <x-ui.breadcrumbs 
                    :items="[
                        ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                        ['label' => 'Campuses']
                    ]"
                />
            </x-slot:breadcrumbs>

            <x-slot:actions>
                <x-ui.button variant="primary" size="sm" icon="plus" @click="addModalOpen = true">
                    Add Constituent Campus
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Campuses Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($campuses as $cmp)
                <div class="p-6 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-4 pb-3 border-b border-border-subtle">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-heading font-bold text-base text-ink-950">{{ $cmp['name'] }}</h3>
                                    @if($cmp['is_primary'])
                                        <x-ui.badge variant="brand" size="sm">Main Campus</x-ui.badge>
                                    @endif
                                </div>
                                <span class="text-xs text-ink-500 font-sans block mt-0.5">
                                    {{ $cmp['city'] }}, {{ $cmp['province'] }} • Est. {{ $cmp['established'] }}
                                </span>
                            </div>

                            <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                Active
                            </span>
                        </div>

                        <!-- Campus Infrastructure Specs -->
                        <div class="grid grid-cols-2 gap-3 text-xs font-sans">
                            <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                <span class="text-[10px] text-ink-400 uppercase font-mono block">Land Masterplan</span>
                                <span class="font-bold text-ink-900 font-mono">{{ $cmp['land_area'] }}</span>
                            </div>
                            <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                <span class="text-[10px] text-ink-400 uppercase font-mono block">Student Population</span>
                                <span class="font-bold text-brand-600 font-mono">{{ number_format($cmp['student_capacity']) }} Students</span>
                            </div>
                            <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle col-span-2">
                                <span class="text-[10px] text-ink-400 uppercase font-mono block">Residential Hostel Provision</span>
                                <span class="font-bold text-ink-900 font-mono">{{ $cmp['hostel_capacity'] }}</span>
                            </div>
                        </div>

                        <!-- Facilities Tag Cloud -->
                        <div>
                            <span class="text-[10px] text-ink-400 uppercase font-mono font-bold block mb-1.5">Key Facilities & Labs</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($cmp['facilities'] as $fac)
                                    <span class="px-2 py-0.5 rounded-md bg-surface-subtle border border-border-subtle text-[11px] text-ink-700 font-sans">
                                        {{ $fac }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Leadership & Address -->
                        <div class="pt-3 border-t border-border-subtle text-xs font-sans text-ink-600">
                            <div>Campus Commandant: <strong class="text-ink-900 font-semibold">{{ $cmp['head'] }}</strong></div>
                            <div class="text-[11px] text-ink-400 mt-0.5">{{ $cmp['address'] }}</div>
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-border-subtle flex items-center justify-between text-xs">
                        <span class="font-mono text-ink-400 text-[11px]">
                            {{ $cmp['departments_count'] }} Depts • {{ $cmp['programs_count'] }} Degrees
                        </span>

                        <button 
                            type="button" 
                            @click="activeCampus = @js($cmp); editModalOpen = true"
                            class="px-3 py-1.5 rounded-lg border border-border-default bg-surface hover:bg-surface-subtle text-ink-700 font-semibold transition-smooth cursor-pointer"
                        >
                            Edit Campus Specs
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add Campus Modal -->
        <div 
            x-show="addModalOpen" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-surface rounded-2xl border border-border-default p-6 max-w-lg w-full shadow-elevated space-y-5" @click.outside="addModalOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Add Constituent Campus</h3>
                    <button type="button" @click="addModalOpen = false" class="text-ink-400 hover:text-ink-700 text-sm">✕</button>
                </div>

                <form action="{{ route('uni_admin.campuses.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Campus Name</label>
                        <input type="text" name="name" placeholder="e.g. Quetta Sub-Campus" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">City</label>
                            <input type="text" name="city" placeholder="Quetta" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Province</label>
                            <input type="text" name="province" placeholder="Balochistan" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Land Masterplan Area</label>
                            <input type="text" name="land_area" placeholder="60 Acres" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Student Capacity</label>
                            <input type="number" name="student_capacity" placeholder="2500" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Hostel Capacity Provision</label>
                        <input type="text" name="hostel_capacity" placeholder="800 beds (3 Hostels)" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Campus Head / Commandant</label>
                        <input type="text" name="head" placeholder="Brigadier Tariq Javed" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Postal Address</label>
                        <input type="text" name="address" placeholder="Cantonment Road, Quetta" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                        <button type="button" @click="addModalOpen = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold">Register Campus</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Campus Modal -->
        <div 
            x-show="editModalOpen" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-surface rounded-2xl border border-border-default p-6 max-w-lg w-full shadow-elevated space-y-5" @click.outside="editModalOpen = false">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Update Campus Specifications</h3>
                    <button type="button" @click="editModalOpen = false" class="text-ink-400 hover:text-ink-700 text-sm">✕</button>
                </div>

                <form :action="'{{ route('uni_admin.campuses') }}/' + (activeCampus ? activeCampus.id : '')" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">Campus Name</label>
                        <input type="text" name="name" :value="activeCampus ? activeCampus.name : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">City</label>
                            <input type="text" name="city" :value="activeCampus ? activeCampus.city : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Student Capacity</label>
                            <input type="number" name="student_capacity" :value="activeCampus ? activeCampus.student_capacity : ''" required class="w-full px-3 py-2 rounded-xl border border-border-default text-xs font-sans" />
                        </div>
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.university-admin>

