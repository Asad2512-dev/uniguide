<x-layouts.public 
    title="Contact & Admissions Support — UniGuide Pakistan"
    description="Get in touch with UniGuide Pakistan's admissions advisory team, regional desks, or submit an institutional inquiry."
>
    <!-- Header -->
    <section class="pt-12 pb-14 border-b border-border-default bg-surface">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-ui.page-header 
                title="Admissions Support & Regional Desks"
                description="Need assistance with university eligibility, aggregate discrepancies, or institutional verification? Our national admissions advisory desk is ready to help."
                badge="National Helpdesk"
                badgeVariant="brand"
            >
                <x-slot:breadcrumbs>
                    <x-ui.breadcrumbs 
                        :items="[
                            ['label' => 'Home', 'url' => route('home')],
                            ['label' => 'Contact & Support']
                        ]"
                    />
                </x-slot:breadcrumbs>
            </x-ui.page-header>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
        <!-- Flash Alert if submitted -->
        @if(session('success'))
            <div class="p-4 rounded-xl border border-success-200 bg-success-50 text-success-800 text-xs font-sans flex items-center gap-3">
                <x-icon name="check-circle-2" class="w-5 h-5 text-success-600 shrink-0" />
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Left: Contact Inquiry Form (7 cols) -->
            <div class="lg:col-span-7">
                <x-ui.card padding="p-6 sm:p-8" class="space-y-6">
                    <div>
                        <h3 class="font-heading font-bold text-lg text-ink-950">Submit an Admissions Inquiry</h3>
                        <p class="text-xs text-ink-500 font-sans mt-1">
                            Tickets are reviewed by verified admissions counselors and assigned directly to the relevant regional desk.
                        </p>
                    </div>

                    <form action="{{ route('marketing.contact.submit') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-form.input 
                                name="name" 
                                label="Your Full Name" 
                                placeholder="e.g. Fatima Zahra" 
                                value="{{ old('name') }}"
                                required="true" 
                                icon="user"
                                :error="$errors->first('name')"
                            />

                            <x-form.input 
                                name="email" 
                                type="email"
                                label="Email Address" 
                                placeholder="name@domain.com" 
                                value="{{ old('email') }}"
                                required="true" 
                                :error="$errors->first('email')"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <x-form.input 
                                name="phone" 
                                label="WhatsApp / Phone Number" 
                                placeholder="0300-1234567" 
                                value="{{ old('phone') }}"
                                :error="$errors->first('phone')"
                            />

                            <x-form.select 
                                name="category" 
                                label="Inquiry Category" 
                                :options="[
                                    'aggregate' => 'Aggregate Calculation / Merit Discrepancy',
                                    'bise_verification' => 'BISE / Intermediate Marks Verification',
                                    'scholarship' => 'HEC / Ehsaas Scholarship Assistance',
                                    'university_access' => 'University Registrar / Admin Access',
                                    'unaccredited_report' => 'Report Fraudulent / Unaccredited Program',
                                    'other' => 'General Platform Question'
                                ]"
                                selected="{{ old('category', 'aggregate') }}"
                                required="true"
                            />
                        </div>

                        <x-form.input 
                            name="subject" 
                            label="Subject Summary" 
                            placeholder="e.g. Query regarding FAST Islamabad Fall 2026 CS Cutoff" 
                            value="{{ old('subject') }}"
                            required="true" 
                            :error="$errors->first('subject')"
                        />

                        <div>
                            <label for="message" class="block text-xs font-semibold text-ink-800 tracking-wide font-sans mb-1.5">
                                Detailed Message & Background <span class="text-error-500">*</span>
                            </label>
                            <textarea 
                                name="message" 
                                id="message" 
                                rows="5"
                                required
                                placeholder="Please include your intermediate board, roll number, or target universities if relevant..."
                                class="w-full rounded-lg border border-border-default bg-surface p-3 text-xs sm:text-sm text-ink-900 placeholder:text-ink-400 focus:outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 font-sans transition-all"
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-xs text-error-600 font-sans">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <p class="text-[11px] text-ink-400 font-sans">
                                Response time: ~4 to 24 hours during peak admission cycles.
                            </p>
                            <x-ui.button type="submit" variant="primary" size="md" iconRight="arrow-right">
                                Submit Support Ticket
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card>
            </div>

            <!-- Right: Regional Desks & Emergency Contacts (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Emergency Hotline Card -->
                <div class="p-6 bg-gradient-to-br from-brand-900 to-indigo-950 text-white rounded-2xl shadow-card space-y-3">
                    <span class="text-[10px] font-mono uppercase tracking-wider font-bold text-brand-200 bg-white/10 px-2 py-0.5 rounded">
                        Fall 2026 Admissions Live Desk
                    </span>
                    <h4 class="font-heading font-bold text-lg text-white">Emergency Admissions Helpline</h4>
                    <p class="text-xs text-brand-100 font-sans leading-relaxed">
                        For critical admission fee challan deadlines or university portal outages:
                    </p>
                    <div class="pt-1 flex items-center gap-3 font-mono font-bold text-sm text-white">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span>UAN: 051-111-UNI-GUIDE</span>
                    </div>
                </div>

                <!-- Regional Desks Directory -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-ink-500 font-sans">
                        Regional Coordination Desks
                    </h4>

                    @foreach($regionalDesks as $desk)
                        <div class="p-4 bg-surface rounded-xl border border-border-default space-y-1.5 text-xs font-sans">
                            <span class="font-heading font-bold text-sm text-ink-950 block">{{ $desk['region'] }}</span>
                            <div class="flex items-center gap-2 text-ink-500">
                                <x-icon name="map-pin" class="w-3.5 h-3.5 text-ink-400 shrink-0" />
                                <span>{{ $desk['address'] }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-ink-500">
                                <x-icon name="clock" class="w-3.5 h-3.5 text-ink-400 shrink-0" />
                                <span>{{ $desk['hours'] }}</span>
                            </div>
                            <div class="pt-1 flex items-center gap-4 text-brand-600 font-medium">
                                <a href="mailto:{{ $desk['email'] }}" class="hover:underline">{{ $desk['email'] }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
