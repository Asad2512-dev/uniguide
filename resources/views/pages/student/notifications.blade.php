<x-layouts.student 
    title="Notifications & Admission Alerts — UniGuide Pakistan"
    description="Real-time alerts on closing registration deadlines, entry-test admit slips, merit announcements, and scholarship openings."
>
    <div class="space-y-8 pb-12">
        
        <!-- Header Strip -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-border-default">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                    <x-icon name="bell" class="w-3.5 h-3.5 text-brand-600" />
                    <span>Admissions Signal Center</span>
                </div>
                <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                    Notifications & Admission Alerts
                </h1>
                <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                    Stay informed on university application deadlines, merit list releases, and test dates.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <form action="{{ route('student.notifications.read_all') }}" method="POST">
                    @csrf
                    <button 
                        type="submit" 
                        class="px-3.5 py-2 rounded-xl border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-800 transition-smooth shadow-2xs cursor-pointer flex items-center gap-1.5"
                    >
                        <x-icon name="check-check" class="w-3.5 h-3.5 text-ink-500" />
                        <span>Mark All as Read</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Filter Topic Tabs -->
        <div class="flex flex-wrap items-center gap-2 pb-2">
            @foreach($filters as $fKey => $fLabel)
                <a 
                    href="{{ route('student.notifications', ['filter' => $fKey]) }}"
                    class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-smooth {{ $activeFilter === $fKey ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                >
                    {{ $fLabel }}
                </a>
            @endforeach
        </div>

        <!-- Notification Feed List -->
        <div class="space-y-3">
            @forelse($notifications as $notif)
                <div class="p-5 rounded-2xl border transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $notif['read'] ? 'bg-surface border-border-default' : 'bg-brand-50/20 border-brand-200/80 shadow-2xs' }}">
                    <div class="flex items-start gap-3.5">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ match($notif['type']) {
                            'deadline' => 'bg-warning-100 text-warning-800',
                            'merit' => 'bg-brand-100 text-brand-800',
                            'scholarship' => 'bg-emerald-100 text-emerald-800',
                            default => 'bg-surface-subtle text-ink-600',
                        } }}">
                            <x-icon :name="match($notif['type']) {
                                'deadline' => 'calendar-clock',
                                'merit' => 'award',
                                'scholarship' => 'coins',
                                default => 'bell',
                            }" class="w-4.5 h-4.5" />
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                <h3 class="font-heading font-bold text-sm text-ink-950">
                                    {{ $notif['title'] }}
                                </h3>
                                @if(!$notif['read'])
                                    <span class="w-2 h-2 rounded-full bg-brand-600 shrink-0"></span>
                                @endif
                                @if(!empty($notif['urgent']))
                                    <span class="text-[10px] font-mono font-bold text-error-700 bg-error-50 px-1.5 py-0.2 rounded">
                                        Urgent
                                    </span>
                                @endif
                            </div>

                            <p class="text-xs text-ink-600 font-sans max-w-2xl leading-relaxed">
                                {{ $notif['message'] }}
                            </p>

                            <span class="text-[11px] text-ink-400 font-mono block pt-0.5">
                                {{ $notif['time_ago'] }}
                            </span>
                        </div>
                    </div>

                    <div class="shrink-0 flex items-center gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-border-subtle">
                        @if(!empty($notif['link']))
                            <a 
                                href="{{ $notif['link'] }}"
                                class="text-xs font-semibold text-brand-600 hover:text-brand-700"
                            >
                                Take Action →
                            </a>
                        @endif

                        @if(!$notif['read'])
                            <form action="{{ route('student.notifications.read', $notif['id']) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-ink-400 hover:text-ink-700 cursor-pointer">
                                    Mark Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 rounded-2xl border border-dashed border-border-default bg-surface text-center space-y-4">
                    <x-icon name="bell-off" class="w-8 h-8 text-ink-400 mx-auto" />
                    <h3 class="font-heading font-bold text-base text-ink-900">No notifications in this category</h3>
                    <p class="text-xs text-ink-500 font-sans max-w-sm mx-auto">
                        All application updates, test dates, and deadline reminders will appear here.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.student>
