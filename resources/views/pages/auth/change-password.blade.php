<x-layouts.public title="Change your password — UniGuide">
    <div class="mx-auto max-w-md px-4 py-12"><h1 class="text-2xl font-bold">Change your password</h1>
        @if(session('toast_success'))<p role="status" class="mt-4 text-success-700">{{ session('toast_success') }}</p>@endif
        @if($errors->any())<p role="alert" class="mt-4 text-error-700">{{ $errors->first() }}</p>@endif
        <form method="POST" action="{{ route('account.password.update') }}" class="mt-6 space-y-5">@csrf
            @foreach(['current_password'=>'Current password','password'=>'New password','password_confirmation'=>'Confirm new password'] as $key=>$label)<label class="block text-sm font-semibold">{{ $label }}<input name="{{ $key }}" type="password" autocomplete="{{ $key==='current_password'?'current-password':'new-password' }}" required class="mt-2 block w-full rounded-lg border border-border-default p-3"></label>@endforeach
            <x-ui.button type="submit">Update password</x-ui.button>
        </form>
    </div>
</x-layouts.public>
