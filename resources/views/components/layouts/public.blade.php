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
                    <x-brand.wordmark size="md" />
                    <p class="text-xs text-ink-600 font-sans max-w-sm leading-relaxed">
                        UniGuide Pakistan is the national-level higher education intelligence portal, connecting students across Punjab, Sindh, Khyber Pakhtunkhwa, Balochistan, Islamabad Capital Territory, Gilgit-Baltistan, and Azad Jammu & Kashmir with accredited degree programs and verified admissions.
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
                        <li><a href="{{ route('design-system') }}#domain-cards" class="hover:text-brand-600 transition-colors">Public Sector Universities</a></li>
                        <li><a href="{{ route('design-system') }}#domain-cards" class="hover:text-brand-600 transition-colors">Chartered Private Universities</a></li>
                        <li><a href="{{ route('design-system') }}#domain-cards" class="hover:text-brand-600 transition-colors">Engineering (PEC Accredited)</a></li>
                        <li><a href="{{ route('design-system') }}#domain-cards" class="hover:text-brand-600 transition-colors">Medical & Dental (PMDC)</a></li>
                        <li><a href="{{ route('design-system') }}#domain-cards" class="hover:text-brand-600 transition-colors">Computing & IT (NCEAC)</a></li>
                    </ul>
                </div>

                <!-- Col 4: Admissions & Funding -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        Student Guidance
                    </h4>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('design-system') }}#data-visualizations" class="hover:text-brand-600 transition-colors">Aggregate Merit Formulae</a></li>
                        <li><a href="{{ route('design-system') }}#domain-cards" class="hover:text-brand-600 transition-colors">Ehsaas & HEC Scholarships</a></li>
                        <li><a href="{{ route('design-system') }}#ai-guidance" class="hover:text-brand-600 transition-colors">AI Admission Counselor</a></li>
                        <li><a href="{{ route('preview.student') }}" class="hover:text-brand-600 transition-colors">Student Document Locker</a></li>
                        <li><a href="{{ route('design-system') }}#data-tables" class="hover:text-brand-600 transition-colors">University Comparison Matrix</a></li>
                    </ul>
                </div>

                <!-- Col 5: Governance & Administrative -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-ink-950 uppercase tracking-wider font-sans">
                        Platform & Charters
                    </h4>
                    <ul class="space-y-2 text-xs font-sans text-ink-600">
                        <li><a href="{{ route('preview.university_admin') }}" class="hover:text-brand-600 transition-colors">University Admin Console</a></li>
                        <li><a href="{{ route('preview.national_admin') }}" class="hover:text-brand-600 transition-colors">HEC National Command</a></li>
                        <li><a href="{{ route('design-system') }}" class="hover:text-brand-600 transition-colors">Design System Specifications</a></li>
                        <li><span class="text-ink-400">Statutory Gazette Registry</span></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Disclaimer -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs font-sans text-ink-500">
                <p>
                    © {{ date('Y') }} UniGuide Pakistan. All rights reserved. Data verified against official provincial charters and gazetted fee schedules.
                </p>
                <div class="flex items-center gap-4 text-[11px]">
                    <a href="#" class="hover:text-ink-900 transition-colors">Privacy Policy</a>
                    <span class="text-ink-300">•</span>
                    <a href="#" class="hover:text-ink-900 transition-colors">Statutory Terms</a>
                    <span class="text-ink-300">•</span>
                    <a href="#" class="hover:text-ink-900 transition-colors">Verification Protocol</a>
                </div>
            </div>
        </div>
    </footer>
</x-layouts.base>
