<x-layouts.public title="List your university — UniGuide">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:py-16">
        <p class="text-sm font-semibold text-brand-700 mb-3">FOR UNIVERSITIES</p>
        <h1 class="text-3xl sm:text-4xl font-heading font-bold text-ink-950">Help students discover your university</h1>
        <p class="mt-4 text-ink-600">Join UniGuide’s Punjab and Islamabad directory. Share your institution’s details and our platform team will review your listing request.</p>
        <div class="grid sm:grid-cols-3 gap-4 my-8 text-sm text-ink-600">
            <p><strong class="text-ink-950">1. Share your details</strong><br>Tell us about your institution.</p>
            <p><strong class="text-ink-950">2. We review your request</strong><br>Our team checks the listing details.</p>
            <p><strong class="text-ink-950">3. Reach more students</strong><br>Approved listings appear in the directory.</p>
        </div>
        @if(session('application_reference'))
            <div role="status" class="rounded-xl bg-emerald-50 border border-emerald-200 p-6 mb-6 text-emerald-900">
                <h2 class="font-bold text-lg">Your request has been received</h2>
                <p class="mt-2">Reference: <strong>{{ session('application_reference') }}</strong>. Keep this reference for follow-up. Your listing will appear after approval.</p>
            </div>
        @endif
        @if($errors->any())
            <div role="alert" class="rounded-xl bg-red-50 p-4 mb-6 text-red-800"><p class="font-bold">Please check the details below.</p><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('universities.join.submit') }}" class="rounded-2xl bg-surface border border-border-default p-6 sm:p-8 space-y-6">
            @csrf
            <h2 class="text-xl font-bold text-ink-950">Institution details</h2>
            <div class="grid sm:grid-cols-2 gap-5">
                @foreach(['name' => 'University name', 'website' => 'Official website', 'city' => 'City', 'contact_name' => 'Your full name', 'email' => 'Contact email', 'phone' => 'Contact phone'] as $field => $label)
                    <div><label for="{{ $field }}" class="block text-sm font-semibold text-ink-800 mb-2">{{ $label }} <span aria-hidden="true">*</span></label>
                    <input id="{{ $field }}" name="{{ $field }}" type="{{ $field === 'website' ? 'url' : ($field === 'email' ? 'email' : ($field === 'phone' ? 'tel' : 'text')) }}" value="{{ old($field) }}" required maxlength="{{ $field === 'phone' ? 30 : 200 }}" @if($field === 'website') placeholder="https://university.edu.pk" @endif class="w-full rounded-lg border border-border-default bg-surface p-3 text-sm focus:ring-2 focus:ring-brand-500"></div>
                @endforeach
                <div><label for="province" class="block text-sm font-semibold mb-2">Region *</label><select id="province" name="province" required class="w-full rounded-lg border border-border-default p-3"><option value="">Choose a region</option><option value="punjab" @selected(old('province') === 'punjab')>Punjab</option><option value="ict" @selected(old('province') === 'ict')>Islamabad Capital Territory</option></select></div>
                <div><label for="sector" class="block text-sm font-semibold mb-2">Sector *</label><select id="sector" name="sector" required class="w-full rounded-lg border border-border-default p-3"><option value="">Choose a sector</option><option value="public" @selected(old('sector') === 'public')>Public</option><option value="private" @selected(old('sector') === 'private')>Private</option></select></div>
            </div>
            <div><label for="description" class="block text-sm font-semibold mb-2">About your university *</label><p id="description-help" class="text-sm text-ink-500 mb-2">Include your campus, programs, and admissions information (30–5,000 characters).</p><textarea id="description" name="description" rows="5" minlength="30" maxlength="5000" required aria-describedby="description-help" class="w-full rounded-lg border border-border-default p-3">{{ old('description') }}</textarea></div>
            <label class="flex items-start gap-3 text-sm text-ink-600"><input type="checkbox" name="consent" value="1" required @checked(old('consent')) class="mt-1"><span>I am authorized to represent this institution and consent to UniGuide reviewing these details and publishing the approved institution information.</span></label>
            <x-ui.button type="submit" size="lg">Submit listing request</x-ui.button>
            <p class="text-xs text-ink-500">Submitting a request does not create an administrator account or confirm accreditation.</p>
        </form>
    </div>
</x-layouts.public>
