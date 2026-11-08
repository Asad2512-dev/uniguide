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
                        {{ content('global.brand.description', config('brand.seo.default_description')) }}
                    </p>
                    <div class="flex items-center gap-2 pt-1">
                        <x-domain.verification-badge type="hec_recognized" :detail="content('global.brand.verification_badge', config('brand.legal.regulatory_sync'))" size="sm" />
                    </div>
                </div>

                <!-- Col 3: Discovery Domains -->
                <details open x-data x-init="const query = window.matchMedia('(min-width: 640px)'); $el.open = query.matches; query.addEventListener('change', event => $el.open = event.matches)" @toggle="if (window.innerWidth >= 640) $el.open = true" class="space-y-3">
                    <summary class="flex min-h-11 cursor-pointer list-none items-center justify-between gap-3 sm:cursor-default"><h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        {{ content('global.footer.institutions_programs_title', 'Institutions & Programs') }}
                    </h4><x-icon name="chevron-down" class="size-4 sm:hidden" /></summary>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('universities.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.university_directory', 'University Directory') }}</a></li>
                        <li><a href="{{ route('programs.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.program_explorer', 'Academic Program Explorer') }}</a></li>
                        <li><a href="{{ route('admissions.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.admissions_deadlines', 'Admissions & Deadlines') }}</a></li>
                        <li><a href="{{ route('scholarships.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.scholarships_directory', 'National Scholarships Directory') }}</a></li>
                        <li><a href="{{ route('compare.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.comparison_matrix', 'University Comparison Matrix') }}</a></li>
                    </ul>
                </details>

                <!-- Col 4: Admissions & Funding -->
                <details open x-data x-init="const query = window.matchMedia('(min-width: 640px)'); $el.open = query.matches; query.addEventListener('change', event => $el.open = event.matches)" @toggle="if (window.innerWidth >= 640) $el.open = true" class="space-y-3">
                    <summary class="flex min-h-11 cursor-pointer list-none items-center justify-between gap-3 sm:cursor-default"><h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        {{ content('global.footer.student_guidance_title', 'Student Guidance') }}
                    </h4><x-icon name="chevron-down" class="size-4 sm:hidden" /></summary>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('counselor.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.ai_counselor', 'AI Admission Counselor') }}</a></li>
                        <li><a href="{{ route('eligibility.index') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.eligibility_checker', 'Merit & Eligibility Checker') }}</a></li>
                        <li><a href="{{ route('admissions.calendar') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.admission_calendar', 'Admission Calendar') }}</a></li>
                        <li><a href="{{ route('admissions.checklist') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.document_checklist', 'Document Checklist') }}</a></li>
                        <li><a href="{{ route('marketing.how_it_works') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.how_admissions_work', 'How Admissions Work') }}</a></li>
                    </ul>
                </details>

                <!-- Col 5: Governance & Administrative -->
                <details open x-data x-init="const query = window.matchMedia('(min-width: 640px)'); $el.open = query.matches; query.addEventListener('change', event => $el.open = event.matches)" @toggle="if (window.innerWidth >= 640) $el.open = true" class="space-y-3">
                    <summary class="flex min-h-11 cursor-pointer list-none items-center justify-between gap-3 sm:cursor-default"><h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        {{ content('global.footer.platform_charters_title', 'Platform & Charters') }}
                    </h4><x-icon name="chevron-down" class="size-4 sm:hidden" /></summary>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('marketing.about') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.about_uniguide', 'About UniGuide') }}</a></li>
                        <li><a href="{{ route('marketing.features') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.platform_capabilities', 'Platform Capabilities') }}</a></li>
                        <li><a href="{{ route('marketing.contact') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.contact_desk', 'Contact & Admissions Desk') }}</a></li>
                        <li><a href="{{ route('uni_admin.dashboard') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.uni_admin_console', 'University Admin Console') }}</a></li>
                        <li><a href="{{ route('national_admin.dashboard') }}" class="inline-flex min-h-11 items-center hover:text-brand-600 transition-colors">{{ content('global.footer.superadmin', 'Superadmin') }}</a></li>
                    </ul>
                </details>
            </div>

            <!-- Bottom Disclaimer -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs font-sans text-ink-500">
                <p>
                    {{ content('global.brand.copyright', config('brand.legal.copyright'), ['year' => date('Y')]) }}
                </p>
                <div class="flex flex-wrap justify-center items-center gap-4 text-xs sm:text-[11px]">
                    <a href="{{ route('marketing.about') }}" class="hover:text-ink-900 transition-colors">{{ content('global.footer.data_transparency', 'Data Transparency') }}</a>
                    <span class="text-ink-300">•</span>
                    <a href="{{ route('marketing.contact') }}" class="hover:text-ink-900 transition-colors">{{ content('global.footer.institutional_inquiries', 'Institutional Inquiries') }}</a>
                    <span class="text-ink-300">•</span>
                    <a href="{{ route('design-system') }}" class="hover:text-ink-900 transition-colors">{{ content('global.footer.design_system', 'Design System') }}</a>
                </div>
            </div>
        </div>
    </footer>
    @endunless
</x-layouts.base>
