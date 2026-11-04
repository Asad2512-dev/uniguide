<x-layouts.public 
    :title="$university['name'] . ' (' . $university['acronym'] . ') — UniGuide Pakistan'"
    :description="$university['description']"
>
    <div x-data="{ 
        activeTab: 'overview',
        saved: false,
        saveUniversity() {
            this.saved = !this.saved;
            if (this.saved) {
                $store.toasts.add('Added ' + '{{ $university['acronym'] }}' + ' to your saved institutions.', 'success');
            } else {
                $store.toasts.add('Removed ' + '{{ $university['acronym'] }}' + ' from saved list.', 'info');
            }
        }
    }" class="min-h-screen bg-canvas pb-24">
        
        <!-- Breadcrumb & Institutional Header -->
        <header class="bg-surface border-b border-border-default/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Breadcrumb -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Universities', 'url' => route('universities.index')],
                        ['label' => $university['acronym'], 'url' => route('universities.show', $university['slug'])]
                    ]" />
                </div>

                <!-- Identity Row -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-2">
                    <div class="flex items-start gap-4 sm:gap-5">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-brand-50 border border-brand-200/80 flex items-center justify-center font-heading font-extrabold text-2xl sm:text-3xl text-brand-700 shadow-2xs shrink-0">
                            {{ $university['acronym'] }}
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200/60 font-sans">
                                    {{ $university['charter_type'] }}
                                </span>
                                <x-domain.verification-badge type="hec_recognized" :detail="$university['hec_category']" size="sm" />
                                <span class="text-xs font-mono text-ink-400">Est. {{ $university['established'] }}</span>
                            </div>

                            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                                {{ $university['name'] }}
                            </h1>

                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 text-xs sm:text-sm font-sans text-ink-500 mt-2">
                                <span class="flex items-center gap-1.5">
                                    <x-icon name="map-pin" class="w-4 h-4 text-ink-400" />
                                    {{ $university['address'] }}
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1.5 font-semibold text-brand-700">
                                    <x-icon name="award" class="w-4 h-4 text-brand-600" />
                                    {{ $university['hec_ranking'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right CTAs: Save, Compare, Official Site -->
                    <div class="flex items-center flex-wrap gap-2.5 shrink-0">
                        <button 
                            type="button" 
                            @click="saveUniversity()"
                            :class="saved ? 'bg-brand-50 border-brand-300 text-brand-700' : 'bg-surface border-border-default text-ink-700 hover:bg-surface-subtle'"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border text-xs font-semibold font-sans shadow-2xs transition-smooth cursor-pointer"
                        >
                            <x-icon name="bookmark" class="w-4 h-4" ::class="saved ? 'fill-brand-600 text-brand-600' : ''" />
                            <span x-text="saved ? 'Saved' : 'Save Institution'"></span>
                        </button>

                        <a 
                            href="{{ route('preview.student') }}" 
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-700 font-sans shadow-2xs transition-smooth"
                        >
                            <x-icon name="git-compare" class="w-4 h-4 text-ink-500" />
                            <span>Compare</span>
                        </a>

                        <a 
                            href="{{ $university['website'] }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-ink-900 hover:bg-brand-600 text-white text-xs font-semibold font-sans shadow-xs transition-smooth"
                        >
                            <span>Official Portal</span>
                            <x-icon name="external-link" class="w-3.5 h-3.5" />
                        </a>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="flex items-center gap-2 border-t border-border-subtle mt-6 pt-3 overflow-x-auto text-xs font-sans font-semibold text-ink-500">
                    <button 
                        type="button" 
                        @click="activeTab = 'overview'" 
                        :class="activeTab === 'overview' ? 'text-brand-700 border-brand-600 bg-brand-50/50' : 'text-ink-600 border-transparent hover:text-ink-900'"
                        class="px-3.5 py-2 rounded-lg border-b-2 transition-smooth whitespace-nowrap cursor-pointer"
                    >
                        Overview & Profile
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'programs'" 
                        :class="activeTab === 'programs' ? 'text-brand-700 border-brand-600 bg-brand-50/50' : 'text-ink-600 border-transparent hover:text-ink-900'"
                        class="px-3.5 py-2 rounded-lg border-b-2 transition-smooth whitespace-nowrap flex items-center gap-1.5 cursor-pointer"
                    >
                        <span>Degree Programs</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono bg-surface-subtle border border-border-default">{{ count($programs) }}</span>
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'campuses'" 
                        :class="activeTab === 'campuses' ? 'text-brand-700 border-brand-600 bg-brand-50/50' : 'text-ink-600 border-transparent hover:text-ink-900'"
                        class="px-3.5 py-2 rounded-lg border-b-2 transition-smooth whitespace-nowrap flex items-center gap-1.5 cursor-pointer"
                    >
                        <span>Campuses & Facilities</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-mono bg-surface-subtle border border-border-default">{{ count($university['campuses'] ?? []) }}</span>
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'fees'" 
                        :class="activeTab === 'fees' ? 'text-brand-700 border-brand-600 bg-brand-50/50' : 'text-ink-600 border-transparent hover:text-ink-900'"
                        class="px-3.5 py-2 rounded-lg border-b-2 transition-smooth whitespace-nowrap cursor-pointer"
                    >
                        Fee Schedules & Aid
                    </button>
                    <button 
                        type="button" 
                        @click="activeTab = 'recognition'" 
                        :class="activeTab === 'recognition' ? 'text-brand-700 border-brand-600 bg-brand-50/50' : 'text-ink-600 border-transparent hover:text-ink-900'"
                        class="px-3.5 py-2 rounded-lg border-b-2 transition-smooth whitespace-nowrap cursor-pointer"
                    >
                        Statutory Charter & Audits
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content Layout -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left 2 Cols: Main Institutional Details -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Overview Tab Content -->
                    <div x-show="activeTab === 'overview'" class="space-y-8">
                        <!-- Key Institutional Metrics -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                                <span class="block text-xs font-sans text-ink-500 font-medium">Acceptance Rate</span>
                                <span class="font-heading font-extrabold text-base sm:text-lg text-ink-950 mt-0.5 block">
                                    {{ $university['acceptance_rate'] }}
                                </span>
                                <span class="text-[10px] text-ink-400 font-sans">National Selection</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                                <span class="block text-xs font-sans text-ink-500 font-medium">Estimated Tuition</span>
                                <span class="font-heading font-extrabold text-base sm:text-lg text-ink-950 mt-0.5 block">
                                    {{ $university['fee_range'] }}
                                </span>
                                <span class="text-[10px] text-ink-400 font-sans">Per Semester</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                                <span class="block text-xs font-sans text-ink-500 font-medium">PhD Faculty</span>
                                <span class="font-heading font-extrabold text-base sm:text-lg text-ink-950 mt-0.5 block">
                                    {{ $university['faculty_phd_ratio'] }}
                                </span>
                                <span class="text-[10px] text-ink-400 font-sans">Academic Strength</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-surface border border-border-default shadow-2xs">
                                <span class="block text-xs font-sans text-ink-500 font-medium">Total Enrollment</span>
                                <span class="font-heading font-extrabold text-base sm:text-lg text-ink-950 mt-0.5 block">
                                    {{ $university['total_students'] }}
                                </span>
                                <span class="text-[10px] text-ink-400 font-sans">Active Scholars</span>
                            </div>
                        </div>

                        <!-- Institutional Description -->
                        <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                            <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                                <x-icon name="info" class="w-5 h-5 text-brand-600" />
                                <span>About the Institution</span>
                            </h2>
                            <p class="text-sm sm:text-base text-ink-700 font-sans leading-relaxed">
                                {{ $university['description'] }}
                            </p>
                            <div class="pt-2">
                                <x-domain.source-citation 
                                    source="HEC Higher Education Registry & Gazette Notification Records"
                                    url="https://hec.gov.pk"
                                    verifiedAt="May 2026 Audit"
                                />
                            </div>
                        </div>

                        <!-- Campus Preview Section -->
                        <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-5">
                            <div class="flex items-center justify-between">
                                <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                                    <x-icon name="map-pin" class="w-5 h-5 text-brand-600" />
                                    <span>Campuses & Branch Networks ({{ count($university['campuses'] ?? []) }})</span>
                                </h2>
                                <button type="button" @click="activeTab = 'campuses'" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                                    View all details →
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($university['campuses'] as $camp)
                                    <div class="p-4 rounded-xl border border-border-default bg-surface-subtle hover:border-brand-300 transition-smooth flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="font-heading font-bold text-sm text-ink-950">
                                                    {{ $camp['name'] }}
                                                </h3>
                                                @if($camp['is_main'])
                                                    <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-brand-50 text-brand-700 border border-brand-200/60 uppercase font-mono">
                                                        Main
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-ink-500 font-sans">
                                                {{ $camp['city'] }}, {{ $camp['province'] }}
                                            </p>
                                        </div>

                                        <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs font-sans">
                                            <span class="text-ink-600">{{ $camp['programs_offered_count'] }} Degree Programs</span>
                                            <a 
                                                href="{{ route('universities.campuses.show', ['slug' => $university['slug'], 'campusSlug' => $camp['slug']]) }}"
                                                class="font-semibold text-brand-600 hover:text-brand-700"
                                            >
                                                Campus Details →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Top Programs Preview -->
                        <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-5">
                            <div class="flex items-center justify-between">
                                <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                                    <x-icon name="graduation-cap" class="w-5 h-5 text-brand-600" />
                                    <span>Prominent Degree Programs</span>
                                </h2>
                                <button type="button" @click="activeTab = 'programs'" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                                    View Full Catalog ({{ count($programs) }}) →
                                </button>
                            </div>

                            <div class="space-y-3">
                                @forelse(collect($programs)->take(3) as $prog)
                                    <div class="p-4 rounded-xl border border-border-default bg-surface hover:border-brand-300 hover:shadow-2xs transition-smooth flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-[10px] font-semibold font-mono uppercase px-1.5 py-0.2 rounded bg-brand-50 text-brand-700 border border-brand-200/60">
                                                    {{ $prog['degree_level'] }}
                                                </span>
                                                <h3 class="font-heading font-bold text-sm text-ink-950 hover:text-brand-600 transition-colors">
                                                    <a href="{{ route('programs.show', $prog['slug']) }}">{{ $prog['title'] }}</a>
                                                </h3>
                                            </div>
                                            <p class="text-xs text-ink-500 font-sans">
                                                {{ $prog['duration'] }} • Closing Merit: <span class="font-bold text-brand-700">{{ $prog['cutoff_merit'] }}</span>
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-3 shrink-0">
                                            <div class="text-right hidden sm:block">
                                                <span class="font-mono font-bold text-xs text-ink-900 block">{{ $prog['fee_semester'] }}</span>
                                                <span class="text-[10px] text-ink-400 font-sans">per semester</span>
                                            </div>
                                            <x-ui.button variant="outline" size="xs" :href="route('programs.show', $prog['slug'])">
                                                Details
                                            </x-ui.button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-ink-500 font-sans">Detailed program catalog currently being verified for this institution.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Programs Tab Content -->
                    <div x-show="activeTab === 'programs'" class="space-y-6">
                        <div class="bg-surface rounded-2xl border border-border-default p-6 shadow-2xs">
                            <h2 class="font-heading font-bold text-lg text-ink-950 mb-1">
                                Comprehensive Program Catalog
                            </h2>
                            <p class="text-xs text-ink-600 font-sans mb-6">
                                Degree programs offered by {{ $university['name'] }} with verified semester fee structures and accreditation council registrations.
                            </p>

                            <div class="space-y-4">
                                @forelse($programs as $prog)
                                    <div class="p-4 rounded-xl border border-border-default hover:border-brand-300 transition-smooth flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-mono font-semibold uppercase px-1.5 py-0.2 rounded bg-brand-50 text-brand-700 border border-brand-200/60">
                                                    {{ $prog['degree_level'] }}
                                                </span>
                                                <h3 class="font-heading font-bold text-base text-ink-950">
                                                    <a href="{{ route('programs.show', $prog['slug']) }}" class="hover:text-brand-600 transition-colors">
                                                        {{ $prog['title'] }}
                                                    </a>
                                                </h3>
                                            </div>
                                            <p class="text-xs text-ink-500 font-sans">
                                                {{ $prog['discipline_label'] }} • Duration: {{ $prog['duration'] }}
                                            </p>
                                            <div class="flex items-center gap-1.5 text-xs text-ink-600 pt-1">
                                                <x-icon name="shield-check" class="w-3.5 h-3.5 text-success-600" />
                                                <span>{{ $prog['accreditation'] }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between md:justify-end gap-6 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-border-subtle">
                                            <div class="text-left md:text-right">
                                                <span class="block text-[11px] font-sans text-ink-400">Semester Tuition</span>
                                                <span class="font-mono font-bold text-sm text-ink-900">{{ $prog['fee_semester'] }}</span>
                                                <span class="block text-[10px] text-brand-700 font-semibold font-mono">Merit: {{ $prog['cutoff_merit'] }}</span>
                                            </div>
                                            <x-ui.button variant="outline" size="sm" :href="route('programs.show', $prog['slug'])">
                                                View Criteria
                                            </x-ui.button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-10">
                                        <p class="text-sm font-sans text-ink-500">No programs listed yet for this institution.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Campuses Tab Content -->
                    <div x-show="activeTab === 'campuses'" class="space-y-6">
                        <div class="bg-surface rounded-2xl border border-border-default p-6 shadow-2xs space-y-6">
                            <div>
                                <h2 class="font-heading font-bold text-lg text-ink-950">Institutional Campuses</h2>
                                <p class="text-xs text-ink-600 font-sans">Official physical locations recognized by the federal or provincial charter.</p>
                            </div>

                            <div class="space-y-6">
                                @foreach($university['campuses'] as $camp)
                                    <div class="p-5 rounded-2xl border border-border-default bg-surface-subtle space-y-4">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h3 class="font-heading font-bold text-base text-ink-950">
                                                        {{ $camp['name'] }}
                                                    </h3>
                                                    @if($camp['is_main'])
                                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-brand-50 text-brand-700 border border-brand-200/60 uppercase font-mono">
                                                            Charter Main Campus
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-ink-500 font-sans flex items-center gap-1.5">
                                                    <x-icon name="map-pin" class="w-3.5 h-3.5 text-ink-400" />
                                                    {{ $camp['address'] }}
                                                </p>
                                            </div>

                                            <x-ui.button 
                                                variant="outline" 
                                                size="sm" 
                                                :href="route('universities.campuses.show', ['slug' => $university['slug'], 'campusSlug' => $camp['slug']])"
                                            >
                                                Explore Campus
                                            </x-ui.button>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs font-sans text-ink-600">
                                            <div class="p-3 rounded-xl bg-surface border border-border-subtle">
                                                <span class="block text-[11px] text-ink-400 font-medium mb-0.5">Hostel Facilities</span>
                                                <span class="font-medium text-ink-800">{{ $camp['hostel_capacity'] }}</span>
                                            </div>
                                            <div class="p-3 rounded-xl bg-surface border border-border-subtle">
                                                <span class="block text-[11px] text-ink-400 font-medium mb-0.5">Student Transport</span>
                                                <span class="font-medium text-ink-800">{{ $camp['transport'] }}</span>
                                            </div>
                                            <div class="p-3 rounded-xl bg-surface border border-border-subtle">
                                                <span class="block text-[11px] text-ink-400 font-medium mb-0.5">Contact Line</span>
                                                <span class="font-mono text-ink-800">{{ $camp['phone'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Fees & Scholarships Tab Content -->
                    <div x-show="activeTab === 'fees'" class="space-y-6">
                        <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-6">
                            <div>
                                <h2 class="font-heading font-bold text-lg text-ink-950">Tuition & Financial Architecture</h2>
                                <p class="text-xs text-ink-600 font-sans">Official audited tuition estimates and institutional financial aid policies.</p>
                            </div>

                            <div class="p-4 rounded-xl bg-surface-subtle border border-border-subtle space-y-2 text-xs font-sans">
                                <div class="flex items-center justify-between py-1.5 border-b border-border-subtle">
                                    <span class="text-ink-600">Standard Undergraduate Tuition</span>
                                    <span class="font-mono font-bold text-ink-950">{{ $university['fee_range'] }} / Semester</span>
                                </div>
                                <div class="flex items-center justify-between py-1.5 border-b border-border-subtle">
                                    <span class="text-ink-600">Admission & Security Fee (One-time, Refundable)</span>
                                    <span class="font-mono font-bold text-ink-950">PKR 35,000 – 45,000</span>
                                </div>
                                <div class="flex items-center justify-between py-1.5">
                                    <span class="text-ink-600">Estimated On-Campus Hostel & Mess Fee</span>
                                    <span class="font-mono font-bold text-ink-950">PKR 28,000 – 35,000 / Month</span>
                                </div>
                            </div>

                            <div>
                                <h3 class="font-heading font-bold text-base text-ink-950 mb-3 flex items-center gap-2">
                                    <x-icon name="award" class="w-4 h-4 text-brand-600" />
                                    <span>Accredited Financial Aid & Scholarships</span>
                                </h3>
                                <ul class="space-y-2.5 text-xs sm:text-sm font-sans text-ink-700">
                                    @foreach($university['scholarships_offered'] as $scholarship)
                                        <li class="flex items-start gap-2.5 p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                            <x-icon name="check-circle-2" class="w-4 h-4 text-success-600 shrink-0 mt-0.5" />
                                            <span>{{ $scholarship }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Statutory Recognition & Audits Tab Content -->
                    <div x-show="activeTab === 'recognition'" class="space-y-6">
                        <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-6">
                            <div>
                                <h2 class="font-heading font-bold text-lg text-ink-950">Statutory Charter & Legal Recognition</h2>
                                <p class="text-xs text-ink-600 font-sans">Full legal transparency regarding gazette notifications and statutory authority.</p>
                            </div>

                            <div class="space-y-4">
                                <div class="p-4 rounded-xl border border-border-default bg-surface-subtle">
                                    <span class="block text-xs font-semibold text-ink-500 font-sans">Parliamentary Charter Citation</span>
                                    <p class="font-mono text-sm text-ink-900 font-bold mt-1">{{ $university['charter_citation'] }}</p>
                                </div>

                                <div class="p-4 rounded-xl border border-border-default bg-surface-subtle">
                                    <span class="block text-xs font-semibold text-ink-500 font-sans">Federal / Provincial Gazette Reference</span>
                                    <p class="font-mono text-sm text-ink-900 font-bold mt-1">{{ $university['gazette_reference'] }}</p>
                                </div>
                            </div>

                            <div>
                                <h3 class="font-heading font-bold text-base text-ink-950 mb-3">Statutory Council Accreditations</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($university['accreditations'] as $acc)
                                        <div class="p-3.5 rounded-xl border border-border-default bg-surface">
                                            <span class="inline-block px-1.5 py-0.2 rounded bg-brand-50 text-brand-700 border border-brand-200/60 font-mono text-[10px] font-bold uppercase mb-1">
                                                {{ $acc['body'] }}
                                            </span>
                                            <p class="text-xs font-sans text-ink-800 font-medium">{{ $acc['title'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right 1 Col: Institutional Sticky Sidebar -->
                <aside class="space-y-6">
                    
                    <!-- Admissions Status Card -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 shadow-2xs space-y-5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-semibold uppercase tracking-wider text-ink-400 font-sans">
                                Admission Cycle
                            </span>
                            <x-domain.admission-status :status="$university['status']" :deadline="$university['deadline']" size="sm" />
                        </div>

                        <div class="border-t border-border-subtle pt-4 space-y-3 text-xs font-sans">
                            <div class="flex items-center justify-between text-ink-600">
                                <span>Upcoming Deadline:</span>
                                <span class="font-semibold text-ink-900">{{ $university['deadline'] }}</span>
                            </div>
                            <div class="flex items-center justify-between text-ink-600">
                                <span>Admissions Type:</span>
                                <span class="font-semibold text-ink-900">Fall 2026 Intake</span>
                            </div>
                            <div class="flex items-center justify-between text-ink-600">
                                <span>Acceptance Rate:</span>
                                <span class="font-semibold text-brand-700">{{ $university['acceptance_rate'] }}</span>
                            </div>
                        </div>

                        <a 
                            href="{{ route('preview.student') }}" 
                            class="block w-full text-center py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-sans text-xs font-semibold shadow-xs transition-smooth"
                        >
                            Check My Merit Eligibility
                        </a>
                    </div>

                    <!-- Official Contact Information -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 shadow-2xs space-y-4">
                        <h3 class="font-heading font-bold text-sm text-ink-950 flex items-center gap-2">
                            <x-icon name="phone-call" class="w-4 h-4 text-brand-600" />
                            <span>Admissions & Registry Desk</span>
                        </h3>

                        <div class="space-y-3 text-xs font-sans text-ink-600">
                            <div class="flex items-start gap-2.5">
                                <x-icon name="map-pin" class="w-4 h-4 text-ink-400 shrink-0 mt-0.5" />
                                <span>{{ $university['address'] }}</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <x-icon name="phone" class="w-4 h-4 text-ink-400 shrink-0" />
                                <span class="font-mono">{{ $university['phone'] }}</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <x-icon name="mail" class="w-4 h-4 text-ink-400 shrink-0" />
                                <a href="mailto:{{ $university['email'] }}" class="text-brand-600 hover:underline">
                                    {{ $university['email'] }}
                                </a>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <x-icon name="globe" class="w-4 h-4 text-ink-400 shrink-0" />
                                <a href="{{ $university['website'] }}" target="_blank" rel="noopener noreferrer" class="text-brand-600 hover:underline">
                                    {{ $university['website'] }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Related Institutions -->
                    @if(!empty($relatedUniversities))
                        <div class="bg-surface rounded-2xl border border-border-default p-6 shadow-2xs space-y-4">
                            <h3 class="font-heading font-bold text-sm text-ink-950 flex items-center gap-2">
                                <x-icon name="building-2" class="w-4 h-4 text-brand-600" />
                                <span>Related Universities</span>
                            </h3>

                            <div class="space-y-3">
                                @foreach($relatedUniversities as $rel)
                                    <a 
                                        href="{{ route('universities.show', $rel['slug']) }}" 
                                        class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-surface-subtle transition-smooth group"
                                    >
                                        <div class="w-10 h-10 rounded-lg bg-surface-subtle border border-border-default flex items-center justify-center font-heading font-extrabold text-xs text-brand-700 shrink-0 group-hover:border-brand-300">
                                            {{ $rel['acronym'] }}
                                        </div>
                                        <div class="truncate">
                                            <span class="block font-heading font-bold text-xs text-ink-950 group-hover:text-brand-600 transition-colors truncate">
                                                {{ $rel['name'] }}
                                            </span>
                                            <span class="block text-[11px] text-ink-400 font-sans">
                                                {{ $rel['city'] }} • {{ $rel['hec_ranking'] }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </aside>

            </div>
        </main>

    </div>
</x-layouts.public>
