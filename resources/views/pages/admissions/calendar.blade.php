<x-layouts.public 
    title="National Admission & Deadline Calendar — UniGuide Pakistan"
    description="Synchronized master calendar of Pakistani university application deadlines, national entry test schedules, scholarship cutoffs, and merit list announcements."
>
    <div x-data="{ 
        viewStyle: 'agenda', // 'agenda' or 'cards'
        activeCategory: '{{ $selectedCategory }}'
    }" class="min-h-screen bg-canvas pb-24">
        
        <!-- Header Strip -->
        <header class="bg-surface border-b border-border-default/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Admissions', 'url' => route('admissions.index')],
                        ['label' => 'Deadline Calendar', 'url' => route('admissions.calendar')]
                    ]" />
                </div>

                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-brand-50 border border-brand-200/60 text-brand-700 text-xs font-semibold font-sans mb-3">
                            <x-icon name="calendar" class="w-3.5 h-3.5 text-brand-600" />
                            <span>Synchronized Intake Milestones</span>
                        </div>
                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                            National Admission & Deadline Calendar
                        </h1>
                        <p class="text-sm sm:text-base text-ink-600 font-sans max-w-2xl mt-1.5 leading-relaxed">
                            Chronological timeline of university application cutoffs, NET/NAT/MDCAT test dates, scholarship submissions, and national merit list publications.
                        </p>
                    </div>

                    <!-- Direct Links: Directory & Checklist -->
                    <div class="flex items-center gap-2.5 shrink-0">
                        <x-ui.button variant="outline" size="sm" :href="route('admissions.index')">
                            ← Admissions Directory
                        </x-ui.button>
                        <x-ui.button variant="primary" size="sm" :href="route('admissions.checklist')">
                            Document Checklist
                        </x-ui.button>
                    </div>
                </div>

                <!-- Month-Based Navigation Bar -->
                <div class="flex items-center gap-2 mt-8 overflow-x-auto pb-1 text-xs font-sans font-semibold">
                    <a 
                        href="{{ route('admissions.calendar', array_merge(request()->except(['month']))) }}" 
                        class="px-4 py-2 rounded-xl border transition-smooth whitespace-nowrap {{ $selectedMonth === null ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                    >
                        All Milestones ({{ $totalEventsCount }})
                    </a>
                    <a 
                        href="{{ route('admissions.calendar', array_merge(request()->except(['month']), ['month' => 6])) }}" 
                        class="px-4 py-2 rounded-xl border transition-smooth whitespace-nowrap {{ $selectedMonth === 6 ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                    >
                        June 2026
                    </a>
                    <a 
                        href="{{ route('admissions.calendar', array_merge(request()->except(['month']), ['month' => 7])) }}" 
                        class="px-4 py-2 rounded-xl border transition-smooth whitespace-nowrap {{ $selectedMonth === 7 ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                    >
                        July 2026 (Peak Deadlines)
                    </a>
                    <a 
                        href="{{ route('admissions.calendar', array_merge(request()->except(['month']), ['month' => 8])) }}" 
                        class="px-4 py-2 rounded-xl border transition-smooth whitespace-nowrap {{ $selectedMonth === 8 ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                    >
                        August 2026 (Merit Lists)
                    </a>
                    <a 
                        href="{{ route('admissions.calendar', array_merge(request()->except(['month']), ['month' => 9])) }}" 
                        class="px-4 py-2 rounded-xl border transition-smooth whitespace-nowrap {{ $selectedMonth === 9 ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                    >
                        September 2026
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-6">
            
            <!-- Category Filter Strip & View Switcher -->
            <div class="bg-surface rounded-2xl border border-border-default p-4 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                
                <!-- Category Filter Pills -->
                <div class="flex items-center gap-2 overflow-x-auto text-xs font-sans font-semibold">
                    <span class="text-ink-400 font-medium mr-1 hidden md:inline">Event Type:</span>
                    <a 
                        href="{{ route('admissions.calendar', array_merge(request()->except(['category']))) }}"
                        class="px-3 py-1.5 rounded-lg border transition-smooth whitespace-nowrap {{ $selectedCategory === 'all' ? 'bg-ink-900 text-white border-ink-900 shadow-2xs' : 'bg-surface-subtle border-border-default text-ink-700 hover:bg-surface' }}"
                    >
                        All Categories
                    </a>
                    <a 
                        href="{{ route('admissions.calendar', array_merge(request()->except(['category']), ['category' => 'deadline'])) }}"
                        class="px-3 py-1.5 rounded-lg border transition-smooth whitespace-nowrap flex items-center gap-1.5 {{ $selectedCategory === 'deadline' ? 'bg-danger-600 text-white border-danger-600 shadow-2xs' : 'bg-surface-subtle border-border-default text-ink-700 hover:bg-surface' }}"
                    >
                        <span class="w-2 h-2 rounded-full {{ $selectedCategory === 'deadline' ? 'bg-white' : 'bg-danger-500' }}"></span>
                        <span>Application Deadlines</span>
                    </a>
                    <a 
                        href="{{ route('admissions.calendar', array_merge(request()->except(['category']), ['category' => 'test'])) }}"
                        class="px-3 py-1.5 rounded-lg border transition-smooth whitespace-nowrap flex items-center gap-1.5 {{ $selectedCategory === 'test' ? 'bg-brand-600 text-white border-brand-600 shadow-2xs' : 'bg-surface-subtle border-border-default text-ink-700 hover:bg-surface' }}"
                    >
                        <span class="w-2 h-2 rounded-full {{ $selectedCategory === 'test' ? 'bg-white' : 'bg-brand-500' }}"></span>
                        <span>Entry Tests</span>
                    </a>
                    <a 
                        href="{{ route('admissions.calendar', array_merge(request()->except(['category']), ['category' => 'merit'])) }}"
                        class="px-3 py-1.5 rounded-lg border transition-smooth whitespace-nowrap flex items-center gap-1.5 {{ $selectedCategory === 'merit' ? 'bg-warning-600 text-white border-warning-600 shadow-2xs' : 'bg-surface-subtle border-border-default text-ink-700 hover:bg-surface' }}"
                    >
                        <span>Merit Lists</span>
                    </a>
                    <a 
                        href="{{ route('admissions.calendar', array_merge(request()->except(['category']), ['category' => 'scholarship'])) }}"
                        class="px-3 py-1.5 rounded-lg border transition-smooth whitespace-nowrap flex items-center gap-1.5 {{ $selectedCategory === 'scholarship' ? 'bg-success-600 text-white border-success-600 shadow-2xs' : 'bg-surface-subtle border-border-default text-ink-700 hover:bg-surface' }}"
                    >
                        <span>Scholarships</span>
                    </a>
                </div>

                <!-- Event Count Indicator -->
                <p class="text-xs font-sans text-ink-600 self-end sm:self-auto shrink-0">
                    Displaying <span class="font-bold text-ink-950">{{ count($events) }}</span> chronological events
                </p>
            </div>

            <!-- Empty State -->
            @if(empty($events))
                <div class="bg-surface rounded-2xl border border-border-default p-12 text-center shadow-2xs">
                    <div class="w-14 h-14 rounded-2xl bg-brand-50 border border-brand-200/60 flex items-center justify-center mx-auto mb-4 text-brand-600">
                        <x-icon name="calendar-x" class="w-7 h-7" />
                    </div>
                    <h3 class="font-heading font-bold text-lg text-ink-950 mb-1.5">No calendar milestones found</h3>
                    <p class="text-sm font-sans text-ink-600 max-w-md mx-auto mb-6">
                        No admission cutoffs or events scheduled for this selected period and category filter.
                    </p>
                    <x-ui.button variant="primary" size="sm" :href="route('admissions.calendar')">
                        View All Dates
                    </x-ui.button>
                </div>
            @else
                <!-- Chronological Agenda List -->
                <div class="space-y-3">
                    @foreach($events as $event)
                        <div class="bg-surface rounded-2xl border border-border-default hover:border-brand-300 hover:shadow-card p-5 transition-smooth flex flex-col md:flex-row md:items-center justify-between gap-5">
                            
                            <!-- Left: Date Box & Event Info -->
                            <div class="flex items-start gap-4 sm:gap-5">
                                <!-- Prominent Date Badge -->
                                <div class="w-14 h-15 rounded-xl border flex flex-col items-center justify-center shrink-0 text-center {{ $event['type'] === 'deadline' ? 'bg-danger-50 border-danger-200/80 text-danger-800' : ($event['type'] === 'test' ? 'bg-brand-50 border-brand-200/80 text-brand-800' : ($event['type'] === 'scholarship' ? 'bg-success-50 border-success-200/80 text-success-800' : 'bg-warning-50 border-warning-200/80 text-warning-800')) }}">
                                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider block">
                                        {{ substr($event['month_name'], 0, 3) }}
                                    </span>
                                    <span class="font-heading font-extrabold text-xl leading-none block">
                                        {{ $event['day'] }}
                                    </span>
                                </div>

                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider {{ $event['type'] === 'deadline' ? 'bg-danger-50 text-danger-700 border border-danger-200/60' : ($event['type'] === 'test' ? 'bg-brand-50 text-brand-700 border border-brand-200/60' : ($event['type'] === 'scholarship' ? 'bg-success-50 text-success-700 border border-success-200/60' : 'bg-warning-50 text-warning-700 border border-warning-200/60')) }}">
                                            {{ $event['category'] }}
                                        </span>
                                        <span class="text-xs font-sans text-ink-500">
                                            {{ $event['time'] }}
                                        </span>
                                    </div>

                                    <h3 class="font-heading font-bold text-base text-ink-950">
                                        @if(!empty($event['admission_slug']))
                                            <a href="{{ route('admissions.show', $event['admission_slug']) }}" class="hover:text-brand-600 transition-colors">
                                                {{ $event['title'] }}
                                            </a>
                                        @else
                                            {{ $event['title'] }}
                                        @endif
                                    </h3>

                                    <p class="text-xs text-brand-700 font-semibold font-sans">
                                        {{ $event['university'] }}
                                    </p>
                                </div>
                            </div>

                            <!-- Right: Status & Action Button -->
                            <div class="flex items-center justify-between md:justify-end gap-4 pt-3 md:pt-0 border-t md:border-t-0 border-border-subtle shrink-0">
                                <span class="text-xs font-mono font-medium text-ink-500">
                                    {{ $event['date'] }}
                                </span>

                                @if(!empty($event['admission_slug']))
                                    <x-ui.button variant="outline" size="xs" :href="route('admissions.show', $event['admission_slug'])" iconRight="arrow-right">
                                        Details
                                    </x-ui.button>
                                @elseif($event['type'] === 'scholarship')
                                    <x-ui.button variant="outline" size="xs" :href="route('scholarships.index')" iconRight="arrow-right">
                                        Scholarships
                                    </x-ui.button>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Calendar Subscription Notification Strip -->
            <div class="p-6 rounded-2xl bg-surface-subtle border border-border-default flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0 border border-brand-200/60">
                        <x-icon name="bell-ring" class="w-5 h-5" />
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-sm text-ink-950">Need automated reminders for your shortlisted deadlines?</h4>
                        <p class="text-xs text-ink-500 font-sans">Receive email and SMS notifications 7 days and 48 hours before official university cutoffs.</p>
                    </div>
                </div>
                <x-ui.button variant="primary" size="sm" :href="route('admissions.index')">
                    Explore All Deadlines
                </x-ui.button>
            </div>

        </main>

    </div>
</x-layouts.public>

