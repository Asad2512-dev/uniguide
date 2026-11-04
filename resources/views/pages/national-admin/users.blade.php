<x-layouts.national-admin title="User Management — National Directory & RBAC">
    <x-ui.page-header 
        title="Student and Admin User Management"
        description="National role-based access control (RBAC), multi-factor enforcement, and identity management across candidates, university registrars, and federal commissioners."
        badge="Unified Identity Registry"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
                    ['label' => 'User Management']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="outline" size="sm" icon="file-down">
                Export Audit Roster
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

    <div 
        x-data="{
            search: '',
            roleFilter: 'all',
            matches(u) {
                const s = this.search.toLowerCase();
                const matchesSearch = !s || u.name.toLowerCase().includes(s) || u.email.toLowerCase().includes(s) || u.scope.toLowerCase().includes(s);
                const matchesRole = this.roleFilter === 'all' || u.role.toLowerCase().includes(this.roleFilter.toLowerCase());
                return matchesSearch && matchesRole;
            }
        }"
        class="space-y-4"
    >
        <!-- Filter Bar -->
        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-card flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <x-icon name="search" class="w-4 h-4 text-ink-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                <input 
                    type="text" 
                    x-model="search"
                    placeholder="Search by user name, email, or institutional scope..."
                    class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border border-border-default bg-canvas focus:bg-surface focus:outline-none focus:ring-2 focus:ring-brand-500 transition-colors"
                />
            </div>

            <div class="flex items-center gap-3">
                <select 
                    x-model="roleFilter"
                    class="text-xs rounded-xl border border-border-default bg-surface px-3 py-2 text-ink-700 focus:outline-none focus:ring-2 focus:ring-brand-500"
                >
                    <option value="all">All Access Roles</option>
                    <option value="Federal">Federal Commissioners</option>
                    <option value="University">University Administrators</option>
                    <option value="Applicant">Applicant Candidates</option>
                </select>
            </div>
        </div>

        <!-- Users Table -->
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" aria-label="National User Directory">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th scope="col" class="py-3.5 px-4 font-semibold">User Identity & Email</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Assigned Role</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Institutional Scope</th>
                            <th scope="col" class="py-3.5 px-4 text-center font-semibold">2FA Security</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Member Since</th>
                            <th scope="col" class="py-3.5 px-4 text-right font-semibold">Account State</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        @foreach($users as $usr)
                            <tr 
                                x-show="matches({{ json_encode($usr) }})"
                                class="hover:bg-surface-subtle/80 transition-colors"
                            >
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-ink-950 font-heading block">{{ $usr['name'] }}</span>
                                    <span class="text-[11px] font-mono text-ink-400 block">{{ $usr['email'] }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-ink-800">{{ $usr['role'] }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[11px] font-mono bg-surface-subtle text-brand-700 border border-border-subtle">
                                        {{ $usr['scope'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 text-[11px] font-mono font-bold text-emerald-700">
                                        <x-icon name="shield-check" class="w-3.5 h-3.5 text-emerald-600" />
                                        {{ $usr['two_factor'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-ink-500 text-[11px]">
                                    {{ $usr['created_at'] }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $usr['status'] }}
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

