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
                    ['label' => 'Platform Administration', 'url' => route('national_admin.dashboard')],
                    ['label' => 'User Management']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button 
                variant="outline" 
                size="sm" 
                icon="file-down"
                @click="$store.toasts?.add({ title: 'Audit Roster Exported', message: 'Federal identity ledger downloaded as verified CSV.', type: 'success' })"
            >
                Export Audit Roster
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" icon="user-plus" @click="$dispatch('open-invite-user')">
                Invite Administrator
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
            showInviteModal: false,
            matches(u) {
                const s = this.search.toLowerCase();
                const matchesSearch = !s || u.name.toLowerCase().includes(s) || u.email.toLowerCase().includes(s) || u.scope.toLowerCase().includes(s);
                const matchesRole = this.roleFilter === 'all' || u.role.toLowerCase().includes(this.roleFilter.toLowerCase());
                return matchesSearch && matchesRole;
            }
        }"
        @open-invite-user.window="showInviteModal = true"
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
                <table data-mobile-table class="w-full text-left border-collapse" aria-label="National User Directory">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-xs sm:text-[11px] font-mono uppercase text-ink-500 tracking-wider">
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
                                    <span class="text-xs sm:text-[11px] font-mono text-ink-600 block">{{ $usr['email'] }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-medium text-ink-800">{{ $usr['role'] }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded text-xs sm:text-[11px] font-mono bg-surface-subtle text-brand-700 border border-border-subtle">
                                        {{ $usr['scope'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="inline-flex items-center gap-1 text-xs sm:text-[11px] font-mono font-bold text-emerald-700">
                                        <x-icon name="shield-check" class="w-3.5 h-3.5 text-emerald-600" />
                                        {{ $usr['two_factor'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-ink-500 text-xs sm:text-[11px]">
                                    {{ $usr['created_at'] }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs sm:text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
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

        <!-- Invite Administrator Modal (Alpine.js) -->
        <div 
            x-show="showInviteModal" 
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink-950/60 backdrop-blur-xs"
            @keydown.escape.window="showInviteModal = false"
        >
            <div 
                class="bg-surface rounded-2xl border border-border-default shadow-elevated w-full max-w-lg overflow-hidden"
                @click.away="showInviteModal = false"
            >
                <div class="px-6 py-4 border-b border-border-default bg-surface-subtle flex items-center justify-between">
                    <div>
                        <h3 class="font-heading font-bold text-sm text-ink-950">Grant Administrative Access</h3>
                        <p class="text-xs sm:text-[11px] text-ink-500 font-sans mt-0.5">Issue verified credentials with mandatory multi-factor authentication.</p>
                    </div>
                    <button type="button" @click="showInviteModal = false" class="text-ink-400 hover:text-ink-700 cursor-pointer">
                        <x-icon name="x" class="w-4 h-4" />
                    </button>
                </div>

                <form method="POST" action="{{ route('national_admin.users.invite') }}" class="p-4 sm:p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Full Name & Title</label>
                        <input 
                            type="text" 
                            name="name" 
                            required
                            placeholder="e.g. Prof. Dr. Arshad Ali"
                            class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3.5 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Official Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            required
                            placeholder="name@hec.gov.pk or registrar@nust.edu.pk"
                            class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3.5 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Assigned Access Role</label>
                            <select 
                                name="role" 
                                required
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500"
                            >
                                <option value="University Administrator">University Administrator</option>
                                <option value="Platform Administrator">Federal Commissioner</option>
                                <option value="Charter Committee Auditor">Charter Committee Auditor</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Institutional Scope</label>
                            <input 
                                type="text" 
                                name="scope" 
                                required
                                placeholder="e.g. NUST Islamabad or Islamabad"
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3.5 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500"
                            />
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-surface-subtle border border-border-subtle">
                        <label class="flex items-start gap-2.5 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="two_factor" 
                                value="1" 
                                checked
                                class="mt-0.5 rounded border-border-default text-brand-600 focus:ring-brand-500"
                            />
                            <div>
                                <span class="text-xs font-semibold text-ink-900 block">Enforce Hardware / TOTP App 2FA</span>
                                <span class="text-xs sm:text-[11px] text-ink-500 block font-sans">Mandates Google Authenticator or YubiKey setup prior to granting dashboard access.</span>
                            </div>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-border-subtle flex justify-end gap-3">
                        <button type="button" @click="showInviteModal = false" class="px-4 py-2 text-xs font-semibold text-ink-600 hover:text-ink-800 cursor-pointer">
                            Cancel
                        </button>
                        <x-ui.button type="submit" variant="primary" size="sm" icon="send">
                            Send Invitation
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.national-admin>
