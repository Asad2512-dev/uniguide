<x-layouts.public 
    title="Platform Features — UniGuide Pakistan"
    description="Explore the technological capabilities of UniGuide: multi-parameter search, aggregate calculator, side-by-side comparison, and document locker."
>
    <!-- Header -->
    <section class="pt-12 pb-14 border-b border-border-default bg-surface">
        <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8">
            <x-ui.page-header 
                :title="content('features.header.title', 'UniGuide Platform Capabilities')"
                :description="content('features.header.description', 'Designed specifically for the realities of higher education in Pakistan: multiple examination boards, decentralized tests, and strict guidelines.')"
                :badge="content('features.header.badge', 'SaaS-Scale Architecture')"
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

    <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
        <!-- Feature Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featureModules as $feat)
                <x-ui.card padding="p-6" class="flex flex-col justify-between hover:border-brand-300">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center border border-brand-200/60">
                                <x-icon :name="$feat['icon']" class="w-5 h-5" />
                            </div>
                            <span class="text-xs sm:text-[10px] font-mono font-bold uppercase tracking-wider text-brand-700 bg-brand-50 px-2 py-0.5 rounded border border-brand-200">
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

        <section class="rounded-2xl border border-border-default p-6"><h2 class="text-xl font-semibold">Your university application stays with the institution</h2><p class="mt-3 text-sm text-ink-600">UniGuide helps you research and track your plans. Applications and fee payments are completed through official university channels.</p></section>
    </div>
</x-layouts.public>

