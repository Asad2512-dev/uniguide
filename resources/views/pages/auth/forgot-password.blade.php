<x-layouts.public 
    title="Reset Password — UniGuide Pakistan"
    description="Recover access to your UniGuide student account or institutional administrative console."
>
    <div class="min-h-[calc(100vh-14rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6">
            <div class="text-center space-y-2">
                <x-brand.logo size="lg" class="mx-auto" />
                <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-ink-950 tracking-tight">
                    Reset Your Password
                </h1>
                <p class="text-xs sm:text-sm text-ink-500 font-sans">
                    Enter your registered email address and we will dispatch a secure verification link to restore your account.
                </p>
            </div>

            <div class="bg-surface rounded-3xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                <form action="{{ route('auth.forgot_password.submit') }}" method="POST" class="space-y-4">
                    @csrf

                    <x-form.input 
                        name="email" 
                        type="email"
                        label="Registered Email Address" 
                        placeholder="e.g. ahmad.raza@student.uniguide.pk" 
                        required="true"
                        icon="user"
                        :error="$errors->first('email')"
                    />

                    <x-ui.button type="submit" variant="primary" size="lg" class="w-full shadow-xs" iconRight="arrow-right">
                        Send Password Reset Link
                    </x-ui.button>
                </form>

                <div class="pt-4 border-t border-border-subtle text-center text-xs font-sans text-ink-600">
                    <a href="{{ route('auth.login') }}" class="font-bold text-brand-600 hover:text-brand-700 inline-flex items-center gap-1">
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                        <span>Return to Sign In</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>

