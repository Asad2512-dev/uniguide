<x-layouts.counselor :title="($activeConversation['title'] ?? 'Your future starts with the right questions') . ' — UniGuide Counselor'">
    @php
        $workspaceConfig = [
            'active' => $activeConversation, 'conversations' => $conversations,
            'authenticated' => auth()->check(), 'csrf' => csrf_token(),
            'messageUrl' => route('counselor.message'), 'newUrl' => route('counselor.chat'),
            'historySearch' => $historySearch ?? '',
        ];
    @endphp
    <div x-data="counselorWorkspace(@js($workspaceConfig))" x-viewport-workspace
        @resize.window="viewportWidth = window.innerWidth; if (window.innerWidth >= 1024) historyOpen = false; if (window.innerWidth >= 1440) contextDrawerOpen = false"
        class="counselor-workspace flex h-dvh min-h-0 overflow-hidden border-x border-border-default bg-surface">
        <aside class="hidden min-h-0 shrink-0 flex-col border-r border-border-default bg-canvas lg:flex" :class="sidebarCollapsed ? 'w-24' : 'w-64'" aria-label="Conversation navigation"><x-counselor.sidebar /></aside>
        <main id="main-content" tabindex="-1" class="relative flex min-h-0 min-w-0 flex-1 flex-col">
            <header class="flex min-h-20 shrink-0 items-center gap-2 border-b border-border-subtle px-3 sm:px-6 safe-area-top">
                <button type="button" @click="historyOpen = true" :aria-expanded="historyOpen.toString()" class="counselor-icon-button lg:hidden" aria-label="Open conversation history"><x-icon name="menu" class="size-5" /></button>
                <div class="min-w-0 flex-1"><p class="mb-1 text-[11px] font-semibold uppercase tracking-wider text-brand-700">UniGuide Counselor</p><h1 class="truncate font-heading text-sm font-bold text-ink-950 sm:text-base" x-text="title"></h1></div>
                <button type="button" x-show="hasContext" @click="toggleContext()" class="counselor-icon-button sm:w-auto sm:gap-2 sm:px-3" :aria-expanded="(viewportWidth >= 1440 ? contextOpen : contextDrawerOpen).toString()" aria-label="Show research context"><x-icon name="file-text" class="size-4" /><span class="hidden text-xs font-medium sm:inline" x-text="contextSources.length ? contextSources.length + ' sources' : 'Research context'"></span></button>
                <x-ui.dropdown align="right" width="w-56">
                    <x-slot:trigger><button type="button" x-show="activeId" :disabled="sending || managing" class="counselor-icon-button" aria-label="Conversation actions"><x-icon name="chevron-down" class="size-4" /></button></x-slot:trigger>
                    <x-slot:content><button type="button" @click="openRename(); open = false" class="counselor-menu-action">Rename conversation</button><button type="button" @click="togglePin(); open = false" class="counselor-menu-action" x-text="activeSummary?.pinned ? 'Unpin conversation' : 'Pin conversation'"></button><button type="button" @click="managementError = ''; deleteOpen = true; open = false" class="counselor-menu-action text-error-700">Delete conversation…</button></x-slot:content>
                </x-ui.dropdown>
                <a href="{{ route('counselor.chat') }}" class="counselor-icon-button" aria-label="New conversation"><x-icon name="plus" class="size-4" /></a>
            </header>
            <div x-ref="feed" @scroll.passive="trackScroll()" class="min-h-0 flex-1 overflow-y-auto overscroll-contain px-4 py-6 sm:px-8 sm:py-8">
                <div class="mx-auto w-full max-w-3xl">
                    <section x-show="!messages.length" x-cloak class="counselor-welcome py-5 sm:py-12">
                        <div class="mb-6 flex size-12 items-center justify-center rounded-2xl border border-brand-200 bg-brand-50 text-brand-700"><x-icon name="sparkles" class="size-6" /></div>
                        <p class="mb-3 text-xs font-semibold uppercase tracking-[0.16em] text-brand-700">A little clarity. A better next step.</p>
                        <h2 class="max-w-xl font-heading text-3xl font-bold leading-tight tracking-tight text-ink-950 sm:text-4xl">Your future starts with<br class="hidden sm:block" /> the right questions.</h2>
                        <p class="mt-4 max-w-lg text-sm leading-7 text-ink-600 sm:text-base">Explore universities, compare campuses and understand what to check before you apply. Keep your research and its sources together.</p>
                        <div class="mt-7 grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <button type="button" @click="usePrompt('Show NUML campuses')" class="counselor-starter"><x-icon name="building-2" class="size-4 text-brand-600" /><span>Explore NUML campuses<span class="block text-xs font-normal text-ink-500">Start with a source-backed institution</span></span><span class="ml-auto text-ink-400">↗</span></button>
                            <button type="button" @click="usePrompt('Compare NUML and FAST Islamabad')" class="counselor-starter"><x-icon name="scale" class="size-4 text-brand-600" /><span>Compare two universities<span class="block text-xs font-normal text-ink-500">See the records and the gaps</span></span><span class="ml-auto text-ink-400">↗</span></button>
                            <a href="{{ route('eligibility.index') }}" class="counselor-starter"><x-icon name="check-circle-2" class="size-4 text-brand-600" /><span>Check admission eligibility<span class="block text-xs font-normal text-ink-500">Open the eligibility workspace</span></span><span class="ml-auto text-ink-400">↗</span></a>
                            <a href="{{ route('scholarships.index') }}" class="counselor-starter"><x-icon name="award" class="size-4 text-brand-600" /><span>Discover scholarships<span class="block text-xs font-normal text-ink-500">Explore funding opportunities</span></span><span class="ml-auto text-ink-400">↗</span></a>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-x-5 gap-y-1"><a href="{{ route('programs.index') }}" class="counselor-text-button">Explore degree programs →</a><a href="{{ route('counselor.guided') }}" class="counselor-text-button">Plan my admission journey →</a></div>
                        <p class="mt-6 max-w-xl text-xs leading-6 text-ink-500">Registry mode: answers use UniGuide’s local records. A generative AI model is not connected. Missing fees and requirements are marked clearly; admission decisions remain with the university.</p>
                        <section x-show="conversations.length" class="mt-8 border-t border-border-subtle pt-5"><h3 class="mb-2 text-xs font-semibold text-ink-600">Pick up your research</h3><template x-for="conversation in conversations.slice(0, 3)" :key="conversation.id"><a :href="conversation.url" class="flex min-h-11 items-center justify-between gap-3 border-b border-border-subtle py-3 text-sm text-ink-700 hover:text-brand-700"><span class="truncate" x-text="conversation.title"></span><span aria-hidden="true">→</span></a></template></section>
                    </section>
                    <a x-show="olderUrl" :href="olderUrl" class="mb-6 inline-flex min-h-11 items-center text-sm font-medium text-brand-700">Load earlier messages ↑</a>
                    <a x-show="viewingOlder" :href="activeSummary?.url" class="mb-6 ml-4 inline-flex min-h-11 items-center text-sm text-ink-600">Back to latest</a>
                    <div class="space-y-8" role="log" aria-live="polite" aria-relevant="additions" :aria-busy="sending.toString()" aria-label="Conversation messages"><template x-for="message in messages" :key="message.id"><x-counselor.message /></template></div>
                    <div x-show="sending" x-cloak role="status" class="mt-7 flex items-center gap-3 pb-4 text-sm text-ink-500"><x-icon name="loader-2" class="size-4 animate-spin text-brand-600" />Looking up registry records…</div>
                </div>
            </div>
            <div class="pointer-events-none absolute inset-x-0 bottom-36 flex justify-center"><button type="button" x-show="!atBottom && messages.length" @click="scrollToLatest(true)" class="pointer-events-auto min-h-11 rounded-full border border-border-default bg-white px-4 text-xs font-semibold text-ink-700 shadow-dropdown">Latest message ↓</button></div>
            <div class="shrink-0 bg-surface px-3 pb-3 pt-2 sm:px-8 sm:pb-5 safe-area-bottom [--safe-bottom-padding:0.75rem] sm:[--safe-bottom-padding:1.25rem]">
                <div class="mx-auto max-w-3xl">
                    <div x-show="error" x-cloak role="alert" class="mb-3 rounded-xl border border-error-200 bg-error-50 p-3 text-sm text-error-700"><p x-text="error"></p><button type="button" x-show="failed" :disabled="sending" @click="sendMessage(failed)" class="mt-1 min-h-11 font-semibold underline">Retry this message</button></div>
                    <form action="{{ route('counselor.message') }}" method="POST" @submit.prevent="sendMessage()" class="counselor-composer rounded-2xl border border-border-strong bg-surface shadow-card transition-shadow focus-within:border-brand-400 focus-within:ring-4 focus-within:ring-brand-500/10">
                        @csrf
                        <input type="hidden" name="conversation_id" :value="activeId || 'conv-new'" />
                        <label class="block"><span class="sr-only">Message the UniGuide counselor</span><textarea x-ref="composer" name="message" x-model="draft" @input="resizeComposer($el)" @keydown.enter="if (!$event.shiftKey && !$event.isComposing) { $event.preventDefault(); sendMessage() }" rows="2" maxlength="2000" required :aria-invalid="characterCount > 1000 ? 'true' : 'false'" aria-describedby="counselor-character-count" enterkeyhint="send" placeholder="Which university or campus is on your mind?" class="block min-h-16 w-full resize-none rounded-t-2xl border-0 bg-transparent px-4 pt-4 text-base leading-6 text-ink-900 outline-none placeholder:text-ink-500 focus:ring-0 sm:px-5 sm:text-sm"></textarea></label>
                        <div class="flex items-center justify-between gap-2 px-3 pb-3 sm:px-4"><div class="text-[11px] text-ink-500"><span class="hidden sm:inline">Enter to send · Shift+Enter for a new line<span class="mx-2">·</span></span><span id="counselor-character-count" :class="characterCount > 1000 ? 'text-error-700 font-semibold' : ''" x-text="characterCount + '/1000'"></span></div>
                            @auth
                                <button type="submit" :disabled="!readyToSend" aria-label="Send message" class="inline-flex size-11 shrink-0 items-center justify-center rounded-xl bg-brand-600 text-white transition-colors hover:bg-brand-700 disabled:bg-surface-muted disabled:text-ink-400"><x-icon name="arrow-right" class="size-5" /></button>
                            @else
                                <a href="{{ route('auth.login') }}" class="inline-flex min-h-11 shrink-0 items-center rounded-xl bg-brand-600 px-4 text-xs font-semibold text-white hover:bg-brand-700">Sign in to send</a>
                            @endauth
                        </div>
                    </form>
                    @guest<p class="mt-2 px-1 text-xs leading-5 text-ink-500">Explore freely. Sign in to send and retain private conversations. Guest transcripts are not saved.</p>@endguest
                </div>
            </div>
        </main>
        <aside x-show="hasContext && contextOpen" x-cloak class="hidden w-72 shrink-0 border-l border-border-default min-[1440px]:block" aria-label="Research context"><x-counselor.intelligence /></aside>
        <div x-show="historyOpen" x-cloak x-modal-focus="historyOpen" @keydown.escape.window="historyOpen = false" @click.self="historyOpen = false" role="dialog" aria-modal="true" aria-label="Conversation history" class="fixed inset-0 z-50 bg-ink-950/40"><div class="absolute inset-y-0 left-0 w-80 max-w-full bg-canvas safe-area-top"><x-counselor.sidebar :mobile="true" /></div></div>
        <div x-show="contextDrawerOpen" x-cloak x-modal-focus="contextDrawerOpen" @keydown.escape.window="contextDrawerOpen = false" @click.self="contextDrawerOpen = false" role="dialog" aria-modal="true" aria-label="Research source details" class="fixed inset-0 z-50 bg-ink-950/40"><div class="absolute inset-y-0 right-0 w-96 max-w-full bg-white safe-area-top"><x-counselor.intelligence /></div></div>
        <div x-show="editOpen" x-cloak x-modal-focus="editOpen" @keydown.escape.window="if (!managing) editOpen = false" @click.self="if (!managing) editOpen = false" role="dialog" aria-modal="true" aria-label="Rename conversation" class="fixed inset-0 z-50 flex items-center justify-center bg-ink-950/40 p-4"><form @submit.prevent="saveTitle()" class="w-full max-w-md rounded-2xl bg-white p-5 shadow-modal"><h2 class="font-heading text-lg font-bold">Rename conversation</h2><label class="mt-4 block text-sm font-medium">Title<input x-model="editTitle" required maxlength="120" class="mt-2 min-h-11 w-full rounded-xl border border-border-default px-3 text-base" /></label><p x-show="managementError" x-text="managementError" role="alert" class="mt-3 text-sm text-error-700"></p><div class="mt-5 flex justify-end gap-2"><button type="button" @click="editOpen = false" :disabled="managing" class="counselor-text-button">Cancel</button><button type="submit" :disabled="managing || !editTitle.trim()" class="min-h-11 rounded-xl bg-brand-600 px-4 text-sm font-semibold text-white disabled:opacity-50">Save title</button></div></form></div>
        <div x-show="deleteOpen" x-cloak x-modal-focus="deleteOpen" @keydown.escape.window="if (!managing) deleteOpen = false" @click.self="if (!managing) deleteOpen = false" role="dialog" aria-modal="true" aria-label="Delete conversation confirmation" class="fixed inset-0 z-50 flex items-center justify-center bg-ink-950/40 p-4"><div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-modal"><h2 class="font-heading text-lg font-bold">Delete this conversation?</h2><p class="mt-3 text-sm leading-relaxed text-ink-600">Its saved messages will be permanently removed from your account. This cannot be undone.</p><p x-show="managementError" x-text="managementError" role="alert" class="mt-3 text-sm text-error-700"></p><div class="mt-5 flex justify-end gap-2"><button type="button" @click="deleteOpen = false" :disabled="managing" class="counselor-text-button">Keep conversation</button><button type="button" @click="deleteConversation()" :disabled="managing" class="min-h-11 rounded-xl bg-error-600 px-4 text-sm font-semibold text-white disabled:opacity-50">Delete conversation</button></div></div></div>
    </div>
</x-layouts.counselor>
