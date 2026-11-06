<x-layouts.public 
    title="How Admissions Work — UniGuide Pakistan"
    description="Understand the Pakistani university admission process: aggregate merit calculations, entry tests, merit list rounds, and fee challans."
>
    <!-- Header -->
    <section class="pt-12 pb-14 border-b border-border-default bg-surface">
        <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8">
            <x-ui.page-header 
                title="How Admissions Work on UniGuide"
                description="A transparent, step-by-step roadmap for Pakistani students from intermediate exams to verified university enrollment."
                badge="Admissions Protocol"
                badgeVariant="brand"
            >
                <x-slot:breadcrumbs>
                    <x-ui.breadcrumbs 
                        :items="[
                            ['label' => 'Home', 'url' => route('home')],
                            ['label' => 'How It Works']
                        ]"
                    />
                </x-slot:breadcrumbs>
            </x-ui.page-header>
        </div>
    </section>

    <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-20">
        <!-- 4 Core Steps Detailed -->
        <div class="space-y-12">
            @foreach($workflowSteps as $st)
                <div class="p-4 sm:p-8 bg-surface rounded-3xl border border-border-default shadow-card grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-2 flex flex-col items-start lg:items-center">
                        <span class="text-4xl sm:text-5xl font-heading font-extrabold text-brand-600 bg-brand-50 px-4 py-2 rounded-2xl border border-brand-200">
                            {{ $st['step'] }}
                        </span>
                    </div>

                    <div class="lg:col-span-6 space-y-3">
                        <span class="text-xs font-mono font-bold text-ink-600 uppercase tracking-wider">
                            {{ $st['subtitle'] }}
                        </span>
                        <h3 class="text-xl sm:text-2xl font-heading font-bold text-ink-950">
                            {{ $st['title'] }}
                        </h3>
                        <p class="text-sm text-ink-600 font-sans leading-relaxed">
                            {{ $st['description'] }}
                        </p>
                    </div>

                    <div class="lg:col-span-4 bg-surface-subtle p-5 rounded-2xl border border-border-subtle space-y-2 text-xs font-sans">
                        <span class="font-bold text-ink-900 block font-heading mb-1">Key Guarantees:</span>
                        @foreach($st['features'] as $feat)
                            <div class="flex items-center gap-2 text-ink-700">
                                <x-icon name="check" class="w-3.5 h-3.5 text-success-600 shrink-0" />
                                <span>{{ $feat }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ========================================================================= -->
        <!-- STANDARD PAKISTANI MERIT FORMULA DIRECTORY -->
        <!-- ========================================================================= -->
        <section class="py-12 bg-surface-subtle rounded-3xl border border-border-default p-8 sm:p-12 space-y-8">
            <x-ui.section-header 
                eyebrow="Formulas & Mathematics"
                title="Standard Pakistani Aggregate Merit Formulas"
                description="Each university evaluates candidate credentials differently. Here is how leading institutions calculate your final selection score."
            />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- NUST Formula Card -->
                <x-ui.card padding="p-6" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 font-bold flex items-center justify-center font-heading">
                            NUST
                        </div>
                        <x-ui.badge variant="brand" size="sm">75 / 15 / 10</x-ui.badge>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-base text-ink-950">NUST Entry Test (NET)</h4>
                        <p class="text-xs text-ink-500 font-sans mt-0.5">Applied across all SEECS, SMME, NICE & S3H programs</p>
                    </div>
                    <div class="p-3 bg-surface-subtle rounded-xl border border-border-subtle space-y-1.5 text-xs font-sans">
                        <div class="flex justify-between text-ink-700">
                            <span>NET Test Score (out of 200):</span>
                            <span class="font-bold text-ink-950">75%</span>
                        </div>
                        <div class="flex justify-between text-ink-700">
                            <span>FSc Pre-Eng / ICS Marks:</span>
                            <span class="font-bold text-ink-950">15%</span>
                        </div>
                        <div class="flex justify-between text-ink-700">
                            <span>Matriculation / O-Level:</span>
                            <span class="font-bold text-ink-950">10%</span>
                        </div>
                    </div>
                </x-ui.card>

                <!-- FAST Formula Card -->
                <x-ui.card padding="p-6" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 font-bold flex items-center justify-center font-heading">
                            FAST
                        </div>
                        <x-ui.badge variant="success" size="sm">50 / 50</x-ui.badge>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-base text-ink-950">FAST-NUCES (NU Test)</h4>
                        <p class="text-xs text-ink-500 font-sans mt-0.5">Islamabad and Lahore, Peshawar, Chiniot</p>
                    </div>
                    <div class="p-3 bg-surface-subtle rounded-xl border border-border-subtle space-y-1.5 text-xs font-sans">
                        <div class="flex justify-between text-ink-700">
                            <span>NU Admission Test:</span>
                            <span class="font-bold text-ink-950">50%</span>
                        </div>
                        <div class="flex justify-between text-ink-700">
                            <span>FSc Part-I / Equivalent:</span>
                            <span class="font-bold text-ink-950">50%</span>
                        </div>
                        <div class="flex justify-between text-ink-700">
                            <span>Matriculation Marks:</span>
                            <span class="font-semibold text-ink-500">0% (Tie-breaker)</span>
                        </div>
                    </div>
                </x-ui.card>

                <!-- MDCAT Formula Card -->
                <x-ui.card padding="p-6" class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-9 h-9 rounded-xl bg-brand-50 text-brand-700 font-bold flex items-center justify-center font-heading">
                            PMDC
                        </div>
                        <x-ui.badge variant="warning" size="sm">50 / 40 / 10</x-ui.badge>
                    </div>
                    <div>
                        <h4 class="font-heading font-bold text-base text-ink-950">Medical & Dental (MDCAT)</h4>
                        <p class="text-xs text-ink-500 font-sans mt-0.5">PMDC National Public & Private Medical Colleges</p>
                    </div>
                    <div class="p-3 bg-surface-subtle rounded-xl border border-border-subtle space-y-1.5 text-xs font-sans">
                        <div class="flex justify-between text-ink-700">
                            <span>National MDCAT Score:</span>
                            <span class="font-bold text-ink-950">50%</span>
                        </div>
                        <div class="flex justify-between text-ink-700">
                            <span>FSc Pre-Medical:</span>
                            <span class="font-bold text-ink-950">40%</span>
                        </div>
                        <div class="flex justify-between text-ink-700">
                            <span>Matriculation (Science):</span>
                            <span class="font-bold text-ink-950">10%</span>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </section>

        <!-- Final CTA -->
        <div class="p-4 sm:p-8 bg-surface rounded-2xl border border-border-default text-center space-y-4 max-w-2xl mx-auto shadow-card">
            <h3 class="text-xl font-heading font-bold text-ink-950">Ready to calculate your real aggregate?</h3>
            <p class="text-xs sm:text-sm text-ink-600 font-sans leading-relaxed">
                Log into the student portal to compute your chances across all top Pakistani universities in 30 seconds.
            </p>
            <div class="pt-2 flex items-center justify-center gap-3">
                <x-ui.button variant="primary" size="md" :href="route('eligibility.index')" iconRight="arrow-right">
                    Open Student Merit Calculator
                </x-ui.button>
            </div>
        </div>
    </div>
</x-layouts.public>

