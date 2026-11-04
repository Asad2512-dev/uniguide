<x-layouts.university-admin 
    :title="'University Profile Management — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <x-ui.page-header 
        title="University Profile & Institutional Identity"
        description="Official national charter credentials, statutory accreditations, central admissions helpline, and executive leadership roster."
        badge="Verified Federal Charter"
        badgeVariant="success"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                    ['label' => 'Profile Management']
                ]"
            />
        </x-slot:breadcrumbs>
    </x-ui.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left 8 Cols: Edit Form -->
        <div class="lg:col-span-8">
            <form action="{{ route('uni_admin.profile.update') }}" method="POST" class="p-6 sm:p-8 rounded-2xl bg-surface border border-border-default shadow-card space-y-8">
                @csrf

                <!-- Basic Credentials Section -->
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950 pb-2 border-b border-border-subtle">
                        Institutional Identification
                    </h3>
                    <p class="text-xs text-ink-500 font-sans mt-1 mb-5">
                        These credentials appear on candidate search results and official comparison matrices.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-ink-800 mb-1.5">Official University Full Name</label>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ old('name', $profile['name']) }}" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1.5">Parliamentary Charter Act</label>
                            <input 
                                type="text" 
                                value="{{ $profile['charter_act'] }}" 
                                disabled 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-subtle bg-surface-subtle text-ink-500 text-xs font-mono cursor-not-allowed"
                            />
                            <span class="text-[10px] text-ink-400 mt-1 block">Locked by HEC Federal Registry</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1.5">Establishment Year & Ranking</label>
                            <input 
                                type="text" 
                                value="Est. {{ $profile['established'] }} • {{ $profile['hec_ranking'] }}" 
                                disabled 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-subtle bg-surface-subtle text-ink-500 text-xs font-sans cursor-not-allowed"
                            />
                        </div>
                    </div>
                </div>

                <!-- Contact & Web Portals -->
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950 pb-2 border-b border-border-subtle">
                        Admissions Contacts & Digital Portals
                    </h3>
                    <p class="text-xs text-ink-500 font-sans mt-1 mb-5">
                        Public channels where candidates reach your central admissions counselors.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1.5">Admissions Helpline / UAN</label>
                            <input 
                                type="text" 
                                name="helpline" 
                                value="{{ old('helpline', $profile['helpline']) }}" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1.5">Central Admissions Email</label>
                            <input 
                                type="email" 
                                name="admissions_email" 
                                value="{{ old('admissions_email', $profile['admissions_email']) }}" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1.5">Institutional Website</label>
                            <input 
                                type="url" 
                                name="website" 
                                value="{{ old('website', $profile['website']) }}" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1.5">Candidate Application Portal</label>
                            <input 
                                type="url" 
                                value="{{ $profile['admissions_portal'] }}" 
                                disabled
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-subtle bg-surface-subtle text-ink-500 text-xs font-sans cursor-not-allowed"
                            />
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-ink-800 mb-1.5">Headquarters Postal Address</label>
                            <textarea 
                                name="address" 
                                rows="2" 
                                required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                            >{{ old('address', $profile['address']) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Executive Leadership Roster -->
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950 pb-2 border-b border-border-subtle">
                        Executive Leadership
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4">
                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1.5">Chancellor</label>
                            <input 
                                type="text" 
                                name="chancellor" 
                                value="{{ old('chancellor', $profile['chancellor']) }}" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-ink-800 mb-1.5">Rector / Vice Chancellor</label>
                            <input 
                                type="text" 
                                name="rector" 
                                value="{{ old('rector', $profile['rector']) }}" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                            />
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-border-subtle flex items-center justify-end">
                    <x-ui.button type="submit" variant="primary" size="md" icon="check">
                        Save Profile Updates
                    </x-ui.button>
                </div>
            </form>
        </div>

        <!-- Right 4 Cols: Accreditations & Statutory Standing -->
        <div class="lg:col-span-4 space-y-6">
            <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                <div class="flex items-center gap-2 pb-3 border-b border-border-subtle">
                    <x-icon name="shield-check" class="w-4 h-4 text-emerald-600" />
                    <h3 class="font-heading font-bold text-sm text-ink-950">Statutory Accreditations</h3>
                </div>

                <div class="space-y-3">
                    @foreach($profile['accreditations'] as $acc)
                        <div class="p-3.5 rounded-xl bg-surface-subtle border border-border-subtle flex items-center justify-between">
                            <span class="text-xs font-semibold text-ink-900">{{ $acc }}</span>
                            <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/80 px-2 py-0.5 rounded">
                                Active
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-border-subtle text-[11px] text-ink-500 font-sans leading-relaxed">
                    Accreditation changes require statutory gazette attestation through the HEC National Command portal.
                </div>
            </div>

            <!-- Verification Metadata Card -->
            <div class="p-6 rounded-2xl bg-surface-subtle border border-border-default space-y-3">
                <span class="text-[10px] font-mono font-bold text-ink-400 uppercase block">Audit Verification</span>
                <div class="text-xs font-sans text-ink-700">
                    Last audited by HEC Inspection Directorate on <strong class="text-ink-900">{{ $profile['verified_date'] }}</strong>.
                </div>
            </div>
        </div>
    </div>
</x-layouts.university-admin>
