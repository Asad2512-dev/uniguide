<x-layouts.public 
    title="How Admissions Work — UniGuide Pakistan"
    description="Understand the Pakistani university admission process: aggregate merit calculations, entry tests, merit list rounds, and fee challans."
>
    <!-- Header -->
    <section class="pt-12 pb-14 border-b border-border-default bg-surface">
        <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8">
            <x-ui.page-header 
                :title="content('how-it-works.header.title', 'How Admissions Work on UniGuide')"
                :description="content('how-it-works.header.description', 'A transparent, step-by-step roadmap for Pakistani students from intermediate exams to verified university enrollment.')"
                :badge="content('how-it-works.header.badge', 'Admissions Protocol')"
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

        <section class="rounded-xl border border-border-default p-6"><h2 class="text-xl font-semibold">Recorded formulas only</h2><p class="mt-3 text-sm text-ink-600">Aggregate calculations require your saved results and a formula configured by the institution. Missing requirements are shown as unverified.</p><a href="{{ route('student.profile') }}" class="mt-4 inline-block text-brand-700">Update your academic profile →</a></section>
    </div>
</x-layouts.public>

