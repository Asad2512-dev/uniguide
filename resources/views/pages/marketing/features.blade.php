<x-layouts.public 
    title="Platform Features — UniGuide Pakistan"
    description="Explore the technological capabilities of UniGuide: multi-parameter search, aggregate calculator, side-by-side comparison, and document locker."
>
    <!-- Header -->
    <section class="pt-12 pb-14 border-b border-border-default bg-surface">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-ui.page-header 
                title="UniGuide Platform Capabilities"
                description="Designed specifically for the realities of higher education in Pakistan: multiple examination boards, decentralized tests, and strict HEC guidelines."
                badge="SaaS-Scale Architecture"
                badgeVariant="brand"
            >
                <x-slot:breadcrumbs>
                    <x-ui.breadcrumbs 
                        :items="[
                            ['label' => 'Home', 'url' => route('home')],
                            ['label' => 'Platform Features']
                        ]"
                    />
                </x-slot:breadcrumbs>
            </x-ui.page-header>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
        <!-- Feature Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featureModules as $feat)
                <x-ui.card padding="p-6" class="flex flex-col justify-between hover:border-brand-300">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-200/60">
                                <x-icon :name="$feat['icon']" class="w-5 h-5" />
                            </div>
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-brand-700 bg-brand-50 px-2 py-0.5 rounded border border-brand-200">
                                {{ $feat['badge'] }}
                            </span>
                        </div>

                        <h3 class="font-heading font-bold text-lg text-ink-950 mb-2">
                            {{ $feat['title'] }}
                        </h3>

                        <p class="text-xs text-ink-600 font-sans leading-relaxed mb-4">
                            {{ $feat['description'] }}
                        </p>

                        <div class="space-y-1.5 pt-3 border-t border-border-subtle text-xs font-sans">
                            @foreach($feat['highlights'] as $high)
                                <div class="flex items-center gap-2 text-ink-700">
                                    <x-icon name="check" class="w-3.5 h-3.5 text-success-600 shrink-0" />
                                    <span>{{ $high }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-ui.card>
            @endforeach
        </div>

        <!-- Interactive Feature Spotlight: Document Locker & 1-Link Banking -->
        <section class="p-8 md:p-12 bg-surface rounded-3xl border border-border-default shadow-card">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-7 space-y-4">
                    <span class="text-xs font-mono font-bold uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                        Institutional Payment Gateway
                    </span>
                    <h3 class="text-2xl sm:text-3xl font-heading font-bold text-ink-950 tracking-tight">
                        Integrated with 1-Link, 1-Bill, and National Banking Systems
                    </h3>
                    <p class="text-xs sm:text-sm text-ink-600 font-sans leading-relaxed">
                        Never stand in bank lines during admission cutoff rushes. Download official institutional fee challans with automatic 1-Bill reference codes payable through any mobile banking app (HBL, Meezan, Bank Alfalah, Easypaisa, Nayapay, or JazzCash).
                    </p>
                    <div class="pt-2 flex items-center gap-4 text-xs font-sans text-ink-700">
                        <span class="flex items-center gap-1.5 font-semibold text-emerald-700">
                            <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600" />
                            Instant Receipt Generation
                        </span>
                        <span class="flex items-center gap-1.5 font-semibold text-emerald-700">
                            <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600" />
                            Direct University Portal Sync
                        </span>
                    </div>
                </div>

                <div class="lg:col-span-5 bg-surface-subtle p-6 rounded-2xl border border-border-default text-center space-y-3">
                    <x-icon name="receipt" class="w-12 h-12 text-brand-600 mx-auto" />
                    <span class="font-heading font-bold text-base text-ink-950 block">1-Bill National Integration</span>
                    <p class="text-xs text-ink-500 font-sans">Automated challan verification for 240+ institutions</p>
                    <div class="font-mono text-xs font-bold text-ink-800 bg-surface px-3 py-1.5 rounded-lg border border-border-default inline-block">
                        1-Bill Prefix: 100248-XXXX-XXXX
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <div class="text-center pt-6">
            <x-ui.button variant="primary" size="lg" :href="route('auth.register')" iconRight="arrow-right">
                Create Free Account to Use All Features
            </x-ui.button>
        </div>
    </div>
</x-layouts.public>

