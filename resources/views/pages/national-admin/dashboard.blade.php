<x-layouts.national-admin title="Platform overview — UniGuide">
    <x-ui.page-header title="Platform overview" description="Manage university listings, support your users, and keep UniGuide’s information useful and up to date." />
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mt-6">
        @foreach([
            ['Universities', \App\Models\University::count(), 'national_admin.universities'],
            ['Pending applications', \App\Models\UniversityApplication::where('status', 'pending')->count(), 'national_admin.applications'],
            ['Programs', \App\Models\Program::count(), 'national_admin.programs'],
            ['Users', \App\Models\User::count(), 'national_admin.users'],
        ] as [$label, $count, $destination])
            <a href="{{ route($destination) }}" class="rounded-2xl border border-border-default bg-surface p-6 hover:border-brand-400 transition-colors"><p class="text-sm text-ink-500">{{ $label }}</p><p class="text-3xl font-bold text-ink-950 mt-2">{{ number_format($count) }}</p></a>
        @endforeach
    </div>
    <div class="grid lg:grid-cols-2 gap-6 mt-8">
        <section class="rounded-2xl border border-border-default bg-surface p-6">
            <h2 class="text-xl font-bold">Grow the university directory</h2>
            <p class="text-sm text-ink-600 mt-3 mb-5">Review new listing requests, check institution details, and help students discover their options.</p>
            <x-ui.button :href="route('national_admin.applications')">Review applications</x-ui.button>
            <a href="{{ route('universities.join') }}" class="block mt-4 text-sm text-brand-700 underline">Open the university joining form</a>
        </section>
        <section class="rounded-2xl border border-border-default bg-surface p-6">
            <h2 class="text-xl font-bold">Keep information reliable</h2>
            <p class="text-sm text-ink-600 mt-3 mb-5">Review updates from university websites and manage the content used by the student counselor.</p>
            <div class="flex flex-wrap gap-3"><x-ui.button variant="outline" :href="route('national_admin.verification')">Review data</x-ui.button><x-ui.button variant="outline" :href="route('national_admin.chatbot_config')">Counselor settings</x-ui.button></div>
        </section>
    </div>
    <section class="rounded-2xl border border-border-default bg-surface p-6 mt-6">
        <h2 class="text-xl font-bold">Your role on UniGuide</h2>
        <p class="text-sm text-ink-600 mt-3">You manage the platform, its directory, users, and content. A listing approval means an institution can appear on UniGuide; it does not establish academic recognition or accreditation.</p>
    </section>
</x-layouts.national-admin>
