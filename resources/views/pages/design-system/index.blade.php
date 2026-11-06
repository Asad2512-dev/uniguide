<x-layouts.public title="UniGuide Pakistan — Frontend Architecture & Living Design System">
    <!-- Top Announcement Banner -->
    <div class="bg-gradient-to-r from-brand-900 via-brand-800 to-indigo-950 text-white py-3 px-4 sm:px-6">
        <div class="landing-container mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs font-sans">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-white/20 text-white uppercase tracking-wider">
                    Phase Foundation Complete
                </span>
                <span class="font-medium text-brand-100">
                    UniGuide Pakistan Frontend Architecture, Design Tokens & Global Blade Component Suite.
                </span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-white/60">Active Frameworks:</span>
                <span class="font-mono text-white/90 font-semibold">Laravel 13 Blade • Tailwind v4 • Alpine.js • Vite • Lucide • Chart.js</span>
            </div>
        </div>
    </div>

    <!-- Design System Hero Header -->
    <section class="pt-12 pb-14 border-b border-border-default bg-surface">
        <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                <div class="space-y-4 max-w-3xl">
                    <div class="flex items-center gap-2.5">
                        <x-brand.logo size="lg" />
                        <div>
                            <span class="text-[11px] font-mono uppercase tracking-widest font-bold text-brand-700 bg-brand-50 px-2.5 py-0.5 rounded-full border border-brand-200/60">
                                Global Design System v1.0
                            </span>
                            <h1 class="text-3xl md:text-4xl font-heading font-extrabold text-ink-950 tracking-tight mt-1">
                                UniGuide Pakistan Design System
                            </h1>
                        </div>
                    </div>
                    <p class="text-sm md:text-base text-ink-600 font-sans leading-relaxed">
                        A national-level, commercial SaaS frontend architecture built strictly with <strong>Laravel Blade</strong>, <strong>Tailwind CSS</strong>, <strong>Alpine.js</strong>, and <strong>Chart.js</strong>. Clean light mode palette, verified editorial typography, WCAG 2.2 AA accessibility, and zero external frontend frameworks.
                    </p>
                </div>

                <!-- Interactive Layout Shell Switcher Card -->
                <div class="bg-surface-subtle p-5 rounded-2xl border border-border-default shadow-card shrink-0 lg:w-96 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-ink-900 font-sans uppercase tracking-wider">
                            Interactive Application Shells
                        </span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    </div>
                    <p class="text-xs text-ink-500 font-sans">
                        Preview all four independent Blade layout shells sharing this design system:
                    </p>
                    <div class="grid grid-cols-2 gap-2 text-xs font-sans">
                        <x-ui.button variant="outline" size="xs" :href="route('preview.public')" icon="compass" class="w-full justify-start">
                            Public Shell
                        </x-ui.button>
                        <x-ui.button variant="outline" size="xs" :href="route('preview.student')" icon="graduation-cap" class="w-full justify-start">
                            Student Shell
                        </x-ui.button>
                        <x-ui.button variant="outline" size="xs" :href="route('preview.university_admin')" icon="building-2" class="w-full justify-start">
                            Univ. Admin
                        </x-ui.button>
                        <x-ui.button variant="outline" size="xs" :href="route('preview.national_admin')" icon="shield-check" class="w-full justify-start">
                            Superadmin
                        </x-ui.button>
                    </div>
                </div>
            </div>

            <!-- Jump to Section Nav Bar -->
            <div class="mt-10 pt-4 border-t border-border-subtle flex items-center space-x-2 md:space-x-6 overflow-x-auto text-xs font-semibold font-sans text-ink-600">
                <a href="#foundations" class="hover:text-brand-600 py-2 whitespace-nowrap">1. Foundations & Tokens</a>
                <a href="#buttons-actions" class="hover:text-brand-600 py-2 whitespace-nowrap">2. Buttons & Actions</a>
                <a href="#form-controls" class="hover:text-brand-600 py-2 whitespace-nowrap">3. Form Controls</a>
                <a href="#status-badges" class="hover:text-brand-600 py-2 whitespace-nowrap">4. Badges & Indicators</a>
                <a href="#domain-cards" class="hover:text-brand-600 py-2 whitespace-nowrap">5. Domain Cards</a>
                <a href="#ai-guidance" class="hover:text-brand-600 py-2 whitespace-nowrap">6. AI Guidance</a>
                <a href="#data-tables" class="hover:text-brand-600 py-2 whitespace-nowrap">7. Tables & Navigation</a>
                <a href="#feedback-overlays" class="hover:text-brand-600 py-2 whitespace-nowrap">8. Overlays & States</a>
                <a href="#data-visualizations" class="hover:text-brand-600 py-2 whitespace-nowrap">9. Data Visualizations</a>
            </div>
        </div>
    </section>

    <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-20">

        <!-- ========================================== -->
        <!-- SECTION 1: FOUNDATIONS & DESIGN TOKENS -->
        <!-- ========================================== -->
        <section id="foundations" class="scroll-mt-24 space-y-8">
            <x-ui.section-header 
                eyebrow="Foundation & Tokens"
                title="Color Architecture & Typography"
                description="Engineered for high readability, light mode sophistication, editorial prestige, and accessible contrast ratios (WCAG 2.2 AA compliant)."
            />

            <!-- Color Palette Swatches -->
            <div class="space-y-4">
                <h3 class="text-sm font-bold font-heading text-ink-950 uppercase tracking-wider">
                    Core Color Tokens
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    <div class="p-3 bg-surface rounded-xl border border-border-default shadow-2xs space-y-2">
                        <div class="h-14 rounded-lg bg-[#FAFBFD] border border-border-default flex items-center justify-center text-xs font-mono text-ink-600">
                            #FAFBFD
                        </div>
                        <div>
                            <p class="text-xs font-bold text-ink-950 font-sans">Canvas</p>
                            <p class="text-[10px] text-ink-400 font-mono">--color-canvas</p>
                        </div>
                    </div>

                    <div class="p-3 bg-surface rounded-xl border border-border-default shadow-2xs space-y-2">
                        <div class="h-14 rounded-lg bg-[#FFFFFF] border border-border-default flex items-center justify-center text-xs font-mono text-ink-900 shadow-2xs">
                            #FFFFFF
                        </div>
                        <div>
                            <p class="text-xs font-bold text-ink-950 font-sans">Surface</p>
                            <p class="text-[10px] text-ink-400 font-mono">--color-surface</p>
                        </div>
                    </div>

                    <div class="p-3 bg-surface rounded-xl border border-border-default shadow-2xs space-y-2">
                        <div class="h-14 rounded-lg bg-[#4355E8] flex items-center justify-center text-xs font-mono text-white font-bold">
                            #4355E8
                        </div>
                        <div>
                            <p class="text-xs font-bold text-ink-950 font-sans">Primary Brand</p>
                            <p class="text-[10px] text-ink-400 font-mono">--color-brand-600</p>
                        </div>
                    </div>

                    <div class="p-3 bg-surface rounded-xl border border-border-default shadow-2xs space-y-2">
                        <div class="h-14 rounded-lg bg-[#303AB2] flex items-center justify-center text-xs font-mono text-white font-bold">
                            #303AB2
                        </div>
                        <div>
                            <p class="text-xs font-bold text-ink-950 font-sans">Deep Brand</p>
                            <p class="text-[10px] text-ink-400 font-mono">--color-brand-800</p>
                        </div>
                    </div>

                    <div class="p-3 bg-surface rounded-xl border border-border-default shadow-2xs space-y-2">
                        <div class="h-14 rounded-lg bg-[#12866C] flex items-center justify-center text-xs font-mono text-white font-bold">
                            #12866C
                        </div>
                        <div>
                            <p class="text-xs font-bold text-ink-950 font-sans">Emerald Success</p>
                            <p class="text-[10px] text-ink-400 font-mono">--color-success-500</p>
                        </div>
                    </div>

                    <div class="p-3 bg-surface rounded-xl border border-border-default shadow-2xs space-y-2">
                        <div class="h-14 rounded-lg bg-[#C58B24] flex items-center justify-center text-xs font-mono text-white font-bold">
                            #C58B24
                        </div>
                        <div>
                            <p class="text-xs font-bold text-ink-950 font-sans">Amber Warning</p>
                            <p class="text-[10px] text-ink-400 font-mono">--color-warning-500</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Typography Hierarchy Preview -->
            <div class="p-6 bg-surface rounded-2xl border border-border-default shadow-card space-y-6">
                <h3 class="text-sm font-bold font-heading text-ink-950 uppercase tracking-wider border-b border-border-subtle pb-3">
                    Typography Scale: Plus Jakarta Sans (Headings) & Inter (UI)
                </h3>

                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 pb-3 border-b border-border-subtle">
                        <span class="text-3xl sm:text-4xl font-heading font-extrabold text-ink-950 tracking-tight">
                            Heading 1 / Display • 36px – 48px
                        </span>
                        <span class="text-xs font-mono text-ink-400">Plus Jakarta Sans 800</span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 pb-3 border-b border-border-subtle">
                        <span class="text-2xl sm:text-3xl font-heading font-bold text-ink-950 tracking-tight">
                            Heading 2 / Section • 24px – 30px
                        </span>
                        <span class="text-xs font-mono text-ink-400">Plus Jakarta Sans 700</span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1 pb-3 border-b border-border-subtle">
                        <span class="text-lg sm:text-xl font-heading font-bold text-ink-950">
                            Heading 3 / Card Title • 18px – 20px
                        </span>
                        <span class="text-xs font-mono text-ink-400">Plus Jakarta Sans 600</span>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-1">
                        <p class="text-sm text-ink-700 font-sans max-w-2xl leading-relaxed">
                            Body Text: Inter Regular 14px. Used for all core interface narratives, data labels, and descriptive paragraphs. Maintains high legibility even in dense academic datasets.
                        </p>
                        <span class="text-xs font-mono text-ink-400">Inter 400 / 500</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- SECTION 2: BUTTONS & ACTION CONTROLS -->
        <!-- ========================================== -->
        <section id="buttons-actions" class="scroll-mt-24 space-y-8">
            <x-ui.section-header 
                eyebrow="Interactive Controls"
                title="Buttons, Icon Buttons & Segmented Controls"
                description="Variants, sizes, loading spinners, disabled states, and keyboard focus states."
            />

            <x-ui.card padding="p-6">
                <!-- Button Variants Row -->
                <div class="space-y-3 mb-8">
                    <span class="text-xs font-bold uppercase tracking-wider text-ink-500 font-sans">
                        Button Variants
                    </span>
                    <div class="flex flex-wrap items-center gap-3">
                        <x-ui.button variant="primary">Primary Brand</x-ui.button>
                        <x-ui.button variant="secondary">Secondary Ink</x-ui.button>
                        <x-ui.button variant="outline">Outline Border</x-ui.button>
                        <x-ui.button variant="soft">Soft Tint</x-ui.button>
                        <x-ui.button variant="ghost">Ghost Link</x-ui.button>
                        <x-ui.button variant="destructive">Destructive</x-ui.button>
                        <div class="p-2 rounded-xl bg-brand-900 inline-flex items-center gap-2">
                            <x-ui.button variant="white" size="sm">Solid White</x-ui.button>
                            <x-ui.button variant="outline-white" size="sm">Outline White</x-ui.button>
                        </div>
                    </div>
                </div>

                <!-- Sizes & States Row -->
                <div class="space-y-3 mb-8">
                    <span class="text-xs font-bold uppercase tracking-wider text-ink-500 font-sans">
                        Button Sizes & Loading States
                    </span>
                    <div class="flex flex-wrap items-center gap-3">
                        <x-ui.button size="xs" icon="plus">Extra Small</x-ui.button>
                        <x-ui.button size="sm" icon="search">Small</x-ui.button>
                        <x-ui.button size="md" iconRight="arrow-right">Medium (Default)</x-ui.button>
                        <x-ui.button size="lg" icon="sparkles">Large Hero</x-ui.button>
                        <x-ui.button size="md" :loading="true">Processing</x-ui.button>
                        <x-ui.button size="md" :disabled="true">Disabled</x-ui.button>
                    </div>
                </div>

                <!-- Icon Buttons & Segmented Controls -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-border-subtle">
                    <div class="space-y-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-ink-500 font-sans">
                            Icon Buttons (Sizes: xs, sm, md, lg)
                        </span>
                        <div class="flex items-center gap-2">
                            <x-ui.icon-button size="xs" icon="plus" label="Add" />
                            <x-ui.icon-button size="sm" icon="bookmark" label="Bookmark" />
                            <x-ui.icon-button size="md" icon="share-2" label="Share" />
                            <x-ui.icon-button size="lg" icon="bell" label="Alerts" variant="soft" />
                            <x-ui.icon-button size="md" icon="trash-2" label="Delete" variant="ghost" />
                        </div>
                    </div>

                    <div class="space-y-3">
                        <span class="text-xs font-bold uppercase tracking-wider text-ink-500 font-sans">
                            Segmented Control
                        </span>
                        <div>
                            <x-ui.segmented-control 
                                :options="[
                                    ['id' => 'undergraduate', 'label' => 'BS / Undergraduate', 'count' => 140],
                                    ['id' => 'postgraduate', 'label' => 'MS / MPhil', 'count' => 84],
                                    ['id' => 'doctoral', 'label' => 'PhD', 'count' => 24],
                                ]"
                                default="undergraduate"
                            />
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </section>

        <!-- ========================================== -->
        <!-- SECTION 3: FORM CONTROLS & FILTERS -->
        <!-- ========================================== -->
        <section id="form-controls" class="scroll-mt-24 space-y-8">
            <x-ui.section-header 
                eyebrow="Data Entry & Search"
                title="Form Fields, Multi-Selects & Filters"
                description="Comprehensive inputs with labels, helper texts, validation states, and keyboard shortcut search."
            />

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Standard Inputs -->
                <x-ui.card padding="p-5" class="space-y-4">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider">
                        Text Inputs & Search
                    </h4>
                    <x-form.input 
                        name="student_name" 
                        label="Full Student Legal Name" 
                        placeholder="As written on Matriculation certificate" 
                        value="Ahmad Raza Khan"
                        required="true"
                        icon="user"
                    />

                    <x-form.input 
                        name="email_error" 
                        label="Institutional Email Address" 
                        value="invalid-email-format@"
                        error="Please provide a valid official email ending with .edu.pk or standard domain."
                    />

                    <x-form.search id="showcase-search" placeholder="Search 248 institutions..." />
                </x-ui.card>

                <!-- Select & Multi-Select -->
                <x-ui.card padding="p-5" class="space-y-4">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider">
                        Selects & Filters
                    </h4>
                    <x-form.select 
                        name="province_select" 
                        label="Provincial Jurisdiction" 
                        :options="['ict' => 'Islamabad Capital Territory (Federal)', 'punjab' => 'Punjab', 'sindh' => 'Sindh', 'kpk' => 'Khyber Pakhtunkhwa', 'balochistan' => 'Balochistan']"
                        selected="ict"
                    />

                    <div>
                        <label class="block text-xs font-semibold text-ink-800 tracking-wide font-sans mb-1.5">
                            Discipline Multi-Select Dropdown
                        </label>
                        <x-form.multi-select 
                            label="Filter Academic Disciplines"
                            :options="[
                                ['id' => '1', 'name' => 'Computer Science (CS)', 'count' => 124],
                                ['id' => '2', 'name' => 'Software Engineering (SE)', 'count' => 98],
                                ['id' => '3', 'name' => 'Electrical Engineering (EE)', 'count' => 76],
                                ['id' => '4', 'name' => 'Mechanical Engineering (ME)', 'count' => 64],
                                ['id' => '5', 'name' => 'Medicine (MBBS)', 'count' => 45],
                            ]"
                            :selected="['1', '2']"
                        />
                    </div>

                    <div class="pt-2">
                        <span class="block text-xs font-semibold text-ink-800 tracking-wide font-sans mb-2">
                            Active Filter Chips
                        </span>
                        <div class="flex flex-wrap gap-1.5">
                            <x-form.filter-chip label="Public Sector" active="true" :removable="true" />
                            <x-form.filter-chip label="Category W" active="true" :removable="true" />
                            <x-form.filter-chip label="Engineering (PEC)" :removable="false" />
                        </div>
                    </div>
                </x-ui.card>

                <!-- Toggles, Checkboxes & Radios -->
                <x-ui.card padding="p-5" class="space-y-4">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider">
                        Switches & Choice Controls
                    </h4>
                    
                    <div class="space-y-2">
                        <x-form.toggle 
                            name="notifications_enabled" 
                            label="Admission Deadline Alerts" 
                            description="SMS and email reminders before merit closing" 
                            checked="true" 
                        />
                        <x-form.toggle 
                            name="hec_sync" 
                            label="Auto-verify with identity records" 
                            description="Instantly fetch CNIC and educational certificates" 
                        />
                    </div>

                    <div class="pt-3 border-t border-border-subtle space-y-2">
                        <x-form.checkbox 
                            name="need_scholarship" 
                            label="Include Need-Based Scholarship Options" 
                            description="Automatically matches Ehsaas grant criteria"
                            checked="true" 
                        />
                        <x-form.checkbox 
                            name="pec_only" 
                            label="PEC Accredited Engineering Only (Washington Accord)" 
                        />
                    </div>

                    <div class="pt-3 border-t border-border-subtle space-y-2">
                        <span class="text-xs font-semibold text-ink-800 font-sans block">Tuition Payment Schedule</span>
                        <x-form.radio name="fee_plan" value="semester" label="Per Semester Installment" checked="true" />
                        <x-form.radio name="fee_plan" value="annual" label="Annual Lump-sum Discount" />
                    </div>
                </x-ui.card>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- SECTION 4: BADGES, STATUSES & TRUST -->
        <!-- ========================================== -->
        <section id="status-badges" class="scroll-mt-24 space-y-8">
            <x-ui.section-header 
                eyebrow="Credibility & Trust"
                title="Badges, Admission States & Statutory Seals"
                description="National regulatory credentials, freshness stamps, and admission cycle indicators."
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Badges Matrix -->
                <x-ui.card padding="p-5" class="space-y-4">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider">
                        Badge Variants & Sizes
                    </h4>
                    <div class="flex flex-wrap items-center gap-2">
                        <x-ui.badge variant="brand">Brand #EEF0FF</x-ui.badge>
                        <x-ui.badge variant="success" :dot="true">Verified Active</x-ui.badge>
                        <x-ui.badge variant="warning" :dot="true" :pulse="true">Review Required</x-ui.badge>
                        <x-ui.badge variant="error">Unaccredited</x-ui.badge>
                        <x-ui.badge variant="lavender" icon="sparkles">AI Assisted</x-ui.badge>
                        <x-ui.badge variant="mint" icon="shield-check">Chartered</x-ui.badge>
                        <x-ui.badge variant="outline">Neutral Outline</x-ui.badge>
                    </div>

                    <div class="pt-3 border-t border-border-subtle space-y-3">
                        <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider">
                            Admission Cycle Status Components
                        </h4>
                        <div class="space-y-2">
                            <div><x-domain.admission-status status="open" deadline="15 July 2026" /></div>
                            <div><x-domain.admission-status status="closing_soon" deadline="Tomorrow, 5:00 PM PKT" /></div>
                            <div><x-domain.admission-status status="merit_announced" deadline="1st List Active" /></div>
                            <div><x-domain.admission-status status="upcoming" deadline="Cycle Starts August" /></div>
                            <div><x-domain.admission-status status="closed" deadline="Ended 30 May" /></div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Statutory Seals & Citations -->
                <x-ui.card padding="p-5" class="space-y-4">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider">
                        Statutory Regulatory Credentials
                    </h4>
                    <div class="space-y-2.5">
                        <div><x-domain.verification-badge type="hec_recognized" detail="Category W (Highest National Ranking)" verifiedAt="2026 Sync" /></div>
                        <div><x-domain.verification-badge type="chartered" detail="Federal Act No. XXXV of 1991" verifiedAt="Gazette" /></div>
                        <div><x-domain.verification-badge type="council_accredited" detail="Pakistan Engineering Council (PEC) Level-II" /></div>
                        <div><x-domain.verification-badge type="gazette_verified" detail="Statutory SRO Notification 884(I)/2025" /></div>
                    </div>

                    <div class="pt-3 border-t border-border-subtle space-y-2">
                        <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider">
                            Official Source Citations
                        </h4>
                        <x-domain.source-citation 
                            source="Superadmin University Directory 2026"
                            reference="Federal Register Item #44 • Page 12"
                            verifiedDate="Verified 03 Nov 2026"
                        />
                        <x-domain.source-citation 
                            source="NUST SEECS Official Prospectus 2026"
                            reference="Section 4: Fee Structure & Scholarship Quotas"
                            verifiedDate="Live Source"
                        />
                    </div>
                </x-ui.card>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- SECTION 5: DOMAIN CARDS & METRICS -->
        <!-- ========================================== -->
        <section id="domain-cards" class="scroll-mt-24 space-y-8">
            <x-ui.section-header 
                eyebrow="Domain Modules"
                title="University Cards, Programs & Scholarships"
                description="Core cards that drive student discovery, tuition comparisons, and scholarship applications."
            />

            <!-- University Cards Grid -->
            <div>
                <h3 class="text-sm font-bold font-heading text-ink-950 uppercase tracking-wider mb-4">
                    University Discovery Cards (University Registry Pakistan Institutions)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($universities as $uni)
                        <x-domain.university-card 
                            :name="$uni['name']"
                            :acronym="$uni['acronym']"
                            :location="$uni['location']"
                            :sector="$uni['sector']"
                            :hecRank="$uni['hecRank']"
                            :feeRange="$uni['feeRange']"
                            :programsCount="$uni['programsCount']"
                            :acceptanceRate="$uni['acceptanceRate']"
                            :status="$uni['status']"
                            :deadline="$uni['deadline']"
                            :featured="$uni['featured'] ?? false"
                        />
                    @endforeach
                </div>
            </div>

            <!-- Program Cards Grid -->
            <div>
                <h3 class="text-sm font-bold font-heading text-ink-950 uppercase tracking-wider mb-4">
                    Academic Program Cards (BS, BE, MS, PhD)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($programs as $prog)
                        <x-domain.program-card 
                            :title="$prog['title']"
                            :university="$prog['university']"
                            :degreeLevel="$prog['degreeLevel']"
                            :duration="$prog['duration']"
                            :feePerSemester="$prog['feePerSemester']"
                            :cutoffMerit="$prog['cutoffMerit']"
                            :accreditation="$prog['accreditation']"
                            :status="$prog['status']"
                        />
                    @endforeach
                </div>
            </div>

            <!-- Scholarship Cards Grid -->
            <div>
                <h3 class="text-sm font-bold font-heading text-ink-950 uppercase tracking-wider mb-4">
                    Scholarship & Financial Aid Cards
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($scholarships as $sch)
                        <x-domain.scholarship-card 
                            :title="$sch['title']"
                            :provider="$sch['provider']"
                            :coverage="$sch['coverage']"
                            :eligibility="$sch['eligibility']"
                            :deadline="$sch['deadline']"
                            :category="$sch['category']"
                        />
                    @endforeach
                </div>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- SECTION 6: AI ADVISOR & REASONING -->
        <!-- ========================================== -->
        <section id="ai-guidance" class="scroll-mt-24 space-y-8">
            <x-ui.section-header 
                eyebrow="Artificial Intelligence"
                title="AI Admission Guidance & Merit Intelligence"
                description="Reasoning balloons with confidence metrics, citation footnotes, and suggested follow-up chips."
            />

            <x-domain.ai-message 
                advisorName="UniGuide National Merit Intelligence"
                confidence="98% High Confidence"
                timestamp="Calculated using verified Fall 2026 merit guidelines"
                :citations="[
                    ['source' => 'NUST SEECS Official Admission Gazette 2025-26', 'reference' => 'Cutoff Circular 2025-SEECS-CS', 'date' => 'Official Source'],
                    ['source' => 'FAST-NUCES Islamabad Admission Office', 'reference' => 'Batch 2025 Aggregate Closing List', 'date' => 'Verified']
                ]"
                :prompts="[
                    'What aggregate is needed for FAST BS Artificial Intelligence?',
                    'Calculate fee payment installments for LUMS SDSB',
                    'Show need-based scholarship application deadline'
                ]"
            >
                <p>
                    Greetings Ahmad! Here is the admission prognosis based on your Matric marks (<strong>1042/1100 • 94.7%</strong>) and FSc Part-I marks (<strong>482/520 • 92.6%</strong>):
                </p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>
                        <strong>NUST Entry Test (NET-1/2/3):</strong> With a target NET score of <strong>150+</strong>, your aggregate reaches <strong>81.2%</strong>, placing you in safe territory for <em>BS Computer Science</em> and <em>BS Software Engineering</em> at SEECS Islamabad.
                    </li>
                    <li>
                        <strong>FAST-NUCES (Islamabad / Lahore):</strong> Your high FSc score gives you a significant advantage in the 50% FSc + 50% NU Test formula. A score of <strong>65+</strong> in the NU test guarantees selection.
                    </li>
                </ul>
                <p>
                    Recommendation: Complete registration for NET Series 3 before the cutoff on <strong>15 June</strong>.
                </p>
            </x-domain.ai-message>
        </section>

        <!-- ========================================== -->
        <!-- SECTION 7: DATA TABLES & NAVIGATION -->
        <!-- ========================================== -->
        <section id="data-tables" class="scroll-mt-24 space-y-8">
            <x-ui.section-header 
                eyebrow="Data Presentation"
                title="Tables, Pagination, Timelines & Steps"
                description="Linear & Stripe grade tables with sortable column headers, pagination controls, and multi-step progress indicators."
            />

            <!-- Table Component -->
            <x-ui.table>
                <thead>
                    <tr>
                        <x-ui.table.header :sortable="true" sorted="asc">Institution Name</x-ui.table.header>
                        <x-ui.table.header :sortable="true">Location</x-ui.table.header>
                        <x-ui.table.header>Ranking</x-ui.table.header>
                        <x-ui.table.header>Estimated Fee</x-ui.table.header>
                        <x-ui.table.header align="right">Admissions</x-ui.table.header>
                    </tr>
                </thead>
                <tbody>
                    <x-ui.table.row>
                        <x-ui.table.cell>
                            <span class="font-bold text-ink-950 font-heading">National University of Sciences & Technology</span>
                            <div class="text-xs text-ink-500">Public Sector • Chartered 1991</div>
                        </x-ui.table.cell>
                        <x-ui.table.cell>Sector H-12, Islamabad</x-ui.table.cell>
                        <x-ui.table.cell><x-ui.badge variant="brand" size="sm">#1 Engineering</x-ui.badge></x-ui.table.cell>
                        <x-ui.table.cell><span class="font-mono font-semibold">PKR 145,000/sem</span></x-ui.table.cell>
                        <x-ui.table.cell align="right"><x-domain.admission-status status="open" size="sm" /></x-ui.table.cell>
                    </x-ui.table.row>

                    <x-ui.table.row>
                        <x-ui.table.cell>
                            <span class="font-bold text-ink-950 font-heading">Lahore University of Management Sciences</span>
                            <div class="text-xs text-ink-500">Chartered Private • Chartered 1985</div>
                        </x-ui.table.cell>
                        <x-ui.table.cell>DHA Phase 5, Lahore</x-ui.table.cell>
                        <x-ui.table.cell><x-ui.badge variant="brand" size="sm">#1 Business</x-ui.badge></x-ui.table.cell>
                        <x-ui.table.cell><span class="font-mono font-semibold">PKR 420,000/sem</span></x-ui.table.cell>
                        <x-ui.table.cell align="right"><x-domain.admission-status status="closing_soon" size="sm" /></x-ui.table.cell>
                    </x-ui.table.row>

                    <x-ui.table.row>
                        <x-ui.table.cell>
                            <span class="font-bold text-ink-950 font-heading">FAST-NUCES Islamabad</span>
                            <div class="text-xs text-ink-500">Chartered Autonomous • Chartered 2000</div>
                        </x-ui.table.cell>
                        <x-ui.table.cell>Sector AK Brohi Road, Islamabad</x-ui.table.cell>
                        <x-ui.table.cell><x-ui.badge variant="brand" size="sm">#1 Computing</x-ui.badge></x-ui.table.cell>
                        <x-ui.table.cell><span class="font-mono font-semibold">PKR 155,000/sem</span></x-ui.table.cell>
                        <x-ui.table.cell align="right"><x-domain.admission-status status="open" size="sm" /></x-ui.table.cell>
                    </x-ui.table.row>
                </tbody>
            </x-ui.table>

            <x-ui.pagination :currentPage="1" :totalPages="25" :totalItems="248" :perPage="10" />

            <!-- Step Progress & Timeline Side-by-side -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                <x-ui.card padding="p-6">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider mb-4">
                        Vertical Wizard Step Indicator
                    </h4>
                    <x-ui.step-indicator orientation="vertical" />
                </x-ui.card>

                <x-ui.card padding="p-6">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider mb-4">
                        Admission Milestones Timeline
                    </h4>
                    <x-ui.timeline />
                </x-ui.card>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- SECTION 8: FEEDBACK, OVERLAYS & STATES -->
        <!-- ========================================== -->
        <section id="feedback-overlays" class="scroll-mt-24 space-y-8">
            <x-ui.section-header 
                eyebrow="Interactive Feedback"
                title="Modals, Drawers, Toasts, Skeletons & Errors"
                description="Accessible dialogs, slide-overs, global reactive toasts, shimmering skeletons, and graceful error boundaries."
            />

            <!-- Interactive Triggers -->
            <x-ui.card padding="p-6" class="space-y-6">
                <div>
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider mb-3">
                        Interactive Dialog & Drawer Triggers
                    </h4>
                    <div class="flex flex-wrap items-center gap-3">
                        <x-ui.button variant="primary" size="sm" icon="layout-dashboard" @click="$dispatch('open-modal', 'demo-modal')">
                            Open Modal Dialog
                        </x-ui.button>

                        <x-ui.button variant="secondary" size="sm" icon="sliders-horizontal" @click="$dispatch('open-drawer', 'demo-drawer')">
                            Open Filter Drawer
                        </x-ui.button>

                        <x-ui.button variant="destructive" size="sm" icon="alert-triangle" @click="$dispatch('open-modal', 'demo-confirm')">
                            Open Confirmation Dialog
                        </x-ui.button>

                        <x-ui.tooltip text="Verified via Pakistan Engineering Council Gazette">
                            <span class="inline-flex items-center gap-1 text-xs text-brand-600 font-semibold border-b border-dashed border-brand-300 cursor-help py-1">
                                Hover for Tooltip
                                <x-icon name="info" class="w-3.5 h-3.5" />
                            </span>
                        </x-ui.tooltip>
                    </div>
                </div>

                <!-- Toast Triggers -->
                <div class="pt-4 border-t border-border-subtle">
                    <h4 class="text-xs font-bold font-sans text-ink-900 uppercase tracking-wider mb-3">
                        Global Reactive Toast Triggers
                    </h4>
                    <div class="flex flex-wrap items-center gap-2.5">
                        <x-ui.button variant="soft" size="xs" icon="check-circle-2" @click="$store.toasts.add({ title: 'Application Saved', message: 'Your admission draft has been recorded.', type: 'success' })">
                            Trigger Success Toast
                        </x-ui.button>

                        <x-ui.button variant="outline" size="xs" icon="alert-circle" @click="$store.toasts.add({ title: 'Verification Error', message: 'Invalid FSc Roll Number or Board mismatch.', type: 'error' })">
                            Trigger Error Toast
                        </x-ui.button>

                        <x-ui.button variant="outline" size="xs" icon="alert-triangle" @click="$store.toasts.add({ title: 'Deadline Warning', message: 'NUST registration ends in 48 hours.', type: 'warning' })">
                            Trigger Warning Toast
                        </x-ui.button>

                        <x-ui.button variant="outline" size="xs" icon="info" @click="$store.toasts.add({ title: 'New Merit Circular', message: 'has revised minimum eligibility criteria.', type: 'info' })">
                            Trigger Info Toast
                        </x-ui.button>
                    </div>
                </div>
            </x-ui.card>

            <!-- Loading Skeletons & Empty / Error States -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Skeleton Card -->
                <div class="space-y-2">
                    <span class="text-xs font-bold font-sans text-ink-500 uppercase tracking-wider">Loading Skeleton</span>
                    <x-ui.skeleton variant="card" />
                </div>

                <!-- Empty State -->
                <div class="space-y-2">
                    <span class="text-xs font-bold font-sans text-ink-500 uppercase tracking-wider">Empty State</span>
                    <x-ui.empty-state 
                        icon="search"
                        title="No universities found"
                        description="Try modifying your tuition fee range or selecting a different province."
                        actionLabel="Reset Filters"
                    />
                </div>

                <!-- Error State -->
                <div class="space-y-2">
                    <span class="text-xs font-bold font-sans text-ink-500 uppercase tracking-wider">Graceful Error State</span>
                    <x-ui.error-state 
                        title="Source Sync Offline"
                        message="Federal registry connection timed out."
                        code="HEC_SYNC_504"
                    />
                </div>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- SECTION 9: DATA VISUALIZATIONS (CHART.JS) -->
        <!-- ========================================== -->
        <section id="data-visualizations" class="scroll-mt-24 space-y-8">
            <x-ui.section-header 
                eyebrow="Data Visualization"
                title="Chart.js Charts & Analytical Graphs"
                description="Accessible, responsive charts with custom UniGuide brand palettes and legible typography."
            />

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Chart 1: Merit Cutoff Trends -->
                <x-ui.card padding="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="font-heading font-bold text-base text-ink-950">
                                5-Year Closing Merit Cutoffs (BS Computer Science)
                            </h4>
                            <p class="text-xs text-ink-500 font-sans mt-0.5">
                                Aggregate percentage progression across top institutions (2022–2026)
                            </p>
                        </div>
                        <x-ui.badge variant="brand" size="sm">Agg %</x-ui.badge>
                    </div>

                    <div class="h-64 w-full relative">
                        <canvas id="meritTrendsChart"></canvas>
                    </div>
                </x-ui.card>

                <!-- Chart 2: Public vs Private Tuition Distribution -->
                <x-ui.card padding="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="font-heading font-bold text-base text-ink-950">
                                Average Semester Tuition By Academic Discipline
                            </h4>
                            <p class="text-xs text-ink-500 font-sans mt-0.5">
                                Public vs Chartered Private University comparison (PKR Thousands)
                            </p>
                        </div>
                        <x-ui.badge variant="success" size="sm">PKR '000</x-ui.badge>
                    </div>

                    <div class="h-64 w-full relative">
                        <canvas id="tuitionComparisonChart"></canvas>
                    </div>
                </x-ui.card>
            </div>
        </section>
    </div>

    <!-- Demonstration Modal -->
    <x-ui.modal id="demo-modal" title="Candidate Merit Verification" description="Official verification against Board of Intermediate & Secondary Education (BISE) data.">
        <div class="space-y-4">
            <p class="text-xs text-ink-600 font-sans leading-relaxed">
                This verification check connects to the federal data exchange and provincial BISE boards to validate academic marks.
            </p>
            <x-form.input name="bise_roll" label="Intermediate Roll Number" placeholder="e.g. 542198" value="542198" />
            <x-form.select name="bise_board" label="BISE Examination Board" :options="['bise_lahore' => 'BISE Lahore', 'bise_fbise' => 'Federal Board (FBISE) Islamabad', 'bise_karachi' => 'BISE Karachi', 'bise_peshawar' => 'BISE Peshawar']" selected="bise_fbise" />
        </div>
        <x-slot:footer>
            <x-ui.button variant="ghost" size="sm" @click="show = false">
                Cancel
            </x-ui.button>
            <x-ui.button variant="primary" size="sm" @click="$store.toasts.add({ title: 'Marks Verified', message: 'FBISE Roll #542198 verified with 92.6% marks.', type: 'success' }); show = false">
                Verify Credentials
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    <!-- Demonstration Drawer -->
    <x-ui.drawer id="demo-drawer" title="Advanced University Filters" description="Filter 248 institutions by statutory parameters.">
        <div class="space-y-5">
            <x-form.select name="filter_province" label="Provincial Jurisdiction" :options="['all' => 'All Provinces', 'ict' => 'Islamabad Capital Territory', 'punjab' => 'Punjab', 'sindh' => 'Sindh', 'kpk' => 'KPK', 'balochistan' => 'Balochistan']" />
            <x-form.toggle name="hec_w" label="Category W Only" description="Filter for institutions in highest tier" checked="true" />
            <x-form.toggle name="need_aid" label="Has Need-Based Aid" description="Offers university endowment or Ehsaas grants" checked="true" />
            <x-form.input name="max_tuition" label="Maximum Semester Fee (PKR)" placeholder="200000" value="200,000" />
        </div>
        <x-slot:footer>
            <x-ui.button variant="ghost" size="sm" @click="show = false">Cancel</x-ui.button>
            <x-ui.button variant="primary" size="sm" @click="$store.toasts.add({ title: 'Filters Applied', message: '42 universities match criteria.', type: 'info' }); show = false">Apply Filters</x-ui.button>
        </x-slot:footer>
    </x-ui.drawer>

    <!-- Demonstration Confirm Dialog -->
    <x-ui.confirm-dialog 
        id="demo-confirm"
        title="Revoke Application Submission?"
        message="Are you sure you want to withdraw your admission application from NUST SEECS? This action cannot be reversed."
        confirmText="Withdraw Application"
        cancelText="Keep Active"
        variant="destructive"
    />

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.createUniGuideChart) {
                // Merit Trends Line Chart
                window.createUniGuideChart('meritTrendsChart', {
                    type: 'line',
                    data: {
                        labels: ['2022', '2023', '2024', '2025', '2026 (Est.)'],
                        datasets: [
                            {
                                label: 'NUST SEECS (BSCS)',
                                data: [78.5, 79.2, 80.0, 80.8, 81.4],
                                borderColor: '#4355E8',
                                backgroundColor: 'rgba(67, 85, 232, 0.08)',
                                fill: true,
                                tension: 0.35,
                                pointBackgroundColor: '#4355E8',
                            },
                            {
                                label: 'FAST-NUCES (BSCS)',
                                data: [75.2, 76.1, 76.8, 77.4, 78.2],
                                borderColor: '#12866C',
                                backgroundColor: 'transparent',
                                tension: 0.35,
                                pointBackgroundColor: '#12866C',
                            },
                            {
                                label: 'GIKI Topi (BSCS)',
                                data: [76.0, 76.8, 77.5, 78.0, 78.9],
                                borderColor: '#7C3AED',
                                backgroundColor: 'transparent',
                                tension: 0.35,
                                pointBackgroundColor: '#7C3AED',
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                min: 72,
                                max: 84,
                            }
                        }
                    }
                });

                // Tuition Comparison Bar Chart
                window.createUniGuideChart('tuitionComparisonChart', {
                    type: 'bar',
                    data: {
                        labels: ['Computer Science', 'Software Eng.', 'Electrical Eng.', 'BBA / Business', 'Data Science'],
                        datasets: [
                            {
                                label: 'Public Sector (e.g. NUST, UET)',
                                data: [145, 140, 135, 125, 140],
                                backgroundColor: '#4355E8',
                                borderRadius: 6,
                            },
                            {
                                label: 'Chartered Private (e.g. LUMS, GIKI)',
                                data: [380, 360, 340, 420, 375],
                                backgroundColor: '#C7CDFE',
                                borderRadius: 6,
                            }
                        ]
                    },
                    options: {
                        scales: {
                            y: {
                                ticks: {
                                    callback: function(val) {
                                        return 'PKR ' + val + 'k';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.public>

