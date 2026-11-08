<x-layouts.public 
    title="About UniGuide Pakistan — National Higher Education Intelligence Portal"
    description="Learn about UniGuide Pakistan's mission, statutory data verification protocols, regulatory compliance, and academic governance."
>
    <!-- Header -->
    <section class="pt-12 pb-14 border-b border-border-default bg-surface">
        <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8">
            <x-ui.page-header 
                :title="content('about.header.title', 'About UniGuide Pakistan')"
                :description="content('about.header.description', 'Pakistan\'s national intelligence platform for university discovery, verified merit calculations, and transparent higher education opportunities.')"
                :badge="content('about.header.badge', 'University Discovery')"
                badgeVariant="brand"
            >
                <x-slot:breadcrumbs>
                    <x-ui.breadcrumbs 
                        :items="[
                            ['label' => 'Home', 'url' => route('home')],
                            ['label' => 'About UniGuide']
                        ]"
                    />
                </x-slot:breadcrumbs>
            </x-ui.page-header>
        </div>
    </section>

    <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
        <!-- Mission & Editorial Narrative -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-7 space-y-5">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold font-sans bg-brand-50 text-brand-700 border border-brand-200">
                    <x-icon name="sparkles" class="w-4 h-4 text-brand-600" />
                    <span>The National Education Imperative</span>
                </div>

                <h2 class="text-2xl sm:text-3xl md:text-4xl font-heading font-extrabold text-ink-950 tracking-tight leading-tight">
                    Eliminating the information asymmetry that prevents talented Pakistani students from reaching their potential.
                </h2>

                <p class="text-sm md:text-base text-ink-600 font-sans leading-relaxed">
                    Every year, over 1.2 million Pakistani students complete their Intermediate (HSSC / FSc / FA / ICS) and Cambridge A-Levels. Navigating the decentralized admissions landscape across 240+ institutions—each with unique entry tests, divergent aggregate weightage formulas, unpublished closing cutoffs, and confusing fee schedules—leads to missed deadlines, wasted application fees, and unfulfilled aspirations.
                </p>

                <p class="text-sm text-ink-600 font-sans leading-relaxed">
                    UniGuide Pakistan was established to provide a unified, authentic, and completely free national gateway. We do not sell promotional placements or accept paid ranking manipulations. Every statistic, fee figure, and merit formula is directly cross-referenced against official provincial assembly charters and gazetted university circulars.
                </p>
            </div>

            <!-- Visual Badge / Metric Column -->
            <div class="lg:col-span-5 space-y-4">
                <x-ui.card padding="p-6" class="space-y-4 bg-gradient-to-br from-surface to-brand-50/30">
                    <div class="flex items-center gap-3">
                        <x-brand.logo size="lg" />
                        <div>
                            <h4 class="font-heading font-bold text-base text-ink-950">Statutory Data Seal</h4>
                            <p class="text-xs text-ink-500 font-sans">Federal Higher Education Registry</p>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-border-subtle space-y-2 text-xs font-sans">
                        <div class="flex items-center justify-between">
                            <span class="text-ink-600">Total Recognized Universities:</span>
                            <span class="font-bold text-ink-950">248 Institutions</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-ink-600">Accredited Curricula Monitored:</span>
                            <span class="font-bold text-ink-950">6,420 Degrees</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-ink-600">Regulatory Oversight:</span>
                            <span class="font-mono text-emerald-700 font-semibold">University source records</span>
                        </div>
                    </div>
                    <div class="pt-2">
                        <x-domain.verification-badge type="hec_recognized" detail="Level-1 Verified Node" size="sm" />
                    </div>
                </x-ui.card>
            </div>
        </div>

        <!-- Data Transparency & Verification Protocol -->
        <section class="py-10 border-y border-border-default space-y-8">
            <x-ui.section-header 
                eyebrow="Our Verification Standard"
                title="The UniGuide Zero-Hallucination & Transparency Protocol"
                description="We operate with rigorous data standards to ensure candidate safety across Punjab and Islamabad."
            />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-ui.card padding="p-6" class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-success-50 text-success-600 flex items-center justify-center border border-success-200">
                        <x-icon name="shield-check" class="w-5 h-5" />
                    </div>
                    <h3 class="font-heading font-bold text-base text-ink-950">1. Statutory Charter Verification</h3>
                    <p class="text-xs text-ink-600 font-sans leading-relaxed">
                        An institution is only cataloged once verified against the official gazette of Pakistan or provincial assembly charter. Unaccredited sub-campuses are flagged with public warning alerts.
                    </p>
                </x-ui.card>

                <x-ui.card padding="p-6" class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-200">
                        <x-icon name="receipt" class="w-5 h-5" />
                    </div>
                    <h3 class="font-heading font-bold text-base text-ink-950">2. Gazetted Fee Transparency</h3>
                    <p class="text-xs text-ink-600 font-sans leading-relaxed">
                        Semester tuition ranges reflect official published fee schedules including admission charges, examination funds, and laboratory fees, preventing surprise out-of-pocket costs.
                    </p>
                </x-ui.card>

                <x-ui.card padding="p-6" class="space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-lavender-50 text-lavender-600 flex items-center justify-center border border-lavender-200">
                        <x-icon name="calculator" class="w-5 h-5" />
                    </div>
                    <h3 class="font-heading font-bold text-base text-ink-950">3. Mathematical Formula Fidelity</h3>
                    <p class="text-xs text-ink-600 font-sans leading-relaxed">
                        Every formula is implemented mathematically from institutional prospectuses, verifying whether intermediate Part-I or full Part-II marks apply for the active session.
                    </p>
                </x-ui.card>
            </div>
        </section>

        <!-- National Charter Milestones (Timeline) -->
        <section class="space-y-8">
            <x-ui.section-header 
                eyebrow="Platform Timeline"
                title="Evolution of the National Platform"
                description="From research initiative to national higher education infrastructure."
            />

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @foreach($charterMilestones as $ms)
                    <div class="p-4 sm:p-6 bg-surface rounded-2xl border border-border-default space-y-2">
                        <span class="text-xs font-mono font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-md border border-brand-200">
                            {{ $ms['year'] }}
                        </span>
                        <h4 class="font-heading font-bold text-base text-ink-950 pt-2">{{ $ms['title'] }}</h4>
                        <p class="text-xs text-ink-600 font-sans leading-relaxed">{{ $ms['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Leadership & Governance -->
        <section class="space-y-8">
            <x-ui.section-header 
                eyebrow="Governance"
                title="Academic Advisory Council & Leadership"
                description="Guided by experienced former vice chancellors, accreditation inspectors, and educational systems architects."
            />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($leadership as $leader)
                    <x-ui.card padding="p-6" class="space-y-3">
                        <div class="w-12 h-12 rounded-xl bg-brand-600 text-white font-heading font-bold flex items-center justify-center text-sm shadow-xs">
                            {{ $leader['avatar'] }}
                        </div>
                        <div>
                            <h4 class="font-heading font-bold text-base text-ink-950">{{ $leader['name'] }}</h4>
                            <p class="text-xs font-semibold text-brand-700 font-sans mt-0.5">{{ $leader['role'] }}</p>
                            <p class="text-xs sm:text-[11px] text-ink-500 font-sans mt-2 leading-relaxed">{{ $leader['credentials'] }}</p>
                        </div>
                    </x-ui.card>
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.public>

