<x-layouts.public 
    title="Sign In — UniGuide Pakistan Admissions Gateway"
    description="Sign in to your UniGuide student admission locker, university administration console, or UniGuide superadmin workspace."
>
    <div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6">
            <!-- Header Brand & Identity -->
            <div class="text-center space-y-2">
                <x-brand.logo size="lg" class="mx-auto" />
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-ink-950 tracking-tight">
                    Sign in to UniGuide
                </h1>
                <p class="text-xs sm:text-sm text-ink-500 font-sans">
                    National Higher Education Gateway for Pakistani students & institutions
                </p>
            </div>

            <!-- Login Container Card -->
            <div 
                x-data="{
                    role: '{{ $initialRole ?? 'student' }}',
                    showPassword: false,
                    email: '',
                    password: '',
                    setDemo(r) {
                        this.role = r;
                        if (r === 'student') {
                            this.email = 'ahmad.raza@student.uniguide.pk';
                            this.password = 'StudentPass2026!';
                        } else if (r === 'university') {
                            this.email = 'admissions@nust.edu.pk';
                            this.password = 'NustAdmin2026!';
                        } else if (r === 'admin') {
                            this.email = 'regulatory@hec.gov.pk';
                            this.password = 'HecFederal2026!';
                        }
                    }
                }"
                class="bg-surface rounded-3xl border border-border-default p-6 sm:p-8 shadow-card space-y-6"
            >
                <!-- Role Switcher Segmented Control -->
                <div>
                    <label class="block text-xs sm:text-[11px] font-bold text-ink-600 uppercase tracking-wider font-sans mb-2 text-center">
                        Select Workspace Account Type
                    </label>
                    <div class="grid grid-cols-3 gap-1 bg-surface-subtle p-1 rounded-xl border border-border-default text-xs font-sans">
                        <button 
                            type="button" 
                            @click="role = 'student'"
                            :class="role === 'student' ? 'bg-surface text-brand-700 font-bold shadow-2xs' : 'text-ink-600 hover:text-ink-950'"
                            class="py-1.5 rounded-lg transition-smooth cursor-pointer text-center"
                        >
                            Student
                        </button>
                        <button 
                            type="button" 
                            @click="role = 'university'"
                            :class="role === 'university' ? 'bg-surface text-brand-700 font-bold shadow-2xs' : 'text-ink-600 hover:text-ink-950'"
                            class="py-1.5 rounded-lg transition-smooth cursor-pointer text-center"
                        >
                            University
                        </button>
                        <button 
                            type="button" 
                            @click="role = 'admin'"
                            :class="role === 'admin' ? 'bg-surface text-brand-700 font-bold shadow-2xs' : 'text-ink-600 hover:text-ink-950'"
                            class="py-1.5 rounded-lg transition-smooth cursor-pointer text-center"
                        >
                            Superadmin
                        </button>
                    </div>
                </div>

                <!-- Quick Demo Auto-Fill Strip -->
                <div class="p-3 bg-brand-50/60 rounded-xl border border-brand-200/60 flex items-center justify-between text-xs font-sans">
                    <span class="text-brand-900 font-medium">Demo login:</span>
                    <div class="flex items-center gap-1.5">
                        <button type="button" @click="setDemo('student')" class="px-2 py-0.5 rounded bg-surface text-brand-700 border border-brand-200 hover:bg-brand-100 font-medium text-xs sm:text-[11px] cursor-pointer">
                            Student
                        </button>
                        <button type="button" @click="setDemo('university')" class="px-2 py-0.5 rounded bg-surface text-brand-700 border border-brand-200 hover:bg-brand-100 font-medium text-xs sm:text-[11px] cursor-pointer">
                            NUST Admin
                        </button>
                        <button type="button" @click="setDemo('admin')" class="px-2 py-0.5 rounded bg-surface text-brand-700 border border-brand-200 hover:bg-brand-100 font-medium text-xs sm:text-[11px] cursor-pointer">
                            Superadmin
                        </button>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('auth.login.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="role" :value="role" />

                    <div>
                        <label for="email" class="block text-xs font-semibold text-ink-800 font-sans mb-1.5">
                            <span x-show="role === 'student'">Email or BISE Roll Number</span>
                            <span x-show="role === 'university'">Official Institutional Email (.edu.pk)</span>
                            <span x-show="role === 'admin'">Superadmin Email</span>
                        </label>
                        <input 
                            type="text" 
                            name="email" 
                            id="email" 
                            x-model="email"
                            required
                            placeholder="e.g. name@student.uniguide.pk" 
                            class="w-full px-3.5 py-2 text-sm text-ink-900 bg-surface rounded-lg border border-border-default focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 font-sans"
                        />
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-ink-800 font-sans">
                                Account Password
                            </label>
                            <a href="{{ route('auth.forgot_password') }}" class="text-xs text-brand-600 hover:text-brand-700 font-medium font-sans">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                id="password" 
                                x-model="password"
                                required
                                placeholder="••••••••" 
                                class="w-full px-3.5 pr-10 py-2 text-sm text-ink-900 bg-surface rounded-lg border border-border-default focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 font-sans"
                            />
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-ink-400 hover:text-ink-700 cursor-pointer"
                                aria-label="Toggle password visibility"
                            >
                                <x-icon name="eye" class="w-4 h-4" x-show="!showPassword" />
                                <x-icon name="eye-off" class="w-4 h-4" x-show="showPassword" />
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-sans text-ink-600">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-border-default text-brand-600 focus:ring-brand-500/20" />
                            <span>Keep me signed in for 30 days</span>
                        </label>
                    </div>

                    <x-ui.button type="submit" variant="primary" size="lg" class="w-full shadow-xs" iconRight="arrow-right">
                        Sign In to Workspace
                    </x-ui.button>
                </form>

                <div class="pt-4 border-t border-border-subtle text-center text-xs font-sans text-ink-600">
                    <span>Don't have a student account yet?</span>
                    <a href="{{ route('auth.register') }}" class="font-bold text-brand-600 hover:text-brand-700 ml-1">
                        Register for free
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>

