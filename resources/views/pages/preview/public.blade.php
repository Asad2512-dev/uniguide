<x-layouts.public title="UniGuide Pakistan — National University Discovery & Admissions">
    <!-- Hero Section -->
    <section class="relative pt-12 pb-16 md:pt-20 md:pb-24 border-b border-border-default bg-gradient-to-b from-brand-50/30 via-canvas to-canvas">
        <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center space-y-6">
                <!-- Eyebrow Pill -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold font-sans bg-surface border border-brand-200 text-brand-700 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span>
                    <span>Fall 2026 National University Admissions Live</span>
                </div>

                <!-- Headline -->
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-heading font-extrabold text-ink-950 tracking-tight leading-[1.12]">
                    Discover verified Pakistani universities, calculate aggregate merit, and secure your future.
                </h1>

                <!-- Subheading -->
                <p class="text-base sm:text-lg text-ink-600 font-sans leading-relaxed max-w-2xl mx-auto">
                    The authoritative national intelligence platform for 248+ listed public and chartered institutions, 6,400+ degree programs, and official merit schedules.
                </p>

                <!-- Search & Quick Filter Bar -->
                <div class="pt-4 max-w-2xl mx-auto">
                    <div class="p-2 bg-surface rounded-2xl border border-border-default shadow-elevated">
                        <div class="flex flex-col sm:flex-row items-center gap-2">
                            <div class="flex-1 w-full">
                                <x-form.search id="hero-search" placeholder="Search by university (e.g. NUST, FAST), degree (BSCS, MBBS), or city..." />
                            </div>
                            <x-ui.button variant="primary" size="md" :href="route('design-system') . '#domain-cards'" iconRight="arrow-right" class="w-full sm:w-auto shrink-0">
                                Explore
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Filter Tags -->
                    <div class="flex flex-wrap items-center justify-center gap-1.5 mt-4 text-xs font-sans text-ink-500">
                        <span class="font-medium">Trending searches:</span>
                        <x-form.filter-chip label="Top Engineering (PEC)" />
                        <x-form.filter-chip label="Computer Science & AI" />
                        <x-form.filter-chip label="Islamabad & Rawalpindi" />
                        <x-form.filter-chip label="Ehsaas Scholarship" />
                    </div>
                </div>
            </div>

            <!-- Key National Metric Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mt-16">
                @foreach($metrics as $metric)
                    <x-ui.metric-card 
                        :title="$metric['title']"
                        :value="$metric['value']"
                        :change="$metric['change']"
                        :changeType="$metric['changeType']"
                        :icon="$metric['icon']"
                        :iconColor="$metric['iconColor']"
                        :period="$metric['period']"
                    />
                @endforeach
            </div>
        </div>
    </section>

    <!-- University Discovery Showcase Section -->
    <section class="py-16 md:py-20 landing-container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
            <x-ui.section-header 
                eyebrow="University Registry Directory"
                title="Featured National Universities"
                description="Explore accredited higher education institutions in Pakistan with gazetted tuition fees, closing merit cutoffs, and verified statutory recognition."
            />

            <!-- Segmented Control Filters -->
            <div class="shrink-0">
                <x-ui.segmented-control 
                    :options="[
                        ['id' => 'all', 'label' => 'All Sectors', 'count' => 248],
                        ['id' => 'public', 'label' => 'Public', 'count' => 142],
                        ['id' => 'private', 'label' => 'Chartered Private', 'count' => 106],
                    ]"
                    default="all"
                />
            </div>
        </div>

        <!-- University Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-domain.university-card 
                name="National University of Sciences & Technology"
                acronym="NUST"
                location="Sector H-12, Islamabad"
                sector="Public Sector"
                hecRank="#1 Engineering & Tech"
                feeRange="PKR 135,000 – 175,000"
                programsCount="54 Degree Programs"
                acceptanceRate="~12% Highly Selective"
                status="open"
                deadline="15 July 2026"
                :featured="true"
            />

            <x-domain.university-card 
                name="Lahore University of Management Sciences"
                acronym="LUMS"
                location="DHA Phase 5, Lahore"
                sector="Chartered Private"
                hecRank="#1 Business & Management"
                feeRange="PKR 380,000 – 460,000"
                programsCount="32 Degree Programs"
                acceptanceRate="~9% Extremely Selective"
                status="closing_soon"
                deadline="30 June 2026"
            />

            <x-domain.university-card 
                name="FAST - National University of Computer & Emerging Sciences"
                acronym="FAST"
                location="Islamabad and Lahore"
                sector="Chartered Autonomous"
                hecRank="#1 Computing & IT"
                feeRange="PKR 145,000 – 165,000"
                programsCount="24 Degree Programs"
                acceptanceRate="~15% Competitive"
                status="open"
                deadline="05 July 2026"
            />
        </div>

        <div class="mt-10 text-center">
            <x-ui.button variant="outline" size="md" :href="route('design-system')" iconRight="arrow-right">
                View All 248 Recognized Universities in Catalog
            </x-ui.button>
        </div>
    </section>

    <!-- AI Advisor Guidance Section -->
    <section class="py-16 bg-surface-subtle border-y border-border-default">
        <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-5 space-y-5">
                    <x-ui.section-header 
                        eyebrow="AI-Powered Counseling"
                        title="Personalized merit calculation with verified statutory data"
                        description="UniGuide's admission engine analyzes your Matric, FSc/A-Levels, and Entry Test scores against past 5-year closing cutoffs to calculate your exact admission probabilities across 240+ institutions."
                    />

                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-success-50 text-success-600 flex items-center justify-center shrink-0 mt-0.5 border border-success-200">
                                <x-icon name="check" class="w-3.5 h-3.5" />
                            </div>
                            <div class="text-xs font-sans">
                                <span class="font-bold text-ink-900">Official Formula Calculations:</span>
                                <span class="text-ink-600"> NUST NET (75/15/10), FAST NUCES (50/50), ECAT Punjab, MDCAT Sindh, and NUMS.</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-success-50 text-success-600 flex items-center justify-center shrink-0 mt-0.5 border border-success-200">
                                <x-icon name="check" class="w-3.5 h-3.5" />
                            </div>
                            <div class="text-xs font-sans">
                                <span class="font-bold text-ink-900">Zero Hallucinations:</span>
                                <span class="text-ink-600"> Every recommendation cites official gazette notifications and university prospectuses.</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <x-ui.button variant="primary" size="md" :href="route('preview.student')" iconRight="arrow-right">
                            Try AI Admission Advisor
                        </x-ui.button>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <x-domain.ai-message 
                        advisorName="UniGuide National Merit Intelligence"
                        confidence="98% High Confidence"
                        timestamp="Verified with Fall 2025/2026 Cutoffs"
                        :citations="[
                            ['source' => 'NUST Directorate of Admissions Gazette 2025', 'reference' => 'Cutoff Merit Circular 2025-SEECS-CS', 'date' => 'Official Source'],
                            ['source' => 'FAST-NUCES Islamabad Merit Notification', 'reference' => 'BSCS Aggregate Range: 76.5% - 81.2%', 'date' => 'Verified']
                        ]"
                        :prompts="[
                            'What are my chances at FAST with 78% aggregate?',
                            'Show available scholarships for engineering students',
                            'Compare fee structures between NUST and GIKI'
                        ]"
                    >
                        <p>
                            Based on your FSc Pre-Engineering score (<strong>92.6%</strong>) and NET score (<strong>156/200</strong>), your calculated NUST aggregate is <strong>82.45%</strong>.
                        </p>
                        <p>
                            Historically, the first merit list for <strong>BS Software Engineering (SEECS)</strong> closed at <strong>80.12%</strong>, and <strong>BS Computer Science</strong> closed at <strong>81.40%</strong>. Your probability of selection in the <strong>First Merit List</strong> is evaluated at <strong>94% (Very High)</strong>.
                        </p>
                    </x-domain.ai-message>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

