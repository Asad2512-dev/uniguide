<x-layouts.public 
    :title="$campus['name'] . ' — ' . $university['acronym'] . ' — UniGuide Pakistan'"
    :description="'Campus details, student hostels, transport routes, and degree offerings for ' . $campus['name'] . ' of ' . $university['name']"
>
    <div class="min-h-screen bg-canvas pb-24">
        
        <!-- Header Banner -->
        <header class="bg-surface border-b border-border-default/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Universities', 'url' => route('universities.index')],
                        ['label' => $university['acronym'], 'url' => route('universities.show', $university['slug'])],
                        ['label' => $campus['name'], 'url' => route('universities.campuses.show', ['slug' => $university['slug'], 'campusSlug' => $campus['slug']])]
                    ]" />
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <a 
                                href="{{ route('universities.show', $university['slug']) }}" 
                                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-brand-50 text-brand-700 border border-brand-200/60 font-sans hover:bg-brand-100 transition-colors"
                            >
                                <x-icon name="building-2" class="w-3.5 h-3.5" />
                                <span>{{ $university['name'] }}</span>
                            </a>

                            @if($campus['is_main'])
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-success-50 text-success-700 border border-success-200/60 font-sans">
                                    Principal Main Campus
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-surface-subtle text-ink-600 border border-border-default font-sans">
                                    Regional Constituent Campus
                                </span>
                            @endif
                        </div>

                        <h1 class="font-heading font-extrabold text-2xl sm:text-3xl md:text-4xl text-ink-950 tracking-tight">
                            {{ $campus['name'] }}
                        </h1>

                        <p class="text-xs sm:text-sm font-sans text-ink-600 mt-2 flex items-center gap-1.5">
                            <x-icon name="map-pin" class="w-4 h-4 text-ink-400 shrink-0" />
                            <span>{{ $campus['address'] }}</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <x-ui.button variant="outline" size="sm" :href="route('universities.show', $university['slug'])">
                            ← Institutional Profile
                        </x-ui.button>
                        <a 
                            href="tel:{{ $campus['phone'] }}" 
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-ink-900 text-white text-xs font-semibold hover:bg-brand-600 transition-smooth shadow-xs"
                        >
                            <x-icon name="phone" class="w-3.5 h-3.5" />
                            <span>Contact Campus</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left 2 Cols: Campus Facilities, Hostels, Transport, Programs -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Highlights Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-5 rounded-2xl bg-surface border border-border-default shadow-2xs space-y-1">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600 mb-2">
                                <x-icon name="bed" class="w-4 h-4" />
                            </div>
                            <span class="block text-xs font-semibold text-ink-500 font-sans">Student Accommodation</span>
                            <span class="font-heading font-bold text-sm text-ink-950 block">{{ $campus['hostel_capacity'] }}</span>
                        </div>

                        <div class="p-5 rounded-2xl bg-surface border border-border-default shadow-2xs space-y-1">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600 mb-2">
                                <x-icon name="bus" class="w-4 h-4" />
                            </div>
                            <span class="block text-xs font-semibold text-ink-500 font-sans">Conveyance & Transport</span>
                            <span class="font-heading font-bold text-sm text-ink-950 block">{{ $campus['transport'] }}</span>
                        </div>

                        <div class="p-5 rounded-2xl bg-surface border border-border-default shadow-2xs space-y-1">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600 mb-2">
                                <x-icon name="book-open" class="w-4 h-4" />
                            </div>
                            <span class="block text-xs font-semibold text-ink-500 font-sans">Research Libraries</span>
                            <span class="font-heading font-bold text-sm text-ink-950 block">{{ $campus['libraries'] }}</span>
                        </div>
                    </div>

                    <!-- Facilities & Centers -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-4">
                        <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                            <x-icon name="building" class="w-5 h-5 text-brand-600" />
                            <span>Campus Labs & Dedicated Facilities</span>
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                            @foreach($campus['facilities'] as $fac)
                                <div class="flex items-center gap-3 p-3.5 rounded-xl bg-surface-subtle border border-border-subtle text-xs sm:text-sm font-sans text-ink-800">
                                    <x-icon name="check-circle-2" class="w-4 h-4 text-brand-600 shrink-0" />
                                    <span class="font-medium">{{ $fac }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Campus Programs Catalog -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-2xs space-y-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="font-heading font-bold text-lg text-ink-950 flex items-center gap-2">
                                    <x-icon name="graduation-cap" class="w-5 h-5 text-brand-600" />
                                    <span>Degrees Offered at this Campus</span>
                                </h2>
                                <p class="text-xs text-ink-500 font-sans mt-0.5">
                                    Accredited academic degrees with physical lectures conducted on this site.
                                </p>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-surface-subtle border border-border-default text-ink-700">
                                {{ count($campus['programs'] ?? []) }} Offered
                            </span>
                        </div>

                        <div class="space-y-3">
                            @forelse($campus['programs'] ?? [] as $prog)
                                <div class="p-4 rounded-xl border border-border-default hover:border-brand-300 hover:shadow-2xs transition-smooth flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[10px] font-mono font-semibold uppercase px-1.5 py-0.2 rounded bg-brand-50 text-brand-700 border border-brand-200/60">
                                                {{ $prog['degree_level'] }}
                                            </span>
                                            <h3 class="font-heading font-bold text-sm text-ink-950 hover:text-brand-600 transition-colors">
                                                <a href="{{ route('programs.show', $prog['slug']) }}">{{ $prog['title'] }}</a>
                                            </h3>
                                        </div>
                                        <p class="text-xs text-ink-500 font-sans">
                                            {{ $prog['discipline_label'] }} • Closing Merit: <span class="font-bold text-brand-700">{{ $prog['cutoff_merit'] }}</span>
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-3 shrink-0">
                                        <span class="font-mono font-bold text-xs text-ink-900 hidden sm:inline">{{ $prog['fee_semester'] }}</span>
                                        <x-ui.button variant="outline" size="xs" :href="route('programs.show', $prog['slug'])">
                                            Program Details
                                        </x-ui.button>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 rounded-xl bg-surface-subtle text-xs text-ink-500 font-sans">
                                    Please refer to the full university profile to see all offerings across affiliated campuses.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <!-- Right 1 Col: Campus Contact & Parent Info -->
                <aside class="space-y-6">
                    
                    <!-- Contact Box -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 shadow-2xs space-y-4">
                        <h3 class="font-heading font-bold text-sm text-ink-950 flex items-center gap-2">
                            <x-icon name="phone-call" class="w-4 h-4 text-brand-600" />
                            <span>Campus Administrative Office</span>
                        </h3>

                        <div class="space-y-3 text-xs font-sans text-ink-600">
                            <div>
                                <span class="block text-[11px] font-medium text-ink-400 mb-0.5">Physical Campus Address</span>
                                <span class="text-ink-800">{{ $campus['address'] }}</span>
                            </div>
                            <div>
                                <span class="block text-[11px] font-medium text-ink-400 mb-0.5">Campus Direct Inquiries</span>
                                <a href="tel:{{ $campus['phone'] }}" class="font-mono text-brand-600 hover:underline">
                                    {{ $campus['phone'] }}
                                </a>
                            </div>
                            <div>
                                <span class="block text-[11px] font-medium text-ink-400 mb-0.5">Campus Admissions Desk Email</span>
                                <a href="mailto:{{ $campus['email'] }}" class="text-brand-600 hover:underline">
                                    {{ $campus['email'] }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Parent Institution Summary -->
                    <div class="bg-surface rounded-2xl border border-border-default p-6 shadow-2xs space-y-3">
                        <span class="text-xs font-semibold text-ink-400 uppercase tracking-wider font-sans">Parent Institution</span>
                        <h3 class="font-heading font-bold text-base text-ink-950">
                            {{ $university['name'] }}
                        </h3>
                        <p class="text-xs text-ink-600 font-sans">
                            Chartered under {{ $university['charter_citation'] }}.
                        </p>
                        <div class="pt-2">
                            <x-ui.button variant="outline" size="sm" :href="route('universities.show', $university['slug'])" class="w-full">
                                Institutional Directory Record
                            </x-ui.button>
                        </div>
                    </div>

                </aside>

            </div>
        </main>

    </div>
</x-layouts.public>

