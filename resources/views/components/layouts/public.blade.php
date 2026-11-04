@props([
    'title' => 'UniGuide Pakistan — National Higher Education Discovery',
    'description' => null,
])

<x-layouts.base :title="$title" :description="$description">
    <!-- Public Navbar -->
    <x-nav.public-navbar />

    <!-- Main Content Area -->
    <main id="main-content" class="flex-1 w-full">
        {{ $slot }}
    </main>

    <!-- Public Editorial Footer -->
    <footer class="bg-surface border-t border-border-default mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-8 md:gap-12 pb-12 border-b border-border-subtle">
                <!-- Col 1 & 2: Brand Identity -->
                <div class="md:col-span-2 space-y-4">
                    <x-brand.wordmark size="md" :href="route('home')" />
                    <p class="text-xs text-ink-600 font-sans max-w-sm leading-relaxed">
                        UniGuide Pakistan is the national higher education intelligence platform, connecting students across Punjab, Sindh, Khyber Pakhtunkhwa, Balochistan, Islamabad Capital Territory, Gilgit-Baltistan, and Azad Jammu & Kashmir with accredited degree programs and verified admissions.
                    </p>
                    <div class="flex items-center gap-2 pt-1">
                        <x-domain.verification-badge type="hec_recognized" detail="National Registry Sync" size="sm" />
                    </div>
                </div>

                <!-- Col 3: Discovery Domains -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        Institutions & Programs
                    </h4>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('universities.index') }}" class="hover:text-brand-600 transition-colors">National University Directory</a></li>
                        <li><a href="{{ route('programs.index') }}" class="hover:text-brand-600 transition-colors">Academic Program Explorer</a></li>
                        <li><a href="{{ route('admissions.index') }}" class="hover:text-brand-600 transition-colors">Admissions & Deadlines</a></li>
                        <li><a href="{{ route('scholarships.index') }}" class="hover:text-brand-600 transition-colors">National Scholarships Directory</a></li>
                        <li><a href="{{ route('compare.index') }}" class="hover:text-brand-600 transition-colors">University Comparison Matrix</a></li>
                    </ul>
                </div>

                <!-- Col 4: Admissions & Funding -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        Student Guidance
                    </h4>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('counselor.index') }}" class="hover:text-brand-600 transition-colors">AI Admission Counselor</a></li>
                        <li><a href="{{ route('eligibility.index') }}" class="hover:text-brand-600 transition-colors">Merit & Eligibility Checker</a></li>
                        <li><a href="{{ route('admissions.calendar') }}" class="hover:text-brand-600 transition-colors">Admission Calendar</a></li>
                        <li><a href="{{ route('admissions.checklist') }}" class="hover:text-brand-600 transition-colors">Document Checklist</a></li>
                        <li><a href="{{ route('marketing.how_it_works') }}" class="hover:text-brand-600 transition-colors">How Admissions Work</a></li>
                    </ul>
                </div>

                <!-- Col 5: Governance & Administrative -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        Platform & Charters
                    </h4>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('marketing.about') }}" class="hover:text-brand-600 transition-colors">About UniGuide</a></li>
                        <li><a href="{{ route('marketing.features') }}" class="hover:text-brand-600 transition-colors">Platform Capabilities</a></li>
                        <li><a href="{{ route('marketing.contact') }}" class="hover:text-brand-600 transition-colors">Contact & Admissions Desk</a></li>
                        <li><a href="{{ route('uni_admin.dashboard') }}" class="hover:text-brand-600 transition-colors">University Admin Console</a></li>
                        <li><a href="{{ route('national_admin.dashboard') }}" class="hover:text-brand-600 transition-colors">HEC National Command</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Disclaimer -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs font-sans text-ink-500">
                <p>
                    © {{ date('Y') }} UniGuide Pakistan. All rights reserved. Data verified against official provincial charters and gazetted fee schedules.
                </p>
                <div class="flex items-center gap-4 text-[11px]">
                    <a href="{{ route('marketing.about') }}" class="hover:text-ink-900 transition-colors">Data Transparency</a>
                    <span class="text-ink-300">•</span>
                    <a href="{{ route('marketing.contact') }}" class="hover:text-ink-900 transition-colors">Institutional Inquiries</a>
                    <span class="text-ink-300">•</span>
                    <a href="{{ route('design-system') }}" class="hover:text-ink-900 transition-colors">Design System</a>
                </div>
            </div>
        </div>
    </footer>
</x-layouts.base>
