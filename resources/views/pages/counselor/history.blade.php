<x-layouts.public 
    title="Counseling Sessions History — UniGuide Pakistan"
    description="Review, resume, and export your archived AI university counseling sessions and admission strategy consultations."
>
    <div x-data="{ deleteModalOpen: false, deleteActionUrl: '', deleteItemName: '' }" class="min-h-screen bg-canvas pb-24">
        
        <!-- Header Banner -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="landing-container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'AI Counselor', 'url' => route('counselor.index')],
                        ['label' => 'Archived Counseling Sessions', 'url' => route('counselor.history')]
                    ]" />
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                            <x-icon name="history" class="w-3.5 h-3.5 text-brand-600" />
                            <span>Advisory Transcripts Archive</span>
                        </div>
                        <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                            Consultation History & Transcripts
                        </h1>
                        <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                            Access previous calculations, compare historical merit inquiries, or export your consultation transcripts for university applications.
                        </p>
                    </div>

                    <a 
                        href="{{ route('counselor.chat') }}"
                        class="shrink-0 inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold font-sans shadow-xs transition-smooth"
                    >
                        <x-icon name="plus" class="w-3.5 h-3.5" />
                        <span>Start New Session</span>
                    </a>
                </div>

                <!-- Filters & Search Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mt-8 pt-6 border-t border-border-subtle">
                    <!-- Topic Pills -->
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach($filters as $key => $label)
                            <a 
                                href="{{ route('counselor.history', ['filter' => $key, 'q' => $searchTerm]) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-smooth {{ $activeFilter === $key ? 'bg-brand-600 text-white shadow-2xs' : 'bg-surface border border-border-default text-ink-700 hover:bg-surface-subtle' }}"
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Search Input -->
                    <form action="{{ route('counselor.history') }}" method="GET" class="relative max-w-xs w-full">
                        <input type="hidden" name="filter" value="{{ $activeFilter }}" />
                        <x-icon name="search" class="w-4 h-4 text-ink-400 absolute left-3 top-1/2 -translate-y-1/2" />
                        <input 
                            type="text" 
                            name="q" 
                            value="{{ $searchTerm }}"
                            placeholder="Search transcripts..." 
                            class="w-full pl-9 pr-4 py-1.5 rounded-xl border border-border-default bg-surface-subtle focus:bg-surface text-ink-900 placeholder:text-ink-400 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none"
                        />
                    </form>
                </div>
            </div>
        </section>

        <!-- Main Sessions Grid -->
        <main class="landing-container mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($conversations as $conv)
                    <div class="p-5 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-xs sm:text-[11px] text-ink-400 font-sans mb-2">
                                <span class="font-bold text-brand-700 bg-brand-50 border border-brand-100 px-2 py-0.5 rounded">
                                    {{ $conv['topic_label'] ?? 'Counseling' }}
                                </span>
                                <span class="font-mono">{{ $conv['updated_at'] ?? 'Recently' }}</span>
                            </div>

                            <h3 class="font-heading font-bold text-base text-ink-950 line-clamp-1 mb-1">
                                {{ $conv['title'] }}
                            </h3>
                            <p class="text-xs text-ink-600 font-sans line-clamp-3 mb-4 leading-relaxed">
                                {{ $conv['summary'] ?? 'Session discussion regarding Pakistani higher education institutions and criteria.' }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                            <span class="text-ink-600 font-mono text-xs sm:text-[11px]">{{ $conv['message_count'] ?? 2 }} messages</span>

                            <div class="flex items-center gap-3">
                                <button 
                                    type="button" 
                                    @click="deleteActionUrl = '{{ route('counselor.destroy', $conv['id']) }}'; deleteItemName = '{{ addslashes($conv['title']) }}'; deleteModalOpen = true"
                                    class="text-ink-400 hover:text-error-600 cursor-pointer p-1 transition-colors" 
                                    title="Delete conversation"
                                    aria-label="Delete consultation {{ $conv['title'] }}"
                                >
                                    <x-icon name="trash-2" class="w-3.5 h-3.5" />
                                </button>

                                <a 
                                    href="{{ route('counselor.chat', $conv['id']) }}"
                                    class="inline-flex items-center gap-1 font-semibold text-brand-600 hover:text-brand-700"
                                >
                                    <span>Resume</span>
                                    <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full p-12 rounded-2xl border border-dashed border-border-default bg-surface text-center space-y-4">
                        <div class="w-12 h-12 rounded-xl bg-surface-subtle border border-border-default text-ink-400 flex items-center justify-center mx-auto">
                            <x-icon name="history" class="w-6 h-6" />
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-heading font-bold text-base text-ink-900">No archived sessions found</h3>
                            <p class="text-xs text-ink-500 font-sans">
                                {{ $searchTerm ? 'No sessions match your search query "' . $searchTerm . '".' : 'You have not completed any advisory sessions yet.' }}
                            </p>
                        </div>
                        <a 
                            href="{{ route('counselor.chat') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-brand-600 text-white text-xs font-semibold shadow-xs"
                        >
                            <x-icon name="sparkles" class="w-3.5 h-3.5" />
                            <span>Start First Advisory Consultation</span>
                        </a>
                    </div>
                @endforelse
            </div>
        </main>

        <!-- Delete Confirmation Modal -->
        <template x-teleport="body">
            <div 
                x-show="deleteModalOpen" 
                x-cloak 
                class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 md:py-12"
                role="dialog" 
                aria-modal="true" 
                aria-labelledby="delete-history-modal-title"
                @keydown.escape.window="deleteModalOpen = false"
            >
                <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 bg-ink-950/40 backdrop-blur-xs" @click="deleteModalOpen = false"></div>
                <div class="flex min-h-full items-center justify-center p-2 text-center sm:p-0">
                    <div x-show="deleteModalOpen" x-transition class="relative transform overflow-hidden rounded-2xl bg-surface border border-border-default text-left shadow-modal w-full max-w-md p-6" @click.stop>
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-error-50 text-error-600 border border-error-200">
                                <x-icon name="trash-2" class="w-5 h-5" />
                            </div>
                            <div class="flex-1">
                                <h3 class="text-base font-heading font-bold text-ink-950" id="delete-history-modal-title">Delete Consultation History?</h3>
                                <p class="mt-1 text-xs text-ink-600 font-sans leading-relaxed">
                                    Are you sure you want to remove <strong class="text-ink-950" x-text="deleteItemName"></strong>? All messages and recommendations from this session will be permanently deleted.
                                </p>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-border-subtle">
                            <button type="button" @click="deleteModalOpen = false" class="inline-flex min-h-11 items-center justify-center px-4 py-2 text-xs font-semibold text-ink-700 hover:bg-surface-subtle rounded-lg border border-border-default transition-colors">
                                Cancel
                            </button>
                            <form :action="deleteActionUrl" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-white bg-error-600 hover:bg-error-700 active:bg-error-800 rounded-lg shadow-xs transition-colors">
                                    <x-icon name="trash-2" class="size-4" />
                                    <span>Yes, Delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-layouts.public>

