<x-layouts.public 
    :title="$program['title'] . ' — ' . $program['university_name'] . ' — UniGuide Pakistan'"
    :description="$program['overview']"
>
    <div x-data="{ 
        saved: false,
        saveProgram() {
            this.saved = !this.saved;
            if (this.saved) {
                $store.toasts.add('Saved ' + '{{ $program['title'] }}' + ' to your shortlist.', 'success');
            } else {
                $store.toasts.add('Removed from shortlist.', 'info');
            }
        }
    }" class="min-h-screen bg-canvas pb-24">
        
        <!-- Breadcrumbs & Program Identity Header -->
        <header class="bg-surface border-b border-border-default/80">
            <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Degree Programs', 'url' => route('programs.index')],
                        ['label' => $program['university']['acronym'] ?? 'University', 'url' => route('universities.show', $program['university_slug'])],
                        ['label' => $program['title'], 'url' => route('programs.show', $program['slug'])]
                    ]" />
                </div>

                <!-- Main Header Details -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200/60 font-sans uppercase font-mono">
                                {{ $program['degree_level_label'] }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-subtle text-ink-700 border border-border-default font-sans">
                                {{ $program['discipline_label'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-xs font-sans text-success-700 font-semibold bg-success-50 px-2 py-0.5 rounded-full border border-success-200/60">
                                <x-icon name="shield-check" class="w-3.5 h-3.5" />
                                <span>{{ $program['council'] }} Accredited</span>
                            </span>
                        </div>

                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                            {{ $program['title'] }}
                        </h1>

                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs sm:text-sm font-sans text-ink-600 mt-2">
                            <a 
                                href="{{ route('universities.show', $program['university_slug']) }}" 
                                class="font-semibold text-brand-600 hover:text-brand-700 hover:underline flex items-center gap-1"
                            >
                                <x-icon name="building-2" class="w-4 h-4" />
                                <span>{{ $program['university_name'] }}</span>
                            </a>
                            <span>•</span>
                            <span>{{ $program['department'] }}</span>
                        </div>
                    </div>

                    <!-- Right CTAs: Save, Calculate Merit, Apply -->
                    <div class="flex items-center flex-wrap gap-2.5 shrink-0">
                        <button 
                            type="button" 
                            @click="saveProgram()"
                            :class="saved ? 'bg-brand-50 border-brand-300 text-brand-700' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border text-xs font-semibold font-sans shadow-2xs transition-smooth cursor-pointer"
                        >
                            <x-icon name="bookmark" class="w-4 h-4" ::class="saved ? 'fill-brand-600 text-brand-600' : ''" />
                            <span x-text="saved ? 'Shortlisted' : 'Save Program'"></span>
                        </button>

                        <a 
                            href="{{ route('preview.student') }}" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold font-sans shadow-xs transition-smooth"
                        >
                            <x-icon name="calculator" class="w-4 h-4" />
                            <span>Calculate My Merit</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body Grid -->
        <main class="landing-container mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left 2 Cols: Academic Details & Curriculum -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- High-Level Metrics Strip -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                            <span class="block text-xs font-sans text-ink-500 font-medium">Closing Merit (Est.)</span>
                            <span class="font-heading font-extrabold text-base sm:text-lg text-brand-700 mt-0.5 block">
                                {{ $program['estimated_closing_merit'] }}
                            </span>
                            <span class="text-xs sm:text-[10px] text-ink-600 font-sans">Fall Batch Cutoff</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                            <span class="block text-xs font-sans text-ink-500 font-medium">Semester Tuition</span>
                            <span class="font-heading font-extrabold text-base sm:text-lg text-ink-950 mt-0.5 block">
                                {{ $program['fee_per_semester'] }}
                            </span>
                            <span class="text-xs sm:text-[10px] text-ink-600 font-sans">Per Semester</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                            <span class="block text-xs font-sans text-ink-500 font-medium">Program Duration</span>
                            <span class="font-heading font-extrabold text-base sm:text-lg text-ink-950 mt-0.5 block">
                                {{ $program['duration'] }}
                            </span>
                            <span class="text-xs sm:text-[10px] text-ink-600 font-sans">{{ $program['credit_hours'] }} Credit Hours</span>
                        </div>
                        <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                            <span class="block text-xs font-sans text-ink-500 font-medium">Study Shift</span>
                            <span class="font-heading font-extrabold text-base sm:text-lg text-ink-950 mt-0.5 block">
                                {{ $program['study_mode'] }}
                            </span>
                            <span class="text-xs sm:text-[10px] text-ink-600 font-sans">{{ $program['shift'] }}</span>
                        </div>
                    </div>

                    <!-- Program Overview -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                        <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="book-open" class="w-5 h-5 text-brand-600" />
                            <span>Program Overview & Focus</span>
                        </h2>
                        <p class="text-sm sm:text-base text-ink-700 font-sans leading-relaxed">
                            {{ $program['overview'] }}
                        </p>
                    </div>

                    <!-- Admission Criteria & Merit Formula -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-5">
                        <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="check-square" class="w-5 h-5 text-brand-600" />
                            <span>Admission Requirements & Merit Weightage</span>
                        </h2>

                        <div class="space-y-3">
                            @foreach($program['admission_criteria'] as $crit)
                                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-surface-subtle border border-border-subtle text-xs sm:text-sm font-sans text-ink-800">
                                    <x-icon name="check-circle-2" class="w-4 h-4 text-brand-600 shrink-0 mt-0.5" />
                                    <span>{{ $crit }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 p-4 rounded-xl bg-brand-50/60 border border-brand-200/60 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <x-icon name="calculator" class="w-5 h-5 text-brand-600 shrink-0" />
                                <div class="text-xs font-sans">
                                    <span class="font-bold text-brand-900 block">Want to know if your marks meet the cutoff?</span>
                                    <span class="text-brand-700">Enter your Matric and FSc marks into our formula engine.</span>
                                </div>
                            </div>
                            <x-ui.button variant="primary" size="xs" :href="route('preview.student')">
                                Calculate
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Curriculum Breakdown -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-5">
                        <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="list-checks" class="w-5 h-5 text-brand-600" />
                            <span>Curriculum Highlights & Progression</span>
                        </h2>

                        <div class="space-y-3">
                            @foreach($program['curriculum_highlights'] as $item)
                                <div class="p-4 rounded-xl border border-border-default bg-surface text-xs sm:text-sm font-sans text-ink-800">
                                    <span class="font-semibold text-brand-700">{{ explode(':', $item)[0] ?? 'Year' }}:</span>
                                    <span class="text-ink-700">{{ explode(':', $item)[1] ?? $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Career & Industry Outlook -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                        <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="trending-up" class="w-5 h-5 text-brand-600" />
                            <span>Professional Career Outlook</span>
                        </h2>
                        <p class="text-sm font-sans text-ink-700 leading-relaxed">
                            {{ $program['career_outlook'] }}
                        </p>
                    </div>

                    <!-- Related Academic Programs -->
                    @if(!empty($program['resolved_related']))
                        <div class="space-y-4">
                            <h2 class="font-heading font-bold text-lg text-ink-950">
                                Alternative & Related Programs
                            </h2>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($program['resolved_related'] as $relProg)
                                    <div class="p-4 rounded-2xl border border-border-default bg-surface hover:border-brand-300 hover:shadow-2xs transition-smooth flex flex-col justify-between">
                                        <div>
                                            <span class="text-xs sm:text-[10px] font-mono font-semibold uppercase px-1.5 py-0.2 rounded bg-brand-50 text-brand-700 border border-brand-200/60 mb-2 inline-block">
                                                {{ $relProg['degree_level'] }}
                                            </span>
                                            <h3 class="font-heading font-bold text-sm text-ink-950 hover:text-brand-600 transition-colors">
                                                <a href="{{ route('programs.show', $relProg['slug']) }}">
                                                    {{ $relProg['title'] }}
                                                </a>
                                            </h3>
                                            <p class="text-xs text-ink-500 font-sans mt-1">
                                                {{ $relProg['university_name'] }}
                                            </p>
                                        </div>

                                        <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs font-sans">
                                            <span class="font-bold text-brand-700">{{ $relProg['cutoff_merit'] }}</span>
                                            <a href="{{ route('programs.show', $relProg['slug']) }}" class="text-brand-600 font-semibold hover:underline">
                                                View Criteria →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <!-- Right 1 Col: Offering Campuses & Institutional Summary -->
                <aside class="space-y-6">
                    
                    <!-- Offering Campuses -->
                    <div class="bg-surface rounded-2xl border border-border-default p-4 sm:p-6 shadow-2xs space-y-4">
                        <h3 class="font-heading font-bold text-sm text-ink-950 flex items-center gap-2">
                            <x-icon name="map-pin" class="w-4 h-4 text-brand-600" />
                            <span>Campuses Offering This Degree</span>
                        </h3>

                        <div class="space-y-3">
                            @php
                                $offeringCampuses = collect($program['university']['campuses'] ?? [])
                                    ->filter(fn($c) => in_array($c['slug'], $program['available_campuses'] ?? []));
                            @endphp

                            @forelse($offeringCampuses as $camp)
                                <div class="p-3.5 rounded-xl border border-border-subtle bg-surface-subtle space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-heading font-bold text-xs text-ink-950">
                                            {{ $camp['name'] }}
                                        </h4>
                                        @if($camp['is_main'])
                                            <span class="text-xs sm:text-[9px] font-mono font-semibold uppercase px-1 py-0.2 rounded bg-brand-50 text-brand-700">Main</span>
                                        @endif
                                    </div>
                                    <p class="text-xs sm:text-[11px] font-sans text-ink-500">
                                        {{ $camp['city'] }}, {{ $camp['province'] }}
                                    </p>
                                    <a 
                                        href="{{ route('universities.campuses.show', ['slug' => $program['university_slug'], 'campusSlug' => $camp['slug']]) }}"
                                        class="text-xs font-semibold text-brand-600 hover:underline block pt-1"
                                    >
                                        Explore Campus Facilities →
                                    </a>
                                </div>
                            @empty
                                <div class="text-xs text-ink-500 font-sans">
                                    Conducted at primary campus of {{ $program['university_name'] }}.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Council Accreditation Details -->
                    <div class="bg-surface rounded-2xl border border-border-default p-4 sm:p-6 shadow-2xs space-y-3">
                        <span class="text-xs font-semibold text-ink-600 uppercase tracking-wider font-sans">Statutory Accreditation</span>
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-bold bg-success-50 text-success-700 border border-success-200/60">
                                {{ $program['council'] }}
                            </span>
                            <span class="text-xs font-sans text-ink-800 font-semibold">{{ $program['accreditation'] }}</span>
                        </div>
                        <p class="text-xs text-ink-500 font-sans leading-relaxed pt-1">
                            Graduating from an accredited council program qualifies the degree holder for national licensure and professional council registration.
                        </p>
                    </div>

                    <!-- Parent University Card -->
                    <div class="bg-surface rounded-2xl border border-border-default p-4 sm:p-6 shadow-2xs space-y-4">
                        <span class="text-xs font-semibold text-ink-600 uppercase tracking-wider font-sans">Institution Profile</span>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-surface-subtle border border-border-default flex items-center justify-center font-heading font-extrabold text-base text-brand-700 shrink-0">
                                {{ $program['university']['acronym'] ?? 'UG' }}
                            </div>
                            <div>
                                <h4 class="font-heading font-bold text-sm text-ink-950">
                                    {{ $program['university_name'] }}
                                </h4>
                                <span class="text-xs font-sans text-ink-500">
                                    {{ $program['university']['city'] ?? 'Pakistan' }}
                                </span>
                            </div>
                        </div>

                        <x-ui.button variant="outline" size="sm" :href="route('universities.show', $program['university_slug'])" class="w-full">
                            Complete University Profile
                        </x-ui.button>
                    </div>

                </aside>

            </div>
        </main>

    </div>
</x-layouts.public>

