@props(['mobile' => false])
<div class="flex h-full min-h-0 flex-col">
    <div class="flex h-20 shrink-0 items-center justify-between gap-2 px-4">
        <a x-show="{{ $mobile ? 'true' : '!sidebarCollapsed' }}" href="{{ route('home') }}" class="flex min-h-11 items-center gap-2.5 rounded-lg" aria-label="UniGuide home">
            <x-brand.logo size="sm" />
            <span x-show="{{ $mobile ? 'true' : '!sidebarCollapsed' }}" class="font-heading text-sm font-extrabold tracking-tight">UniGuide <span class="text-brand-600">AI</span><span class="mt-0.5 block text-[11px] font-medium tracking-normal text-ink-500">University Counselor</span></span>
        </a>
        @if($mobile)
            <button type="button" @click="historyOpen = false" class="counselor-icon-button" aria-label="Close conversation history"><x-icon name="x" class="size-4" /></button>
        @else
            <button type="button" @click="sidebarCollapsed = !sidebarCollapsed" class="counselor-icon-button" :aria-label="sidebarCollapsed ? 'Expand conversation sidebar' : 'Collapse conversation sidebar'" :aria-expanded="(!sidebarCollapsed).toString()"><x-icon name="menu" class="size-4" /></button>
        @endif
    </div>
    <div class="px-3 pb-4">
        <a href="{{ route('counselor.chat') }}" class="flex min-h-11 items-center justify-center gap-2 rounded-xl border border-brand-200 bg-white px-3 text-sm font-semibold text-brand-700 shadow-xs hover:bg-brand-50" aria-label="New Conversation"><x-icon name="plus" class="size-4 shrink-0" /><span x-show="{{ $mobile ? 'true' : '!sidebarCollapsed' }}">New Conversation</span></a>
    </div>
    <div x-show="{{ $mobile ? 'true' : '!sidebarCollapsed' }}" class="flex min-h-0 flex-1 flex-col">
        <form action="{{ route('counselor.history') }}" method="GET" class="px-3 pb-4">
            <label class="relative block"><span class="sr-only">Search your conversations</span><x-icon name="search" class="pointer-events-none absolute left-3 top-3 size-4 text-ink-500" /><input name="q" x-model="historyQuery" type="search" placeholder="Search conversations" class="min-h-11 w-full rounded-xl border border-transparent bg-surface-muted/60 pl-9 pr-3 text-sm focus:border-brand-300 focus:bg-white" /></label>
        </form>
        <nav aria-label="Counseling Sessions" class="min-h-0 flex-1 space-y-5 overflow-y-auto overscroll-contain px-3 pb-4">
            <template x-for="group in ['Pinned', 'Today', 'Earlier']" :key="group">
                <section x-show="groupConversations(group).length">
                    <h2 class="mb-2 px-2 text-[11px] font-semibold uppercase tracking-wider text-ink-500" x-text="group"></h2>
                    <template x-for="conversation in groupConversations(group)" :key="conversation.id">
                        <a :href="conversation.url" :aria-current="activeId === conversation.id ? 'page' : null" class="mb-1 block rounded-xl px-3 py-3 transition-colors" :class="activeId === conversation.id ? 'bg-white shadow-xs text-brand-800' : 'text-ink-700 hover:bg-white/70'">
                            <span class="line-clamp-2 break-words text-sm font-medium leading-snug" x-text="conversation.title"></span>
                            <span class="mt-1 block text-[11px] text-ink-500" x-text="formatDate(conversation.updated_at)"></span>
                        </a>
                    </template>
                </section>
            </template>
            <p x-show="!filteredConversations.length" class="px-2 text-sm leading-relaxed text-ink-500" x-text="historyQuery ? 'No matching conversations in this list. Press Enter to search your archive.' : 'Your conversations will appear here after you sign in and send a message.'"></p>
        </nav>
        <a href="{{ route('counselor.history') }}" class="mx-3 mb-3 flex min-h-11 items-center gap-2 rounded-lg px-3 text-xs font-medium text-ink-600 hover:bg-white"><x-icon name="history" class="size-4" />Search all conversations<span class="ml-auto text-ink-500">50 recent</span></a>
    </div>
    <div class="shrink-0 border-t border-border-default/60 px-3 py-3 safe-area-bottom">
        <a href="{{ route(!auth()->check() ? 'auth.login' : (in_array(auth()->user()->role, ['admin', 'super_admin', 'hec_regulator']) ? 'national_admin.settings' : (in_array(auth()->user()->role, ['university', 'university_admin', 'university_staff']) ? 'uni_admin.settings' : 'student.settings'))) }}" class="flex min-h-11 items-center gap-2.5 rounded-xl px-2 text-sm text-ink-700 hover:bg-white" aria-label="{{ auth()->check() ? 'Account settings' : 'Sign in to save conversations' }}"><span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-border-default bg-white"><x-icon name="user" class="size-4" /></span><span x-show="{{ $mobile ? 'true' : '!sidebarCollapsed' }}" class="min-w-0 truncate">{{ auth()->user()?->name ?? 'Sign in to save your research' }}</span></a>
    </div>
</div>
