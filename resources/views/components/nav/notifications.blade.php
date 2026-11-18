@php
    $notificationQuery = auth()->user()?->notifications()->where('status', 'active');
    $unreadCount = $notificationQuery ? (clone $notificationQuery)->whereNull('read_at')->count() : 0;
    $recentNotifications = $notificationQuery ? (clone $notificationQuery)->latest()->limit(5)->get() : collect();
@endphp
<div x-data="{ open: false }" class="relative" @keydown.escape.window="if (open) { open = false; $refs.bell.focus(); }" @click.outside="open = false">
    <button x-ref="bell" type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="notification-panel" aria-label="Notifications{{ $unreadCount ? ', '.$unreadCount.' unread' : '' }}" class="relative inline-flex min-h-11 min-w-11 items-center justify-center rounded-lg text-ink-600 hover:text-ink-950 hover:bg-surface-subtle transition-colors">
        <x-icon name="bell" class="w-5 h-5" />
        @if($unreadCount > 0)<span data-notification-unread-indicator class="absolute top-2 right-2 w-2 h-2 rounded-full bg-warning-500 ring-2 ring-surface" aria-hidden="true"></span>@endif
    </button>
    <section id="notification-panel" x-show="open" x-cloak x-transition.opacity aria-labelledby="notification-panel-title" class="fixed inset-x-4 top-[calc(4rem+env(safe-area-inset-top))] mt-3 w-auto sm:absolute sm:inset-x-auto sm:right-0 sm:top-auto sm:w-[23rem] rounded-xl border border-border-default bg-surface shadow-dropdown z-40 overflow-hidden">
        <div class="flex items-center justify-between gap-3 p-4 border-b border-border-default"><h2 id="notification-panel-title" class="text-sm font-bold text-ink-950">Notifications</h2><button type="button" @click="open = false; $refs.bell.focus()" aria-label="Close notifications" class="p-2 rounded text-ink-500 hover:bg-surface-subtle"><x-icon name="x" class="w-4 h-4" /></button></div>
        @auth
            @if($unreadCount > 0)<form method="POST" action="{{ route('notifications.read_all') }}" class="px-4 py-2 border-b border-border-subtle">@csrf<button class="min-h-9 text-xs font-semibold text-brand-700 hover:underline" type="submit">Mark all as read</button></form>@endif
            <div class="max-h-[min(26rem,60dvh)] overflow-y-auto overscroll-contain">
                @forelse($recentNotifications as $notification)
                    <article class="p-4 border-b border-border-subtle {{ $notification->read_at ? '' : 'bg-brand-50/40' }}">
                        <h3 class="text-sm font-semibold text-ink-950">{{ $notification->title }}</h3><p class="text-xs leading-relaxed text-ink-600 mt-1">{{ $notification->message }}</p><p class="text-xs text-ink-500 mt-2">{{ ($notification->dispatched_at ?? $notification->created_at)?->diffForHumans() }}</p>
                        <div class="flex flex-wrap items-center gap-4 mt-2">
                            @if($notification->safeActionUrl())<a class="text-xs font-semibold text-brand-700 hover:underline" href="{{ $notification->safeActionUrl() }}">View details</a>@endif
                            @unless($notification->read_at)<form method="POST" action="{{ route('notifications.read', $notification->id) }}">@csrf<button type="submit" class="min-h-9 text-xs text-brand-700 hover:underline">Mark as read</button></form>@endunless
                        </div>
                    </article>
                @empty
                    <div class="px-5 py-8 text-center"><p class="text-sm font-semibold text-ink-800">You’re all caught up</p><p class="text-xs text-ink-500 mt-2">New notifications will appear here.</p></div>
                @endforelse
            </div>
            <a href="{{ route('notifications.index') }}" class="flex min-h-11 items-center justify-center p-3 text-sm font-semibold text-brand-700 hover:bg-surface-subtle">View all notifications</a>
        @else
            <div class="p-5 text-sm text-ink-600">Sign in to view your notifications.<a href="{{ route('auth.login') }}" class="block mt-3 font-semibold text-brand-700">Sign in</a></div>
        @endauth
    </section>
</div>
