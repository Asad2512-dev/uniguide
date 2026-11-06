<x-layouts.national-admin title="Regulatory Broadcasts & Alerts — National Dispatch">
    <x-ui.page-header 
        title="Regulatory Broadcasts & Notifications"
        description="National communication engine for dispatching statutory circulars, admission deadline extensions, and compliance notices to institutions and candidates."
        badge="Federal Broadcast Active"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Platform Administration', 'url' => route('national_admin.dashboard')],
                    ['label' => 'Broadcast Notifications']
                ]"
            />
        </x-slot:breadcrumbs>

        <x-slot:actions>
            <x-ui.button variant="primary" size="sm" icon="send" @click="$dispatch('open-broadcast-modal')">
                Compose Regulatory Broadcast
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

    <div x-data="{ showModal: false }" @open-broadcast-modal.window="showModal = true" class="space-y-6">
        <!-- Broadcasts Roster -->
        <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
            <div class="overflow-x-auto">
                <table data-mobile-table class="w-full text-left border-collapse" aria-label="Regulatory Broadcasts Roster">
                    <thead>
                        <tr class="border-b border-border-default bg-surface-subtle text-xs sm:text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                            <th scope="col" class="py-3.5 px-4 font-semibold">Broadcast Title & Category</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Target Audience</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Channels</th>
                            <th scope="col" class="py-3.5 px-4 font-semibold">Dispatched</th>
                            <th scope="col" class="py-3.5 px-4 text-right font-semibold">Delivery State</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs">
                        @foreach($notifications as $notif)
                            <tr class="hover:bg-surface-subtle/80 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-ink-950 font-heading block">{{ $notif['title'] }}</span>
                                    <span class="text-xs sm:text-[11px] font-mono text-brand-700 block">{{ $notif['category'] }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-medium text-ink-800">
                                    {{ $notif['target_audience'] }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($notif['channels'] as $ch)
                                            <span class="inline-flex px-2 py-0.5 rounded text-xs sm:text-[10px] font-mono bg-surface-subtle text-ink-700 border border-border-subtle">
                                                {{ $ch }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-ink-500 text-xs sm:text-[11px]">
                                    {{ $notif['dispatched_at'] }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs sm:text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $notif['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Compose Broadcast Modal (Alpine.js) -->
        <div 
            x-show="showModal" 
            x-modal-focus="showModal"
            role="dialog"
            aria-modal="true"
            aria-label="Compose regulatory broadcast"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-ink-950/60 backdrop-blur-xs"
            @keydown.escape.window="showModal = false"
        >
            <div 
                class="bg-surface rounded-2xl border border-border-default shadow-elevated max-h-[calc(100dvh-2rem)] overflow-y-auto w-full max-w-lg"
                @click.away="showModal = false"
            >
                <div class="px-6 py-4 border-b border-border-default bg-surface-subtle flex items-center justify-between">
                    <h3 class="font-heading font-bold text-sm text-ink-950">Compose Regulatory Broadcast</h3>
                    <button type="button" @click="showModal = false" class="text-ink-400 hover:text-ink-700 cursor-pointer">
                        <x-icon name="x" class="w-4 h-4" />
                    </button>
                </div>

                <form method="POST" action="{{ route('national_admin.notifications.create') }}" class="p-4 sm:p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Notification Subject / Title</label>
                        <input 
                            type="text" 
                            name="title" 
                            required
                            placeholder="e.g. Directive: Equivalence Deadlines Fall 2026"
                            class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3.5 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Category</label>
                            <select 
                                name="category" 
                                required
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500"
                            >
                                <option value="Platform Announcement">Platform Announcement</option>
                                <option value="National Admissions Alert">National Admissions Alert</option>
                                <option value="Inspection Notice">Inspection Notice</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Target Audience</label>
                            <select 
                                name="target_audience" 
                                required
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500"
                            >
                                <option value="All Chartered Universities & Candidates">All Universities & Candidates</option>
                                <option value="All Chartered Universities">Universities Only</option>
                                <option value="FSc Final Year Candidates">All Student Candidates</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-border-subtle flex justify-end gap-3">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-semibold text-ink-600 hover:text-ink-800">
                            Cancel
                        </button>
                        <x-ui.button type="submit" variant="primary" size="sm" icon="send">
                            Dispatch Broadcast
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.national-admin>

