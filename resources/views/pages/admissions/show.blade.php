<x-layouts.public 
    :title="$admission['title'] . ' — UniGuide Pakistan'"
    :description="$admission['summary']"
>
    <div x-data="{ reminderModalOpen: false, reminderSet: false }" class="min-h-screen bg-canvas pb-24">

        <!-- Header Banner -->
        <header class="bg-surface border-b border-border-default/80">
            <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Admissions', 'url' => route('admissions.index')],
                        ['label' => $admission['university_acronym'], 'url' => route('universities.show', $admission['university_slug'])],
                        ['label' => $admission['title'], 'url' => route('admissions.show', $admission['slug'])]
                    ]" />
                </div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="   text-xs font-semibold  text-brand-700   font-sans uppercase font-mono">
                                {{ $admission['session'] }}
                            </span>
                            <x-domain.admission-status :status="$admission['status']" size="sm" />
                            <span class="text-xs font-sans text-ink-500">
                                {{ $admission['province_label'] }}
                            </span>
                            <span class="text-xs font-mono text-ink-600">
                                Verified: {{ $admission['verified_at'] }}
                            </span>
                        </div>

                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                            {{ $admission['title'] }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs sm:text-sm font-sans text-ink-600 mt-2">
                            <a 
                                href="{{ route('universities.show', $admission['university_slug']) }}" 
                                class="font-semibold text-brand-600 hover:text-brand-700 hover:underline flex items-center gap-1.5"
                            >
                                <x-icon name="building-2" class="w-4 h-4" />
                                <span>{{ $admission['university_name'] }}</span>
                            </a>
                            <span>•</span>
                            <span>{{ implode(', ', $admission['campuses']) }}</span>
                        </div>
                    </div>

                    <!-- Right CTAs: Save, Set Reminder, Apply Official -->
                    <div class="flex items-center flex-wrap gap-2.5 shrink-0">
                        <x-domain.save-item type="admission" :slug="$admission['slug']" />

                        <button 
                            type="button" 
                            @click="reminderModalOpen = true"
                            :class="reminderSet ? 'bg-success-50 border-success-300 text-success-800' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border text-xs font-semibold font-sans shadow-2xs transition-smooth cursor-pointer"
                        >
                            <x-icon name="bell" class="w-4 h-4" ::class="reminderSet ? 'text-success-600 fill-success-600' : 'text-ink-500'" />
                            <span x-text="reminderSet ? 'Alert Active' : 'Set Deadline Reminder'"></span>
                        </button>

                        <a 
                            href="{{ $admission['official_url'] }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-ink-900 hover:bg-brand-600 text-white text-xs font-semibold font-sans shadow-xs transition-smooth"
                        >
                            <span>Official Portal</span>
                            <x-icon name="external-link" class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Grid -->
        <main class="landing-container mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left 2 Cols: Comprehensive Admissions Dossier -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- High-Level Dates Strip -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                            <span class="block text-xs font-sans text-ink-500 font-medium">Final Deadline</span>
                            <span class="font-heading font-extrabold text-base sm:text-lg text-ink-950 mt-0.5 block">
                                {{ $admission['deadline'] }}
                            </span>
                            <span class="text-xs sm:text-[10px] text-brand-700 font-semibold font-sans">Strict Cutoff</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                            <span class="block text-xs font-sans text-ink-500 font-medium">Entry Test Window</span>
                            <span class="font-heading font-extrabold text-xs sm:text-sm text-ink-950 mt-0.5 block truncate">
                                {{ $admission['entry_test'] }}
                            </span>
                            <span class="text-xs sm:text-[10px] text-ink-600 font-sans">Required Examination</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                            <span class="block text-xs font-sans text-ink-500 font-medium">Merit Announcement</span>
                            <span class="font-heading font-extrabold text-base sm:text-lg text-brand-700 mt-0.5 block">
                                {{ $admission['merit_date'] }}
                            </span>
                            <span class="text-xs sm:text-[10px] text-ink-600 font-sans">First Merit List</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                            <span class="block text-xs font-sans text-ink-500 font-medium">Term Commencement</span>
                            <span class="font-heading font-extrabold text-base sm:text-lg text-ink-950 mt-0.5 block">
                                {{ $admission['classes_date'] }}
                            </span>
                            <span class="text-xs sm:text-[10px] text-ink-600 font-sans">Orientation & Classes</span>
                        </div>
                    </div>

                    <!-- Admission Summary & Statutory Foundation -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                        <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="info" class="w-5 h-5 text-brand-600" />
                            <span>Campaign Overview & Intake Scope</span>
                        </h2>
                        <p class="text-sm sm:text-base text-ink-700 font-sans leading-relaxed">
                            {{ $admission['summary'] }}
                        </p>
                        <div class="pt-2">
                            <x-domain.source-citation 
                                :source="$admission['source_citation']"
                                :url="$admission['official_url']"
                                :verifiedAt="$admission['verified_at']"
                            />
                        </div>
                    </div>

                    <!-- Admission Schedule & Milestone Timeline -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-6">
                        <div>
                            <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                                <x-icon name="calendar-clock" class="w-5 h-5 text-brand-600" />
                                <span>Official Admission Timeline</span>
                            </h2>
                            <p class="text-xs text-ink-500 font-sans mt-0.5">
                                Progression milestones from registration commencement to university orientation.
                            </p>
                        </div>

                        <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-border-default">
                            @foreach($admission['timeline_steps'] as $step)
                                <div class="relative">
                                    <div class="absolute -left-6 top-1 w-3.5 h-3.5 rounded-full border-2 border-brand-600 bg-surface"></div>
                                    <span class="inline-block text-xs sm:text-[11px] font-mono font-bold text-brand-700 uppercase tracking-wider mb-0.5">
                                        {{ $step['date'] }}
                                    </span>
                                    <h3 class="font-heading font-bold text-sm text-ink-950">
                                        {{ $step['title'] }}
                                    </h3>
                                    <p class="text-xs text-ink-600 font-sans mt-0.5 leading-relaxed">
                                        {{ $step['desc'] }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Eligibility Criteria & Merit Weightage Formula -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-5">
                        <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="check-square" class="w-5 h-5 text-brand-600" />
                            <span>Eligibility Criteria & Weightage Formula</span>
                        </h2>

                        <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle space-y-2">
                            <span class="text-xs font-semibold text-ink-500 font-sans uppercase tracking-wider block">Minimum Educational Eligibility</span>
                            <p class="text-xs sm:text-sm text-ink-800 font-sans leading-relaxed">
                                {{ $admission['eligibility_summary'] }}
                            </p>
                        </div>

                        <div class="p-4 rounded-xl bg-brand-50/60 border border-brand-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <span class="text-xs font-bold text-brand-900 font-sans block mb-0.5">Official Merit Aggregate Formula</span>
                                <span class="text-xs font-mono font-bold text-brand-700 block">
                                    {{ $admission['weightage_formula'] }}
                                </span>
                            </div>
                            <x-ui.button variant="primary" size="xs" :href="route('preview.student')" iconRight="arrow-right">
                                Calculate My Merit
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Available Programs Offered in this Intake -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                                <x-icon name="graduation-cap" class="w-5 h-5 text-brand-600" />
                                <span>Offered Degree Programs</span>
                            </h2>
                            <span class="text-xs font-mono font-semibold text-ink-500">
                                {{ count($admission['available_programs']) }} Disciplines
                            </span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                            @foreach($admission['available_programs'] as $progTitle)
                                <div class="flex items-center gap-2.5 p-3 rounded-xl bg-surface-subtle border border-border-subtle text-xs font-sans text-ink-800">
                                    <x-icon name="check-circle-2" class="w-4 h-4 text-brand-600 shrink-0" />
                                    <span class="font-medium">{{ $progTitle }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step-by-Step Application Instructions -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-5">
                        <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="list-ordered" class="w-5 h-5 text-brand-600" />
                            <span>How to Apply Step-by-Step</span>
                        </h2>

                        <div class="space-y-3">
                            @foreach($admission['application_steps'] as $idx => $inst)
                                <div class="flex items-start gap-3.5 p-3.5 rounded-xl border border-border-default bg-surface text-xs sm:text-sm font-sans text-ink-800">
                                    <span class="w-6 h-6 rounded-full bg-brand-50 text-brand-700 border border-brand-200/60 font-mono font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                        {{ $idx + 1 }}
                                    </span>
                                    <p class="leading-relaxed">{{ $inst }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Required Document Checklist for this Campaign -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between">
                            <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                                <x-icon name="clipboard-check" class="w-5 h-5 text-brand-600" />
                                <span>Required Submission Documents</span>
                            </h2>
                            <a href="{{ route('admissions.checklist') }}" class="text-xs font-semibold text-brand-600 hover:underline">
                                Full Checklist →
                            </a>
                        </div>

                        <ul class="space-y-2.5 text-xs sm:text-sm font-sans text-ink-700">
                            @foreach($admission['required_documents'] as $doc)
                                <li class="flex items-start gap-2.5 p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                    <x-icon name="file-text" class="w-4 h-4 text-brand-600 shrink-0 mt-0.5" />
                                    <span>{{ $doc }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <!-- Right 1 Col: Urgent Countdown & Official Contacts -->
                <aside class="space-y-6">

                    <!-- Deadline Countdown Box -->
                    <div class="bg-surface rounded-2xl border border-brand-200 shadow-elevated p-4 sm:p-6 space-y-4 ring-1 ring-brand-500/10">
                        <div class="flex items-center justify-between">
                            <span class="text-xs sm:text-[11px] font-bold uppercase tracking-wider text-brand-700 font-sans">
                                Admission Cutoff
                            </span>
                            <span class="px-2 py-0.5 rounded text-xs sm:text-[10px] font-mono font-bold bg-brand-50 text-brand-700 border border-brand-200/60 uppercase">
                                {{ $admission['session'] }}
                            </span>
                        </div>

                        <div>
                            <span class="block text-2xl font-heading font-extrabold text-ink-950">
                                {{ $admission['deadline'] }}
                            </span>
                            <span class="text-xs text-ink-500 font-sans mt-0.5 block">
                                Confirm the closing time with the university.
                            </span>
                        </div>

                        <div class="space-y-2.5 border-t border-border-subtle pt-4 text-xs font-sans">
                            <div class="flex items-center justify-between text-ink-600">
                                <span>Processing Fee:</span>
                                <span class="font-mono font-bold text-ink-900">{{ $admission['application_fee'] }}</span>
                            </div>
                            <div class="flex items-center justify-between text-ink-600">
                                <span>Test Schedule:</span>
                                <span class="font-semibold text-ink-900">{{ $admission['entry_test'] }}</span>
                            </div>
                        </div>

                        <a 
                            href="{{ $admission['official_url'] }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="block w-full text-center py-3 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-sans text-xs font-semibold shadow-xs transition-smooth"
                        >
                            Open Official Application Portal ↗
                        </a>
                    </div>

                    <!-- Direct University Contact & Helpdesk -->
                    <div class="bg-surface rounded-2xl border border-border-default p-4 sm:p-6 shadow-2xs space-y-4">
                        <h3 class="font-heading font-bold text-sm text-ink-950 flex items-center gap-2">
                            <x-icon name="phone-call" class="w-4 h-4 text-brand-600" />
                            <span>Institutional Admissions Desk</span>
                        </h3>

                        <div class="space-y-3 text-xs font-sans text-ink-600">
                            <div>
                                <span class="block text-xs sm:text-[11px] text-ink-600 font-medium mb-0.5">Institution Address</span>
                                <span class="text-ink-800">{{ $university['address'] ?? 'Official Admissions Secretariat' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs sm:text-[11px] text-ink-600 font-medium mb-0.5">Helpline Telephone</span>
                                <a href="tel:{{ $university['phone'] ?? '' }}" class="font-mono text-brand-600 hover:underline">
                                    {{ $university['phone'] ?? '+92 51 9085 1000' }}
                                </a>
                            </div>
                            <div>
                                <span class="block text-xs sm:text-[11px] text-ink-600 font-medium mb-0.5">Admissions Email Desk</span>
                                <a href="mailto:{{ $university['email'] ?? 'admissions@university.edu.pk' }}" class="text-brand-600 hover:underline">
                                    {{ $university['email'] ?? 'admissions@university.edu.pk' }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- View Parent Institutional Record -->
                    <div class="bg-surface rounded-2xl border border-border-default p-4 sm:p-6 shadow-2xs space-y-3">
                        <span class="text-xs font-semibold text-ink-600 uppercase tracking-wider font-sans">University Registry</span>
                        <h4 class="font-heading font-bold text-sm text-ink-950">
                            {{ $admission['university_name'] }}
                        </h4>
                        <p class="text-xs text-ink-600 font-sans">
                            Review full fee breakdown, scholarship trusts, and audit accreditations.
                        </p>
                        <x-ui.button variant="outline" size="sm" :href="route('universities.show', $admission['university_slug'])" class="w-full">
                            University Profile & Campuses
                        </x-ui.button>
                    </div>

                </aside>

            </div>
        </main>

        <div x-show="reminderModalOpen" x-cloak @keydown.escape.window="reminderModalOpen = false" class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="reminder-title">
            <div class="w-full max-w-md rounded-xl border border-border-default bg-surface p-6 shadow-modal"><h2 id="reminder-title" class="text-lg font-semibold">Save deadline reminders</h2><p class="mt-3 text-sm text-ink-600">In-app reminders are available seven and two days before the recorded deadline. Passed reminder dates are skipped.</p>
            @if(auth()->user()?->isStudent())<form method="POST" action="{{ route('student.admissions.reminders',$admission['slug']) }}" class="mt-5">@csrf<x-ui.button type="submit">Save in-app reminders</x-ui.button></form>@else<a href="{{ route('auth.login') }}" class="mt-5 block text-brand-700">Sign in to save reminders</a>@endif
            <button type="button" @click="reminderModalOpen=false" class="mt-4 text-sm text-ink-600">Cancel</button></div>
        </div>
    </div>
</x-layouts.public>

