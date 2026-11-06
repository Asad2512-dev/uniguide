<x-layouts.student 
    title="Account Settings & Privacy — UniGuide Pakistan"
    description="Manage your student account preferences, security credentials, notification channels, and privacy controls."
>
    <div class="space-y-8 pb-12">
        
        <!-- Header Strip -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-border-default">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                    <x-icon name="settings" class="w-3.5 h-3.5 text-brand-600" />
                    <span>User Governance</span>
                </div>
                <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                    Account Settings & Security
                </h1>
                <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                    Manage your personal profile, notification frequencies, password security, and privacy data requests.
                </p>
            </div>
        </div>

        <form action="{{ route('student.settings.update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- 1. Profile Management -->
            <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-6">
                <div class="pb-3 border-b border-border-subtle">
                    <h2 class="font-heading font-bold text-base text-ink-950">Candidate Information</h2>
                    <p class="text-xs text-ink-500 font-sans mt-0.5">Primary contact details associated with your national applicant ID.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs font-sans">
                    <div class="space-y-1.5">
                        <label class="block font-semibold text-ink-800">Legal Full Name</label>
                        <input 
                            type="text" 
                            name="full_name" 
                            value="{{ $profile['full_name'] }}" 
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-ink-800">Registered Email Address</label>
                        <input 
                            type="email" 
                            value="{{ $profile['email'] }}" 
                            disabled
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface-subtle text-ink-500 cursor-not-allowed font-mono"
                        />
                        <span class="text-xs sm:text-[10px] text-ink-600">Email changes require identity verification.</span>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-ink-800">Primary Mobile Phone (SMS Alerts)</label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ $profile['phone'] }}" 
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 font-mono"
                        />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-ink-800">Residential City</label>
                        <input 
                            type="text" 
                            name="city" 
                            value="{{ $profile['city'] }}" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        />
                    </div>
                </div>
            </div>

            <!-- 2. Notification Preferences -->
            <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                <div class="pb-3 border-b border-border-subtle">
                    <h2 class="font-heading font-bold text-base text-ink-950">Notification Preferences</h2>
                    <p class="text-xs text-ink-500 font-sans mt-0.5">Control how and when UniGuide alerts you about critical admission milestones.</p>
                </div>

                <div class="space-y-3 text-xs font-sans">
                    <label class="flex items-center justify-between p-3.5 rounded-xl border border-border-default bg-surface-subtle cursor-pointer">
                        <div>
                            <span class="font-bold text-ink-900 block">Registration Deadline Reminders</span>
                            <span class="text-ink-500">Receive email alerts 5 days and 24 hours prior to application cutoffs.</span>
                        </div>
                        <input type="checkbox" checked class="rounded text-brand-600 focus:ring-brand-500 h-4 w-4" />
                    </label>

                    <label class="flex items-center justify-between p-3.5 rounded-xl border border-border-default bg-surface-subtle cursor-pointer">
                        <div>
                            <span class="font-bold text-ink-900 block">Entrance Test Schedule & Roll Number Slips</span>
                            <span class="text-ink-500">Immediate SMS alerts when NUST, FAST, or ECAT exam centers publish dates.</span>
                        </div>
                        <input type="checkbox" checked class="rounded text-brand-600 focus:ring-brand-500 h-4 w-4" />
                    </label>

                    <label class="flex items-center justify-between p-3.5 rounded-xl border border-border-default bg-surface-subtle cursor-pointer">
                        <div>
                            <span class="font-bold text-ink-900 block">Merit List Announcement Alerts</span>
                            <span class="text-ink-500">Instant notification when 1st, 2nd, and 3rd closing cutoffs are finalized.</span>
                        </div>
                        <input type="checkbox" checked class="rounded text-brand-600 focus:ring-brand-500 h-4 w-4" />
                    </label>
                </div>
            </div>

            <!-- 3. Account Security -->
            <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                <div class="pb-3 border-b border-border-subtle">
                    <h2 class="font-heading font-bold text-base text-ink-950">Security & Authentication</h2>
                    <p class="text-xs text-ink-500 font-sans mt-0.5">Safeguard your academic records and application access.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs font-sans">
                    <div class="p-4 rounded-xl border border-border-default bg-surface-subtle space-y-2">
                        <div class="font-bold text-ink-900 flex items-center gap-1.5">
                            <x-icon name="key" class="w-4 h-4 text-brand-600" />
                            <span>Password Credentials</span>
                        </div>
                        <p class="text-ink-600">Password last changed 3 months ago. Strong alphanumeric recommended.</p>
                        <a href="{{ route('auth.forgot_password') }}" class="text-brand-600 font-semibold underline block pt-1">
                            Change Password →
                        </a>
                    </div>

                    <div class="p-4 rounded-xl border border-border-default bg-surface-subtle space-y-2">
                        <div class="font-bold text-ink-900 flex items-center gap-1.5">
                            <x-icon name="shield" class="w-4 h-4 text-success-600" />
                            <span>Two-Factor Authentication (2FA)</span>
                        </div>
                        <p class="text-ink-600">SMS OTP verification active for sensitive document downloads.</p>
                        <span class="text-xs sm:text-[10px] font-mono text-success-700 bg-success-50 px-2 py-0.5 rounded font-bold inline-block">
                            Enabled via +92 300 ***8841
                        </span>
                    </div>
                </div>
            </div>

            <!-- 4. Privacy & Data Controls -->
            <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                <div class="pb-3 border-b border-border-subtle">
                    <h2 class="font-heading font-bold text-base text-ink-950">Data Privacy & Archival</h2>
                    <p class="text-xs text-ink-500 font-sans mt-0.5">Download your verified records or manage account retention.</p>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs font-sans">
                    <div>
                        <span class="font-bold text-ink-900 block">Export Full Academic Transcript & Application Portfolio</span>
                        <span class="text-ink-500">Download a verified JSON or PDF bundle of all your marks, test scores, and notes.</span>
                    </div>

                    <button 
                        type="button" 
                        onclick="alert('Your full applicant data archive has been generated and queued for download.')"
                        class="px-4 py-2 rounded-xl border border-border-default bg-surface hover:bg-surface-subtle text-ink-800 font-semibold shadow-2xs cursor-pointer shrink-0"
                    >
                        Export Data Bundle
                    </button>
                </div>
            </div>

            <!-- Save Changes -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold font-sans shadow-card transition-smooth cursor-pointer flex items-center gap-2"
                >
                    <x-icon name="check" class="w-4 h-4" />
                    <span>Save Settings & Preferences</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.student>

