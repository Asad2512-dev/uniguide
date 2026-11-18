<x-layouts.public title="Your notifications — UniGuide">
    <div class="mx-auto max-w-3xl px-4 py-10 sm:py-14">
        <x-ui.page-header title="Your notifications" description="Updates and reminders for your UniGuide account." />
        <a href="{{ route(auth()->user()->isNationalAdmin() ? 'national_admin.dashboard' : (auth()->user()->isUniversityAdmin() ? 'uni_admin.dashboard' : 'student.dashboard')) }}" class="inline-block mb-6 text-sm text-brand-700 hover:underline">← Back to your dashboard</a>
        @if(session('toast_success'))<p role="status" class="mb-5 text-sm text-success-700">{{ session('toast_success') }}</p>@endif
        @if(auth()->user()->notifications()->where('status', 'active')->whereNull('read_at')->exists())<form method="POST" action="{{ route('notifications.read_all') }}" class="mb-5">@csrf<x-ui.button type="submit" variant="outline">Mark all as read</x-ui.button></form>@endif
        <div class="rounded-xl border border-border-default bg-surface divide-y divide-border-default">
            @forelse($notifications as $notification)
                <article class="p-5 {{ $notification->read_at ? '' : 'bg-brand-50/40' }}"><div class="flex flex-wrap justify-between gap-2"><h2 class="text-base font-semibold text-ink-950">{{ $notification->title }}</h2><span class="text-xs text-ink-500">{{ $notification->read_at ? 'Read' : 'Unread' }}</span></div><p class="text-sm text-ink-600 mt-2">{{ $notification->message }}</p><p class="text-xs text-ink-500 mt-3">{{ ($notification->dispatched_at ?? $notification->created_at)?->diffForHumans() }}</p><div class="flex flex-wrap items-center gap-5 mt-3">@if($notification->safeActionUrl())<a href="{{ $notification->safeActionUrl() }}" class="text-sm text-brand-700 hover:underline">View details</a>@endif @unless($notification->read_at)<form method="POST" action="{{ route('notifications.read', $notification->id) }}">@csrf<button type="submit" class="min-h-11 text-sm text-brand-700 hover:underline">Mark as read</button></form>@endunless</div></article>
            @empty
                <div class="p-10 text-center"><h2 class="font-semibold text-ink-900">You’re all caught up</h2><p class="text-sm text-ink-500 mt-2">New notifications will appear here.</p></div>
            @endforelse
        </div><div class="mt-6">{{ $notifications->links() }}</div>
    </div>
</x-layouts.public>
