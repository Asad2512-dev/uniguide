@props([
    'title' => 'UniGuide Pakistan — National Higher Education Discovery',
    'description' => null,
    'workspace' => false,
])

<x-layouts.base :title="$title" :description="$description">
    <!-- Public Navbar -->
    <x-nav.public-navbar />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-1 w-full">
        {{ $slot }}
    </main>

    @unless($workspace)
    <!-- Public Editorial Footer -->
    <footer class="bg-surface border-t border-border-default mt-20">
        <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-12 pb-12 border-b border-border-subtle">
                <!-- Col 1 & 2: Brand Identity -->
                <div class="sm:col-span-2 space-y-4">
                    <x-brand.wordmark size="md" :href="route('home')" />
                    <p class="text-xs text-ink-600 font-sans max-w-sm leading-relaxed">
                        Discover universities and admission opportunities across Punjab and Islamabad.
                    </p>
                    <div class="flex items-center gap-2 pt-1">
                        <x-domain.verification-badge type="hec_recognized" detail="National Registry Sync" size="sm" />
                    </div>
                </div>

                <!-- Col 3: Discovery Domains -->
                <details open x-data x-init="const query = window.matchMedia('(min-width: 640px)'); $el.open = query.matches; query.addEventListener('change', event => $el.open = event.matches)" @toggle="if (window.innerWidth >= 640) $el.open = true" class="space-y-3">
                    <summary class="flex min-h-11 cursor-pointer list-none items-center justify-between gap-3 sm:cursor-default"><h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        Institutions & Programs
                    </h4><x-icon name="chevron-down" class="size-4 sm:hidden" /></summary>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('universities.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">University Directory</a></li>
                        <li><a href="{{ route('programs.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">Academic Program Explorer</a></li>
                        <li><a href="{{ route('admissions.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">Admissions & Deadlines</a></li>
                        <li><a href="{{ route('scholarships.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">National Scholarships Directory</a></li>
                        <li><a href="{{ route('compare.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">University Comparison Matrix</a></li>
                    </ul>
                </details>

                <!-- Col 4: Admissions & Funding -->
                <details open x-data x-init="const query = window.matchMedia('(min-width: 640px)'); $el.open = query.matches; query.addEventListener('change', event => $el.open = event.matches)" @toggle="if (window.innerWidth >= 640) $el.open = true" class="space-y-3">
                    <summary class="flex min-h-11 cursor-pointer list-none items-center justify-between gap-3 sm:cursor-default"><h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        Student Guidance
                    </h4><x-icon name="chevron-down" class="size-4 sm:hidden" /></summary>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('counselor.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">AI Admission Counselor</a></li>
                        <li><a href="{{ route('eligibility.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">Merit & Eligibility Checker</a></li>
                        <li><a href="{{ route('admissions.calendar') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">Admission Calendar</a></li>
                        <li><a href="{{ route('admissions.checklist') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">Document Checklist</a></li>
                        <li><a href="{{ route('marketing.how_it_works') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">How Admissions Work</a></li>
                    </ul>
                </details>

                <!-- Col 5: Governance & Administrative -->
                <details open x-data x-init="const query = window.matchMedia('(min-width: 640px)'); $el.open = query.matches; query.addEventListener('change', event => $el.open = event.matches)" @toggle="if (window.innerWidth >= 640) $el.open = true" class="space-y-3">
                    <summary class="flex min-h-11 cursor-pointer list-none items-center justify-between gap-3 sm:cursor-default"><h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        Platform & Charters
                    </h4><x-icon name="chevron-down" class="size-4 sm:hidden" /></summary>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('marketing.about') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">About UniGuide</a></li>
                        <li><a href="{{ route('marketing.features') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">Platform Capabilities</a></li>
                        <li><a href="{{ route('marketing.contact') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">Contact & Admissions Desk</a></li>
                        <li><a href="{{ route('uni_admin.dashboard') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">University Admin Console</a></li>
                        <li><a href="{{ route('national_admin.dashboard') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">Superadmin</a></li>
                    </ul>
                </details>
            </div>

            <!-- Bottom Disclaimer -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs font-sans text-ink-500">
                <p>
                    © {{ date('Y') }} UniGuide Pakistan. All rights reserved. Data verified against official provincial charters and gazetted fee schedules.
                </p>
                <div class="flex flex-wrap justify-center items-center gap-4 text-xs sm:text-[11px]">
                    <a href="{{ route('marketing.about') }}" class="hover:text-ink-900 transition-colors">Data Transparency</a>
                    <span class="text-ink-300">•</span>
                    <a href="{{ route('marketing.contact') }}" class="hover:text-ink-900 transition-colors">Institutional Inquiries</a>
                    <span class="text-ink-300">•</span>
                    <a href="{{ route('design-system') }}" class="hover:text-ink-900 transition-colors">Design System</a>
                </div>
            </div>
        </div>
    </footer>
    @endunless
</x-layouts.base>
