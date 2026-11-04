<x-layouts.public 
    title="AI University Counselor — UniGuide Pakistan"
    description="Intelligent Pakistani university admission guidance, statutory merit aggregate calculations, side-by-side program comparisons, and verified scholarship matching."
>
    <div x-data="{ 
        promptInput: '',
        isSubmitting: false,
        submitPrompt(text) {
            this.promptInput = text || this.promptInput;
            if (!this.promptInput.trim()) return;
            this.isSubmitting = true;
            $refs.promptForm.submit();
        }
    }" class="min-h-screen bg-canvas pb-24">

        <!-- Hero & Intelligent Prompt Composer Section -->
        <section class="relative border-b border-border-default/80 bg-gradient-to-b from-surface via-surface to-brand-50/20 overflow-hidden pt-10 pb-16 md:pt-14 md:pb-20">
            <!-- Subtle Radial Gradient Background Accents -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-96 pointer-events-none opacity-40">
                <div class="absolute -top-24 left-1/4 w-96 h-96 bg-brand-200/40 rounded-full blur-3xl"></div>
                <div class="absolute -top-20 right-1/4 w-80 h-80 bg-indigo-200/30 rounded-full blur-3xl"></div>
            </div>

            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <!-- Breadcrumb -->
                <div class="flex justify-center mb-6">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'AI Counselor', 'url' => route('counselor.index')]
                    ]" />
                </div>

                <!-- National Intelligence Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold font-sans mb-5 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span>
                    <x-icon name="sparkles" class="w-3.5 h-3.5 text-brand-600" />
                    <span>UniGuide National AI Admission Counselor</span>
                    <span class="text-ink-300">•</span>
                    <span class="font-mono text-[11px] text-brand-800 font-bold">Fall 2026 Calibrated</span>
                </div>

                <h1 class="font-heading font-bold text-3xl sm:text-4xl md:text-5xl text-ink-950 tracking-tight leading-tight mb-4">
                    Intelligent admission guidance, <br class="hidden sm:inline" />
                    <span class="bg-gradient-to-r from-brand-600 via-indigo-600 to-brand-800 bg-clip-text text-transparent">
                        grounded in verified statutory data.
                    </span>
                </h1>

                <p class="text-base sm:text-lg text-ink-600 font-sans max-w-2xl mx-auto mb-8 leading-relaxed">
                    Ask anything about Pakistani university merit formulas, closing cutoffs, tuition fees, entry-test weightages, and fully-funded HEC scholarships.
                </p>

                <!-- Large Intelligent Prompt Composer Card -->
                <div class="text-left bg-surface rounded-2xl border border-brand-200/90 shadow-card p-3 sm:p-4 mb-6 transition-all duration-200 focus-within:ring-2 focus-within:ring-brand-500/20 focus-within:border-brand-500">
                    <form x-ref="promptForm" action="{{ route('counselor.message') }}" method="POST">
                        @csrf
                        <div class="relative">
                            <textarea 
                                name="message" 
                                x-model="promptInput"
                                rows="3" 
                                placeholder="E.g., I scored 82% in FSc Pre-Engineering and want to study CS in Islamabad under PKR 180k. What are my chances at NUST, FAST, and COMSATS?"
                                class="w-full resize-none border-0 bg-transparent p-2 text-ink-900 placeholder:text-ink-400 text-sm sm:text-base font-sans focus:ring-0 focus:outline-none"
                                @keydown.enter.meta.prevent="submitPrompt()"
                                @keydown.enter.ctrl.prevent="submitPrompt()"
                                required
                            ></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-border-subtle mt-1">
                            <!-- Trust Micro-badge -->
                            <div class="flex items-center gap-2 text-xs text-ink-500 font-sans">
                                <x-icon name="shield-check" class="w-4 h-4 text-success-600 shrink-0" />
                                <span>Calibrated against HEC Act, PEC Level-II & NCEAC Category W records</span>
                            </div>

                            <!-- Actions & Submit -->
                            <div class="flex items-center justify-end gap-2">
                                <span class="hidden sm:inline-block text-[11px] font-mono text-ink-400">⌘ + Enter to send</span>
                                <button 
                                    type="submit"
                                    :disabled="!promptInput.trim() || isSubmitting"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-semibold font-sans shadow-xs transition-smooth cursor-pointer"
                                >
                                    <template x-if="!isSubmitting">
                                        <div class="flex items-center gap-1.5">
                                            <span>Ask Counselor</span>
                                            <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                                        </div>
                                    </template>
                                    <template x-if="isSubmitting">
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                            <span>Analyzing…</span>
                                        </div>
                                    </template>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Quick Query Pills -->
                <div class="flex flex-wrap items-center justify-center gap-2 text-xs">
                    <span class="text-ink-400 font-medium">Try asking:</span>
                    <button 
                        type="button" 
                        @click="submitPrompt('How is NUST CS aggregate calculated with NET, FSc, and Matric?')"
                        class="px-2.5 py-1 rounded-full bg-surface border border-border-default hover:border-brand-300 hover:text-brand-700 text-ink-600 transition-smooth cursor-pointer font-sans"
                    >
                        NUST CS Aggregate Formula
                    </button>
                    <button 
                        type="button" 
                        @click="submitPrompt('Compare FAST Islamabad vs NUST SEECS for Software Engineering')"
                        class="px-2.5 py-1 rounded-full bg-surface border border-border-default hover:border-brand-300 hover:text-brand-700 text-ink-600 transition-smooth cursor-pointer font-sans"
                    >
                        FAST vs NUST SEECS
                    </button>
                    <button 
                        type="button" 
                        @click="submitPrompt('What are the eligibility criteria for HEC Need-Based Scholarships?')"
                        class="px-2.5 py-1 rounded-full bg-surface border border-border-default hover:border-brand-300 hover:text-brand-700 text-ink-600 transition-smooth cursor-pointer font-sans"
                    >
                        HEC Need-Based Aid
                    </button>
                    <button 
                        type="button" 
                        @click="submitPrompt('65% FSc Pre-Engineering options in Punjab')"
                        class="px-2.5 py-1 rounded-full bg-surface border border-border-default hover:border-brand-300 hover:text-brand-700 text-ink-600 transition-smooth cursor-pointer font-sans"
                    >
                        65% FSc Options
                    </button>
                </div>
            </div>
        </section>

        <!-- Main Content Grid -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10">
            <!-- Guided Counseling Banner (High-Impact Recommendation Flow) -->
            <div class="mb-12 rounded-2xl border border-brand-200/80 bg-gradient-to-r from-brand-900 via-ink-950 to-brand-950 text-white p-6 sm:p-8 shadow-card relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-brand-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                    <div class="max-w-2xl space-y-2">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-white/10 backdrop-blur-xs border border-white/20 text-brand-200 text-xs font-semibold">
                            <x-icon name="sparkles" class="w-3.5 h-3.5 text-brand-300" />
                            <span>Recommended Evaluation Path</span>
                        </div>
                        <h2 class="font-heading font-bold text-2xl sm:text-3xl text-white tracking-tight">
                            Personalized Reach, Match & Safety Assessment
                        </h2>
                        <p class="text-white/80 text-sm font-sans leading-relaxed">
                            Complete our 6-step questionnaire covering your board marks, entrance test scores, preferred city, and household budget. We will calculate your exact mathematical aggregates across all universities and present structured admission likelihood tiers.
                        </p>
                    </div>

                    <div class="shrink-0 flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <x-ui.button 
                            variant="primary" 
                            size="lg" 
                            :href="route('counselor.guided')" 
                            iconRight="arrow-right"
                            class="bg-brand-500 hover:bg-brand-400 text-ink-950 font-bold border-0"
                        >
                            Start Guided Assessment
                        </x-ui.button>
                        <x-ui.button 
                            variant="outline" 
                            size="lg" 
                            :href="route('counselor.search')" 
                            class="border-white/20 text-white hover:bg-white/10"
                        >
                            Natural Language Search
                        </x-ui.button>
                    </div>
                </div>
            </div>

            <!-- Quick Action Feature Cards -->
            <div class="mb-14">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="font-heading font-bold text-xl text-ink-950">Intelligent Counseling Tools</h2>
                        <p class="text-xs text-ink-500 font-sans">Engineered for Pakistani secondary and higher secondary students.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($quickActions as $action)
                        <a 
                            href="{{ route($action['route']) }}"
                            class="group block p-5 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all duration-200 relative flex flex-col justify-between"
                        >
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-100 flex items-center justify-center text-brand-700 group-hover:scale-105 group-hover:bg-brand-600 group-hover:text-white transition-all">
                                        <x-icon :name="$action['icon']" class="w-5 h-5" />
                                    </div>
                                    <span class="text-[10px] uppercase font-mono font-bold px-2 py-0.5 rounded-full bg-surface-subtle border border-border-default text-ink-600">
                                        {{ $action['badge'] }}
                                    </span>
                                </div>
                                <h3 class="font-heading font-bold text-sm text-ink-950 group-hover:text-brand-600 transition-colors mb-2">
                                    {{ $action['title'] }}
                                </h3>
                                <p class="text-xs text-ink-600 font-sans leading-relaxed">
                                    {{ $action['description'] }}
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-border-subtle flex items-center text-xs font-semibold text-brand-600 group-hover:text-brand-700">
                                <span>Launch Workspace</span>
                                <x-icon name="arrow-right" class="w-3.5 h-3.5 ml-1 transition-transform group-hover:translate-x-1" />
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Two-Column Section: Suggested Topics & Recent Conversations -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-14">
                <!-- Left 2 Cols: Categorized Questions -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-heading font-bold text-lg text-ink-950">Explore Curated Counseling Inquiries</h2>
                            <p class="text-xs text-ink-500 font-sans">Frequently verified student questions with verified answers.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($suggestedCategories as $cat)
                            <div class="rounded-xl border border-border-default bg-surface p-4 flex flex-col justify-between shadow-2xs">
                                <div>
                                    <div class="flex items-center gap-2 mb-3 pb-2 border-b border-border-subtle">
                                        <div class="w-6 h-6 rounded bg-brand-50 text-brand-700 flex items-center justify-center">
                                            <x-icon :name="$cat['icon']" class="w-3.5 h-3.5" />
                                        </div>
                                        <h3 class="font-heading font-bold text-xs uppercase tracking-wider text-ink-800">
                                            {{ $cat['category'] }}
                                        </h3>
                                    </div>

                                    <ul class="space-y-2">
                                        @foreach($cat['prompts'] as $p)
                                            <li>
                                                <button 
                                                    type="button" 
                                                    @click="submitPrompt('{{ addslashes($p) }}')"
                                                    class="text-left w-full text-xs font-sans text-ink-700 hover:text-brand-600 hover:bg-surface-subtle p-1.5 rounded transition-colors flex items-start gap-1.5 group cursor-pointer"
                                                >
                                                    <x-icon name="message-square" class="w-3 h-3 text-ink-400 group-hover:text-brand-500 shrink-0 mt-0.5" />
                                                    <span class="line-clamp-2 leading-snug">{{ $p }}</span>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Col: Recent Archived Conversations -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-heading font-bold text-lg text-ink-950">Recent Sessions</h2>
                            <p class="text-xs text-ink-500 font-sans">Resume saved advisory threads.</p>
                        </div>
                        <a href="{{ route('counselor.history') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                            View All →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentConversations as $conv)
                            <div class="p-3.5 rounded-xl border border-border-default bg-surface hover:border-brand-200 hover:shadow-2xs transition-all">
                                <div class="flex items-center justify-between text-[11px] text-ink-400 font-sans mb-1.5">
                                    <span class="font-semibold text-brand-700 bg-brand-50 px-2 py-0.5 rounded">
                                        {{ $conv['topic_label'] ?? 'Counseling' }}
                                    </span>
                                    <span>{{ $conv['updated_at'] ?? 'Recently' }}</span>
                                </div>
                                <h4 class="font-heading font-bold text-xs text-ink-900 line-clamp-1 mb-1">
                                    {{ $conv['title'] }}
                                </h4>
                                <p class="text-[11px] text-ink-500 font-sans line-clamp-2 mb-3">
                                    {{ $conv['summary'] ?? 'Session discussion and institutional evaluation.' }}
                                </p>
                                <div class="flex items-center justify-between pt-2 border-t border-border-subtle text-xs">
                                    <span class="text-[11px] text-ink-400 font-mono">{{ $conv['message_count'] ?? 2 }} messages</span>
                                    <a 
                                        href="{{ route('counselor.chat', $conv['id']) }}"
                                        class="inline-flex items-center gap-1 font-semibold text-brand-600 hover:text-brand-700"
                                    >
                                        <span>Resume</span>
                                        <x-icon name="arrow-right" class="w-3 h-3" />
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 rounded-xl border border-dashed border-border-default text-center text-xs text-ink-400">
                                No previous conversations recorded.
                            </div>
                        @endforelse

                        <a 
                            href="{{ route('counselor.chat') }}"
                            class="block w-full py-2.5 rounded-xl border border-dashed border-brand-300 bg-brand-50/40 text-brand-700 text-center text-xs font-semibold hover:bg-brand-50 transition-smooth"
                        >
                            + Start Blank Conversation
                        </a>
                    </div>
                </div>
            </div>

            <!-- Transparency & AI Boundaries Card (HEC Trust Mandate) -->
            <div class="rounded-2xl border border-border-default bg-surface-subtle p-6 sm:p-8">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 pb-6 mb-6 border-b border-border-default">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-success-50 text-success-700 border border-success-200/80 flex items-center justify-center">
                            <x-icon name="shield-check" class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-base text-ink-950">Statutory Grounding & AI Trust Mandate</h3>
                            <p class="text-xs text-ink-500 font-sans">How UniGuide Pakistan distinguishes AI synthesis from official government records.</p>
                        </div>
                    </div>
                    <a 
                        href="{{ route('counselor.evidence') }}"
                        class="text-xs font-semibold text-brand-600 hover:text-brand-700 inline-flex items-center gap-1"
                    >
                        <span>Audit Statutory Gazette Register</span>
                        <x-icon name="external-link" class="w-3 h-3" />
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs font-sans">
                    <div class="space-y-3">
                        <h4 class="font-heading font-bold text-ink-900 flex items-center gap-1.5">
                            <x-icon name="check-circle" class="w-4 h-4 text-success-600" />
                            <span>What Our AI Counselor Computes & Verifies:</span>
                        </h4>
                        <ul class="space-y-2 text-ink-600">
                            @foreach($capabilities['capabilities'] as $cap)
                                <li class="flex items-start gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand-600 shrink-0 mt-1.5"></span>
                                    <span>{{ $cap }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="space-y-3">
                        <h4 class="font-heading font-bold text-ink-900 flex items-center gap-1.5">
                            <x-icon name="alert-circle" class="w-4 h-4 text-warning-600" />
                            <span>Statutory Boundaries & Advisory Scope:</span>
                        </h4>
                        <ul class="space-y-2 text-ink-600">
                            @foreach($capabilities['limitations'] as $lim)
                                <li class="flex items-start gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 shrink-0 mt-1.5"></span>
                                    <span>{{ $lim }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-layouts.public>
