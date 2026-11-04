<x-layouts.university-admin 
    :title="'Staff Administration & Security Settings — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <x-ui.page-header 
        title="Staff Access Control & Security Settings"
        description="Manage admissions officer permissions, role-based access control (RBAC), multi-factor authentication policies, and regulatory audit trails."
        badge="Enterprise Security Tier"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                    ['label' => 'Staff & Settings']
                ]"
            />
        </x-slot:breadcrumbs>
    </x-ui.page-header>

    <div class="space-y-10">
        <!-- Authorized Staff Roster Card -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Authorized Administrative Staff Roster</h3>
                    <p class="text-xs text-ink-500 font-sans">Institutional personnel with verified credentials to modify program and fee registries.</p>
                </div>
            </div>

            <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th class="py-3 px-4">Staff Member & Email</th>
                            <th class="py-3 px-4">Assigned Role</th>
                            <th class="py-3 px-4">Directorate / Office</th>
                            <th class="py-3 px-4">2FA Security</th>
                            <th class="py-3 px-4 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        @foreach($staff as $st)
                            <tr class="hover:bg-surface-subtle/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-ink-950 block font-heading">{{ $st['name'] }}</span>
                                    <span class="text-[11px] font-mono text-ink-400">{{ $st['email'] }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-ink-900 block">{{ $st['role'] }}</span>
                                    <span class="text-[10px] font-mono text-brand-700 bg-brand-50 px-1.5 py-0.2 rounded border border-brand-200/60 inline-block mt-0.5">
                                        {{ $st['role_badge'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-ink-700">{{ $st['department'] }}</td>
                                <td class="py-3.5 px-4">
                                    @if($st['two_factor_enabled'])
                                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700">
                                            <x-icon name="shield-check" class="w-3.5 h-3.5 text-emerald-600" />
                                            <span>2FA Active</span>
                                        </span>
                                    @else
                                        <span class="text-[11px] text-amber-700 font-medium">Pending Setup</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <x-ui.badge variant="success" size="sm" :dot="true">Active</x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Security & Notification Protocols Form -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-6">
                <form action="{{ route('uni_admin.settings.update') }}" method="POST" class="p-6 sm:p-8 rounded-2xl bg-surface border border-border-default shadow-card space-y-6">
                    @csrf
                    <div class="pb-3 border-b border-border-subtle">
                        <h3 class="font-heading font-bold text-base text-ink-950">Security & Alert Protocols</h3>
                        <p class="text-xs text-ink-500 font-sans mt-0.5">Automated monitoring alerts and multi-factor enforcement.</p>
                    </div>

                    <div class="space-y-4 text-xs font-sans">
                        <label class="flex items-start gap-3 p-3 rounded-xl border border-border-subtle hover:bg-surface-subtle transition-all cursor-pointer">
                            <input type="checkbox" name="two_factor_required" value="1" {{ $settings['two_factor_required'] ? 'checked' : '' }} class="mt-0.5 text-brand-600 rounded" />
                            <div>
                                <strong class="text-ink-900 block font-semibold">Enforce Mandatory Two-Factor Authentication (2FA)</strong>
                                <span class="text-ink-500 text-[11px]">Require all admissions staff to verify login via hardware key or authenticator app.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3 rounded-xl border border-border-subtle hover:bg-surface-subtle transition-all cursor-pointer">
                            <input type="checkbox" name="auto_ai_scrape_notification" value="1" {{ $settings['auto_ai_scrape_notification'] ? 'checked' : '' }} class="mt-0.5 text-brand-600 rounded" />
                            <div>
                                <strong class="text-ink-900 block font-semibold">Instant AI Scraper Alert Dispatches</strong>
                                <span class="text-ink-500 text-[11px]">Send immediate notification whenever automated crawler detects prospectus discrepancies.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 p-3 rounded-xl border border-border-subtle hover:bg-surface-subtle transition-all cursor-pointer">
                            <input type="checkbox" name="weekly_inquiry_digest" value="1" {{ $settings['weekly_inquiry_digest'] ? 'checked' : '' }} class="mt-0.5 text-brand-600 rounded" />
                            <div>
                                <strong class="text-ink-900 block font-semibold">Weekly Admissions Analytics Digest</strong>
                                <span class="text-ink-500 text-[11px]">Weekly executive email summarizing inquiries volume and merit cutoffs trends.</span>
                            </div>
                        </label>

                        <div class="pt-2">
                            <label class="block text-xs font-semibold text-ink-800 mb-1">Central Admissions Notification Email</label>
                            <input 
                                type="email" 
                                name="notify_email" 
                                value="{{ $settings['notify_email'] }}" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-default text-xs font-sans"
                            />
                        </div>
                    </div>

                    <div class="pt-4 border-t border-border-subtle flex items-center justify-end">
                        <x-ui.button type="submit" variant="primary" size="md" icon="check">
                            Save Security Preferences
                        </x-ui.button>
                    </div>
                </form>
            </div>

            <!-- Regulatory Audit Log (6 cols) -->
            <div class="lg:col-span-6 space-y-4">
                <div class="pb-1">
                    <h3 class="font-heading font-bold text-base text-ink-950">Statutory Activity Audit Trail</h3>
                    <p class="text-xs text-ink-500 font-sans">Cryptographically recorded log of all institutional modifications.</p>
                </div>

                <div class="p-5 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                    <div class="space-y-3">
                        @foreach($audit_log as $log)
                            <div class="p-3.5 rounded-xl bg-surface-subtle border border-border-subtle space-y-1 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-ink-900 font-sans">{{ $log['user'] }}</span>
                                    <span class="font-mono text-[10px] text-ink-400">{{ $log['timestamp'] }}</span>
                                </div>
                                <p class="text-ink-700 leading-snug">{{ $log['action'] }}</p>
                                <span class="text-[10px] font-mono text-ink-400 block pt-0.5">IP: {{ $log['ip'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.university-admin>
