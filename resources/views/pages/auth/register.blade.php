<x-layouts.public 
    title="Create Free Student Account — UniGuide Pakistan"
    description="Register for a free student admissions locker on UniGuide Pakistan to track admission deadlines, calculate merit aggregates, and unlock AI counseling."
>
    <div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl w-full space-y-6">
            <!-- Header Brand & Identity -->
            <div class="text-center space-y-2">
                <x-brand.logo size="lg" class="mx-auto" />
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-ink-950 tracking-tight">
                    Create Your Student Account
                </h1>
                <p class="text-xs sm:text-sm text-ink-500 font-sans">
                    Join over 340,000 Pakistani candidates tracking Fall 2026 university admissions
                </p>
            </div>

            <!-- Registration Container Card -->
            <div class="bg-surface rounded-3xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                <form action="{{ route('auth.register.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <x-form.input 
                        name="full_name" 
                        label="Full Legal Name" 
                        placeholder="As registered with BISE / Cambridge Board" 
                        value="{{ old('full_name') }}"
                        required="true"
                        icon="user"
                        :error="$errors->first('full_name')"
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input 
                            name="email" 
                            type="email"
                            label="Email Address" 
                            placeholder="name@gmail.com" 
                            value="{{ old('email') }}"
                            required="true"
                            :error="$errors->first('email')"
                        />

                        <x-form.input 
                            name="phone" 
                            label="Mobile Number (WhatsApp)" 
                            placeholder="0300-1234567" 
                            value="{{ old('phone') }}"
                            required="true"
                            :error="$errors->first('phone')"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.select 
                            name="stream" 
                            label="Intermediate Academic Stream" 
                            :options="[
                                'fsc_pre_eng' => 'FSc Pre-Engineering',
                                'fsc_pre_med' => 'FSc Pre-Medical',
                                'ics_math_stat' => 'ICS (Computer Science & Math)',
                                'a_levels' => 'Cambridge A-Levels (Equivalence)',
                                'icom' => 'I.Com (Commerce)',
                                'dae' => 'DAE (Associate Engineering)'
                            ]"
                            selected="{{ old('stream', 'fsc_pre_eng') }}"
                            required="true"
                        />

                        <x-form.select 
                            name="province" 
                            label="Provincial Domicile" 
                            :options="[
                                'punjab' => 'Punjab',
                                'sindh_urban' => 'Sindh (Urban)',
                                'sindh_rural' => 'Sindh (Rural)',
                                'ict' => 'Islamabad Capital Territory (Federal)',
                                'kpk' => 'Khyber Pakhtunkhwa',
                                'balochistan' => 'Balochistan',
                                'ajk' => 'Azad Jammu & Kashmir',
                                'gb' => 'Gilgit-Baltistan'
                            ]"
                            selected="{{ old('province', 'ict') }}"
                            required="true"
                        />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-semibold text-ink-800 font-sans mb-1.5">
                                Account Password <span class="text-error-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required
                                placeholder="Min. 8 characters" 
                                class="w-full px-3.5 py-2 text-sm text-ink-900 bg-surface rounded-lg border border-border-default focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 font-sans"
                            />
                            @error('password')
                                <p class="mt-1 text-xs text-error-600 font-sans">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-semibold text-ink-800 font-sans mb-1.5">
                                Confirm Password <span class="text-error-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                required
                                placeholder="Re-type password" 
                                class="w-full px-3.5 py-2 text-sm text-ink-900 bg-surface rounded-lg border border-border-default focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 font-sans"
                            />
                        </div>
                    </div>

                    <div class="pt-2">
                        <x-form.checkbox 
                            name="terms" 
                            label="I agree to UniGuide Pakistan Terms & HEC Data Verification Standards" 
                            description="I authorize UniGuide to verify my intermediate roll numbers against BISE and official entry testing bodies."
                            checked="true"
                            required="true"
                        />
                    </div>

                    <x-ui.button type="submit" variant="primary" size="lg" class="w-full shadow-xs mt-2" iconRight="arrow-right">
                        Complete Registration & Launch Portal
                    </x-ui.button>
                </form>

                <div class="pt-4 border-t border-border-subtle text-center text-xs font-sans text-ink-600">
                    <span>Already have a candidate account?</span>
                    <a href="{{ route('auth.login') }}" class="font-bold text-brand-600 hover:text-brand-700 ml-1">
                        Sign in here
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
