<x-layouts.national-admin title="University applications — UniGuide">
    <x-ui.page-header title="University applications" description="Review requests from universities that want to join UniGuide. Approval adds a directory listing; it does not grant accreditation or an administrator account." />
    @if(session('toast_success'))<p role="status" class="my-4 rounded-xl bg-emerald-50 p-4 text-emerald-900">{{ session('toast_success') }}</p>@endif
    <div class="space-y-5 mt-6">
        @forelse($applications as $application)
            <article class="rounded-2xl border border-border-default bg-surface p-6">
                <div class="flex flex-wrap justify-between gap-3"><h2 class="text-xl font-bold">{{ $application->name }}</h2><span class="text-sm font-semibold">{{ ucfirst($application->status) }}</span></div>
                <p class="text-sm text-ink-500 mt-1">{{ $application->reference }} · {{ $application->created_at->format('d M Y') }} · {{ $application->city }} · {{ ucfirst($application->sector) }}</p>
                <p class="my-4 text-sm whitespace-pre-line">{{ $application->description }}</p>
                <a href="{{ $application->website }}" target="_blank" rel="noopener noreferrer" class="text-brand-700 underline break-all">{{ $application->website }}</a>
                <p class="text-sm mt-3">Contact: {{ $application->contact_name }} · {{ $application->email }} · {{ $application->phone }}</p>
                @if($application->status === 'pending')
                    <form method="POST" action="{{ route('national_admin.applications.review', $application) }}" class="flex flex-wrap gap-3 mt-5">@csrf
                        <x-ui.button type="submit" name="decision" value="approved">Approve listing</x-ui.button>
                        <x-ui.button type="submit" name="decision" value="rejected" variant="secondary">Reject request</x-ui.button>
                    </form>
                @endif
            </article>
        @empty
            <div class="rounded-2xl border border-border-default bg-surface p-10 text-center"><h2 class="text-xl font-bold">No applications yet</h2><p class="mt-2 text-ink-600">New university listing requests will appear here.</p><a class="inline-block mt-4 text-brand-700 underline" href="{{ route('universities.join') }}">View the joining form</a></div>
        @endforelse
        {{ $applications->links() }}
    </div>
</x-layouts.national-admin>
