<x-layouts.national-admin title="System Settings & Audit Trail — Federal Controls">
    <x-ui.page-header 
        title="System Settings and Audit Logs"
        description="Federal infrastructure limits, crawler concurrency parameters, immutable audit retention, and supervisory activity trails."
        badge="Regulatory Compliance Tier"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
                    ['label' => 'Settings & Audit Logs']
                ]"
            />
        </x-slot:breadcrumbs>
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

    <div class="space-y-8">
        <!-- Settings Form -->
        <form method="POST" action="{{ route('national_admin.settings.update') }}" class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-6">
            @csrf

            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Federal Infrastructure & Concurrency Limits</h3>
                    <p class="text-xs text-ink-500 font-sans">Throttle crawler workers and configure dual-approval statutory safeguards.</p>
                </div>
                <x-ui.button type="submit" variant="primary" size="sm" icon="save">
                    Save System Settings
                </x-ui.button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Crawler Worker Concurrency</label>
                    <input 
                        type="number" 
                        name="crawler_concurrency" 
                        value="{{ $settings['crawler_concurrency'] ?? 8 }}"
                        min="1" 
                        max="32"
                        class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3.5 py-2 text-ink-800 font-mono focus:outline-none focus:ring-2 focus:ring-brand-500"
                    />
                    <span class="text-[10px] text-ink-400 mt-1 block">Parallel workers scraping institutional repositories simultaneously.</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Public API Rate Limit (req/min)</label>
                    <input 
                        type="number" 
                        name="api_rate_limit_per_minute" 
                        value="{{ $settings['api_rate_limit_per_minute'] ?? 600 }}"
                        min="60" 
                        max="5000"
                        class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3.5 py-2 text-ink-800 font-mono focus:outline-none focus:ring-2 focus:ring-brand-500"
                    />
                    <span class="text-[10px] text-ink-400 mt-1 block">Maximum requests allowed per IP address before 429 throttling.</span>
                </div>
            </div>

            <div class="pt-4 border-t border-border-subtle space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="require_hec_commissioner_approval_for_fee_hike" 
                        value="1"
                        {{ !empty($settings['require_hec_commissioner_approval_for_fee_hike']) ? 'checked' : '' }}
                        class="rounded border-border-default text-brand-600 focus:ring-brand-500"
                    />
                    <div>
                        <span class="text-xs font-semibold text-ink-950">Require Federal Commissioner Signature on Tuition Hikes > 15%</span>
                        <span class="text-[11px] text-ink-500 block">Enforces statutory approval before any fee increase exceeding national inflation benchmarks is displayed.</span>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input 
                        type="checkbox" 
                        name="immutable_audit_logging_enabled" 
                        value="1"
                        {{ !empty($settings['immutable_audit_logging_enabled']) ? 'checked' : '' }}
                        class="rounded border-border-default text-brand-600 focus:ring-brand-500"
                    />
                    <div>
                        <span class="text-xs font-semibold text-ink-950">Immutable Federal Audit Logging (WORM Storage)</span>
                        <span class="text-[11px] text-ink-500 block">Logs every data mutation with cryptographic timestamping to ensure statutory integrity.</span>
                    </div>
                </label>
            </div>
        </form>

        <!-- Immutable Audit Trail Table -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Immutable Supervisory Audit Trail</h3>
                    <p class="text-xs text-ink-500 font-sans">Tamper-evident chronological record of regulatory actions.</p>
                </div>
                <span class="text-xs font-mono text-ink-400">Section 14 HEC Act</span>
            </div>

            <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th class="py-3.5 px-4 font-semibold">Timestamp (PKT)</th>
                            <th class="py-3.5 px-4 font-semibold">Actor & Role</th>
                            <th class="py-3.5 px-4 font-semibold">Regulatory Action</th>
                            <th class="py-3.5 px-4 text-right font-semibold">Client IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        @foreach($audit_logs as $log)
                            <tr class="hover:bg-surface-subtle/80 transition-colors">
                                <td class="py-3.5 px-4 font-mono text-ink-500 text-[11px]">
                                    {{ $log['timestamp'] }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-ink-950 font-heading block">{{ $log['user'] }}</span>
                                    <span class="text-[11px] font-mono text-brand-700 block">{{ $log['role'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 text-ink-800 font-medium">
                                    {{ $log['action'] }}
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono text-ink-500 text-[11px]">
                                    {{ $log['ip'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.national-admin>
