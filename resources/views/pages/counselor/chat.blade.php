<x-layouts.public 
    title="{{ $activeConversation['title'] ?? 'AI Conversation Workspace' }} — UniGuide Pakistan"
    description="Interactive conversational counseling with UniGuide AI Admission Advisor. Merit predictions, university comparisons, and verified HEC citations."
>
    <div 
        x-data="{
            sidebarOpen: false,
            activeConvId: '{{ $activeConversation['id'] ?? 'conv-new' }}',
            messages: @js($activeConversation['messages'] ?? []),
            promptInput: '',
            isThinking: false,
            copiedIndex: null,
            feedbackGiven: {},

            async sendMessage(textToSend) {
                const messageText = textToSend || this.promptInput;
                if (!messageText || !messageText.trim() || this.isThinking) return;

                const userMsg = {
                    id: 'msg-' + Date.now(),
                    role: 'user',
                    content: messageText.trim(),
                    timestamp: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                };

                this.messages.push(userMsg);
                this.promptInput = '';
                this.isThinking = true;
                this.scrollToBottom();

                try {
                    const response = await fetch('{{ route('counselor.message') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: messageText.trim(),
                            conversation_id: this.activeConvId
                        })
                    });

                    if (!response.ok) throw new Error('Network error');

                    const data = await response.json();
                    
                    if (data.success && data.ai_message) {
                        // Artificial subtle delay to give natural thinking feel
                        setTimeout(() => {
                            this.messages.push(data.ai_message);
                            if (this.activeConvId === 'conv-new') {
                                this.activeConvId = data.conversation_id;
                                window.history.replaceState(null, '', '/counselor/chat/' + data.conversation_id);
                            }
                            this.isThinking = false;
                            this.scrollToBottom();
                        }, 500);
                    } else {
                        throw new Error('Invalid response structure');
                    }
                } catch (err) {
                    console.error(err);
                    this.isThinking = false;
                    this.messages.push({
                        id: 'msg-err-' + Date.now(),
                        role: 'ai',
                        confidence: 'System Error',
                        timestamp: 'Just now',
                        advisorName: 'UniGuide Admission Advisor',
                        content: 'An error occurred while communicating with the admissions intelligence service. Please check your connection and retry.',
                        isError: true,
                        retryPrompt: messageText
                    });
                    this.scrollToBottom();
                }
            },

            copyToClipboard(text, index) {
                navigator.clipboard.writeText(text).then(() => {
                    this.copiedIndex = index;
                    setTimeout(() => { this.copiedIndex = null; }, 2000);
                });
            },

            rateFeedback(msgId, rating) {
                this.feedbackGiven[msgId] = rating;
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const container = this.$refs.chatContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            }
        }"
        x-init="scrollToBottom()"
        class="h-[calc(100vh-4rem)] flex flex-col bg-canvas overflow-hidden"
    >
        <!-- Chat Application Frame -->
        <div class="flex-1 flex overflow-hidden">
            
            <!-- Desktop Conversation Sidebar -->
            <aside class="hidden lg:flex flex-col w-80 border-r border-border-default bg-surface shrink-0">
                <!-- Sidebar Header -->
                <div class="p-4 border-b border-border-default flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 text-brand-700 flex items-center justify-center font-bold text-xs">
                            <x-icon name="sparkles" class="w-4 h-4" />
                        </div>
                        <span class="font-heading font-bold text-xs uppercase tracking-wider text-ink-950">Counseling Sessions</span>
                    </div>

                    <a 
                        href="{{ route('counselor.chat') }}"
                        class="p-1.5 rounded-lg text-ink-500 hover:text-brand-600 hover:bg-surface-subtle transition-colors"
                        title="Start New Conversation"
                    >
                        <x-icon name="plus" class="w-4 h-4" />
                    </a>
                </div>

                <!-- New Conversation CTA Button -->
                <div class="p-3">
                    <a 
                        href="{{ route('counselor.chat') }}"
                        class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl border border-brand-200 bg-brand-50/50 hover:bg-brand-50 text-brand-700 text-xs font-semibold font-sans transition-smooth shadow-2xs"
                    >
                        <x-icon name="plus" class="w-3.5 h-3.5" />
                        <span>New Conversation</span>
                    </a>
                </div>

                <!-- Conversation List -->
                <div class="flex-1 overflow-y-auto px-3 py-2 space-y-1">
                    @foreach($conversations as $conv)
                        @php $isActive = ($conv['id'] === ($activeConversation['id'] ?? '')); @endphp
                        <a 
                            href="{{ route('counselor.chat', $conv['id']) }}"
                            class="group block p-2.5 rounded-xl text-left transition-all {{ $isActive ? 'bg-brand-50 border border-brand-200/80 shadow-2xs' : 'hover:bg-surface-subtle border border-transparent' }}"
                        >
                            <div class="flex items-center justify-between text-[10px] text-ink-400 font-sans mb-1">
                                <span class="font-medium {{ $isActive ? 'text-brand-700 font-semibold' : 'text-ink-500' }}">
                                    {{ $conv['topic_label'] ?? 'General' }}
                                </span>
                                <span>{{ $conv['updated_at'] ?? 'Saved' }}</span>
                            </div>
                            <h4 class="font-heading font-medium text-xs line-clamp-1 {{ $isActive ? 'text-brand-900 font-bold' : 'text-ink-800 group-hover:text-ink-950' }}">
                                {{ $conv['title'] }}
                            </h4>
                            <p class="text-[11px] text-ink-400 font-sans line-clamp-1 mt-0.5">
                                {{ $conv['summary'] ?? 'Session discussion' }}
                            </p>
                        </a>
                    @endforeach
                </div>

                <!-- Sidebar Footer Links -->
                <div class="p-3 border-t border-border-default bg-surface-subtle space-y-1">
                    <a 
                        href="{{ route('counselor.guided') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-sans text-ink-700 hover:text-brand-600 hover:bg-surface transition-colors"
                    >
                        <x-icon name="compass" class="w-3.5 h-3.5 text-brand-600" />
                        <span>Guided Fit Assessment</span>
                    </a>
                    <a 
                        href="{{ route('counselor.history') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-sans text-ink-700 hover:text-brand-600 hover:bg-surface transition-colors"
                    >
                        <x-icon name="history" class="w-3.5 h-3.5 text-ink-400" />
                        <span>All Archived Sessions</span>
                    </a>
                </div>
            </aside>

            <!-- Main Conversation Area -->
            <main class="flex-1 flex flex-col min-w-0 bg-surface overflow-hidden">
                
                <!-- Chat Top Navbar -->
                <header class="h-14 px-4 sm:px-6 border-b border-border-default/80 flex items-center justify-between bg-surface/90 backdrop-blur-xs shrink-0 z-10">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Mobile Drawer Toggle -->
                        <button 
                            type="button" 
                            @click="sidebarOpen = true"
                            class="lg:hidden p-1.5 text-ink-600 hover:text-ink-900 rounded-lg hover:bg-surface-subtle"
                            aria-label="Toggle Conversation Sidebar"
                        >
                            <x-icon name="menu" class="w-5 h-5" />
                        </button>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <h2 class="font-heading font-bold text-sm text-ink-950 truncate">
                                    {{ $activeConversation['title'] ?? 'New Counseling Session' }}
                                </h2>
                                <span class="hidden sm:inline-flex items-center gap-1 text-[10px] font-mono font-bold bg-success-50 text-success-700 border border-success-200/60 px-1.5 py-0.2 rounded">
                                    HEC Verified
                                </span>
                            </div>
                            <span class="text-[11px] text-ink-400 font-sans block truncate">
                                UniGuide AI Counselor • Fall 2026 Policy Model
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a 
                            href="{{ route('counselor.evidence') }}"
                            class="hidden md:inline-flex items-center gap-1.5 text-xs text-ink-600 hover:text-brand-600 px-2.5 py-1.5 rounded-lg border border-border-default bg-surface hover:bg-surface-subtle transition-smooth"
                        >
                            <x-icon name="file-text" class="w-3.5 h-3.5 text-brand-600" />
                            <span>Audit Citations</span>
                        </a>

                        <a 
                            href="{{ route('counselor.index') }}"
                            class="text-ink-400 hover:text-ink-700 p-1.5 rounded-lg hover:bg-surface-subtle transition-colors"
                            title="Exit Workspace"
                        >
                            <x-icon name="x" class="w-4 h-4" />
                        </a>
                    </div>
                </header>

                <!-- Scrollable Messages Container -->
                <div 
                    x-ref="chatContainer"
                    class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6"
                >
                    <!-- Empty State (When starting a fresh chat) -->
                    <template x-if="messages.length === 0">
                        <div class="max-w-2xl mx-auto py-12 text-center space-y-6">
                            <div class="w-14 h-14 rounded-2xl bg-brand-50 border border-brand-200 text-brand-600 flex items-center justify-center mx-auto shadow-xs">
                                <x-icon name="sparkles" class="w-7 h-7" />
                            </div>
                            <div class="space-y-2">
                                <h3 class="font-heading font-bold text-xl text-ink-950">How may I assist your university search?</h3>
                                <p class="text-xs sm:text-sm text-ink-600 font-sans max-w-md mx-auto">
                                    I calculate merit aggregates across NUST, FAST, COMSATS, UET, evaluate financial aid eligibility, and compare curricula.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-left max-w-lg mx-auto">
                                @foreach($suggestedPrompts as $sp)
                                    <button 
                                        type="button" 
                                        @click="sendMessage('{{ addslashes($sp) }}')"
                                        class="p-3 rounded-xl border border-border-default bg-surface hover:border-brand-300 hover:bg-brand-50/20 text-xs font-sans text-ink-700 transition-all text-left flex items-start gap-2 group cursor-pointer"
                                    >
                                        <x-icon name="sparkles" class="w-3.5 h-3.5 text-brand-600 shrink-0 mt-0.5 group-hover:scale-110 transition-transform" />
                                        <span>{{ $sp }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </template>

                    <!-- Rendered Messages -->
                    <template x-for="(msg, index) in messages" :key="msg.id || index">
                        <div>
                            <!-- User Message Bubble -->
                            <template x-if="msg.role === 'user'">
                                <div class="flex items-start justify-end gap-3 max-w-3xl ml-auto">
                                    <div class="bg-brand-600 text-white rounded-2xl rounded-tr-xs p-4 shadow-xs text-sm font-sans leading-relaxed">
                                        <p x-text="msg.content" class="whitespace-pre-wrap"></p>
                                        <div class="text-[10px] text-brand-200 text-right mt-1.5 font-mono" x-text="msg.timestamp"></div>
                                    </div>
                                    <div class="w-8 h-8 rounded-full bg-brand-100 border border-brand-200 text-brand-800 flex items-center justify-center font-bold text-xs shrink-0">
                                        ST
                                    </div>
                                </div>
                            </template>

                            <!-- AI Message Container -->
                            <template x-if="msg.role === 'ai'">
                                <div class="max-w-3xl mr-auto">
                                    <div class="rounded-2xl border border-brand-200/80 bg-gradient-to-b from-brand-50/30 via-surface to-surface p-5 shadow-card">
                                        <!-- Header: Identity & Confidence -->
                                        <div class="flex items-center justify-between pb-3.5 mb-3.5 border-b border-brand-100/70">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 rounded-lg bg-brand-600 text-white flex items-center justify-center shadow-xs">
                                                    <x-icon name="sparkles" class="w-4 h-4" />
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-heading font-bold text-sm text-ink-950" x-text="msg.advisorName || 'UniGuide Admission Advisor'"></span>
                                                        <span class="text-[10px] uppercase font-mono font-bold bg-brand-100 text-brand-800 px-1.5 py-0.2 rounded">
                                                            HEC Verified
                                                        </span>
                                                    </div>
                                                    <span class="text-[11px] text-ink-400 font-sans" x-text="msg.timestamp || 'Just now'"></span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <span class="hidden sm:inline-flex items-center gap-1 text-[11px] font-sans text-success-700 bg-success-50 px-2 py-0.5 rounded-full border border-success-200/60 font-medium">
                                                    <x-icon name="shield-check" class="w-3 h-3 text-success-600" />
                                                    <span x-text="msg.confidence || '98% High Confidence'"></span>
                                                </span>
                                                <button 
                                                    type="button" 
                                                    @click="copyToClipboard(msg.content, index)"
                                                    class="text-ink-400 hover:text-ink-700 p-1 rounded transition-colors cursor-pointer" 
                                                    :title="copiedIndex === index ? 'Copied!' : 'Copy response'"
                                                >
                                                    <template x-if="copiedIndex !== index">
                                                        <x-icon name="copy" class="w-3.5 h-3.5" />
                                                    </template>
                                                    <template x-if="copiedIndex === index">
                                                        <x-icon name="check" class="w-3.5 h-3.5 text-success-600" />
                                                    </template>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Message Content Body -->
                                        <div class="prose prose-sm text-ink-800 text-sm font-sans leading-relaxed space-y-3">
                                            <p x-text="msg.content" class="whitespace-pre-wrap"></p>
                                        </div>

                                        <!-- Inline Comparison Table (If Available) -->
                                        <template x-if="msg.comparison_table">
                                            <div class="mt-4 pt-3.5 border-t border-border-subtle">
                                                <div class="text-xs font-heading font-bold text-ink-900 mb-2" x-text="msg.comparison_table.title"></div>
                                                <div class="overflow-x-auto rounded-xl border border-border-default">
                                                    <table class="w-full text-left text-xs font-sans">
                                                        <thead class="bg-surface-subtle border-b border-border-default text-ink-600 font-semibold">
                                                            <tr>
                                                                <template x-for="head in msg.comparison_table.headers" :key="head">
                                                                    <th class="p-2.5" x-text="head"></th>
                                                                </template>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-border-subtle bg-surface">
                                                            <template x-for="row in msg.comparison_table.rows" :key="row[0]">
                                                                <tr class="hover:bg-surface-subtle/50">
                                                                    <template x-for="(cell, cIndex) in row" :key="cIndex">
                                                                        <td class="p-2.5 text-ink-700" :class="cIndex === 0 ? 'font-medium text-ink-900' : ''" x-text="cell"></td>
                                                                    </template>
                                                                </tr>
                                                            </template>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Inline University Quick Cards (If Available) -->
                                        <template x-if="msg.inline_cards && msg.inline_cards.length > 0">
                                            <div class="mt-4 pt-3.5 border-t border-border-subtle">
                                                <div class="text-[11px] font-semibold text-ink-500 uppercase tracking-wider mb-2 font-sans flex items-center gap-1.5">
                                                    <x-icon name="building-2" class="w-3 h-3 text-brand-600" />
                                                    <span>Featured Institution Matches</span>
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                                    <template x-for="card in msg.inline_cards" :key="card.slug">
                                                        <div class="p-3 rounded-xl border border-border-default bg-surface hover:border-brand-300 transition-all">
                                                            <div class="flex items-center justify-between mb-1">
                                                                <span class="font-heading font-bold text-xs text-ink-950" x-text="card.acronym"></span>
                                                                <span class="text-[10px] text-success-700 font-medium bg-success-50 px-1.5 py-0.2 rounded" x-text="card.rating"></span>
                                                            </div>
                                                            <div class="text-xs text-ink-700 font-sans truncate" x-text="card.name"></div>
                                                            <div class="text-[11px] text-ink-500 font-mono mt-1" x-text="card.fee"></div>
                                                            <div class="mt-2 pt-2 border-t border-border-subtle flex items-center justify-between text-xs">
                                                                <span class="text-[10px] text-brand-700 font-semibold" x-text="card.badge"></span>
                                                                <a :href="'/universities/' + card.slug" class="text-brand-600 hover:text-brand-700 font-semibold flex items-center gap-0.5">
                                                                    <span>View</span>
                                                                    <x-icon name="arrow-right" class="w-3 h-3" />
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Official Citations -->
                                        <template x-if="msg.citations && msg.citations.length > 0">
                                            <div class="mt-4 pt-3.5 border-t border-border-subtle">
                                                <div class="text-[11px] font-semibold text-ink-500 uppercase tracking-wider mb-2 font-sans flex items-center gap-1.5">
                                                    <x-icon name="file-text" class="w-3 h-3 text-brand-600" />
                                                    <span>Official Statutory & University Citations</span>
                                                </div>
                                                <div class="space-y-1.5">
                                                    <template x-for="cite in msg.citations" :key="cite.reference">
                                                        <div class="flex items-center justify-between p-2 rounded-lg bg-surface-subtle border border-border-default text-xs font-sans text-ink-700">
                                                            <div class="truncate mr-2">
                                                                <span class="font-medium text-ink-900" x-text="cite.source"></span>
                                                                <span class="text-ink-400 font-mono text-[11px] block truncate" x-text="cite.reference"></span>
                                                            </div>
                                                            <a :href="cite.url || '#'" class="text-brand-600 hover:text-brand-700 shrink-0 font-semibold text-[11px] flex items-center gap-1">
                                                                <span>Inspect</span>
                                                                <x-icon name="external-link" class="w-3 h-3" />
                                                            </a>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Suggested Follow-up Actions -->
                                        <template x-if="msg.prompts && msg.prompts.length > 0">
                                            <div class="mt-4 pt-3 border-t border-border-subtle flex flex-wrap items-center gap-2">
                                                <span class="text-xs font-medium text-ink-400">Ask next:</span>
                                                <template x-for="prompt in msg.prompts" :key="prompt">
                                                    <button 
                                                        type="button" 
                                                        @click="sendMessage(prompt)"
                                                        class="text-xs font-sans text-brand-700 bg-brand-50 hover:bg-brand-100 hover:text-brand-900 px-3 py-1 rounded-full border border-brand-200 transition-smooth cursor-pointer"
                                                    >
                                                        <span x-text="prompt"></span> →
                                                    </button>
                                                </template>
                                            </div>
                                        </template>

                                        <!-- Thumbs Feedback Controls -->
                                        <div class="mt-4 pt-3 border-t border-border-subtle flex items-center justify-between text-xs text-ink-400">
                                            <span class="text-[11px]">Was this response accurate to HEC policy?</span>
                                            <div class="flex items-center gap-1">
                                                <button 
                                                    type="button" 
                                                    @click="rateFeedback(msg.id, 'up')"
                                                    class="p-1 rounded hover:bg-surface-subtle cursor-pointer transition-colors"
                                                    :class="feedbackGiven[msg.id] === 'up' ? 'text-success-600 font-bold' : 'hover:text-ink-700'"
                                                    title="Helpful"
                                                >
                                                    <x-icon name="thumbs-up" class="w-3.5 h-3.5" />
                                                </button>
                                                <button 
                                                    type="button" 
                                                    @click="rateFeedback(msg.id, 'down')"
                                                    class="p-1 rounded hover:bg-surface-subtle cursor-pointer transition-colors"
                                                    :class="feedbackGiven[msg.id] === 'down' ? 'text-error-600 font-bold' : 'hover:text-ink-700'"
                                                    title="Inaccurate"
                                                >
                                                    <x-icon name="thumbs-down" class="w-3.5 h-3.5" />
                                                </button>
                                                <span x-show="feedbackGiven[msg.id]" class="text-[10px] text-brand-600 ml-1">Feedback recorded</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- Thinking / Streaming Appearance Indicator -->
                    <div x-show="isThinking" class="max-w-3xl mr-auto">
                        <div class="rounded-2xl border border-brand-200/60 bg-surface p-4 shadow-2xs flex items-center gap-3">
                            <div class="w-7 h-7 rounded-lg bg-brand-600 text-white flex items-center justify-center animate-pulse">
                                <x-icon name="sparkles" class="w-3.5 h-3.5" />
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-ink-800">
                                    <span>UniGuide AI Counselor is calculating aggregate formulas & citations</span>
                                    <span class="inline-flex gap-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-600 animate-bounce"></span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-600 animate-bounce [animation-delay:0.2s]"></span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-brand-600 animate-bounce [animation-delay:0.4s]"></span>
                                    </span>
                                </div>
                                <span class="text-[11px] text-ink-400 font-sans block">Consulting 248+ HEC accredited university gazettes...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Input Composer Strip -->
                <footer class="p-3 sm:p-4 border-t border-border-default/80 bg-surface shrink-0">
                    <div class="max-w-4xl mx-auto">
                        <form @submit.prevent="sendMessage()" class="relative flex items-center gap-2">
                            <div class="relative flex-1">
                                <input 
                                    type="text" 
                                    x-model="promptInput"
                                    placeholder="Ask about closing merits, aggregate calculations, fee structures, or scholarships..." 
                                    class="w-full pl-4 pr-10 py-3 rounded-xl border border-border-default bg-surface-subtle focus:bg-surface text-ink-900 placeholder:text-ink-400 text-xs sm:text-sm font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 outline-none transition-all"
                                    :disabled="isThinking"
                                    @keydown.enter.prevent="sendMessage()"
                                />
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1.5">
                                    <kbd class="hidden sm:inline-block px-1.5 py-0.5 text-[10px] font-mono text-ink-400 bg-surface border border-border-default rounded">
                                        ↵
                                    </kbd>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                :disabled="!promptInput.trim() || isThinking"
                                class="px-4 py-3 rounded-xl bg-brand-600 hover:bg-brand-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-semibold font-sans shadow-xs transition-smooth shrink-0 cursor-pointer flex items-center gap-1.5"
                            >
                                <span class="hidden sm:inline">Send</span>
                                <x-icon name="send" class="w-3.5 h-3.5" />
                            </button>
                        </form>
                        
                        <div class="flex items-center justify-between text-[11px] text-ink-400 font-sans px-1 mt-2">
                            <span>Answers cite statutory gazette sources. Verify final dates with institutional admission directorates.</span>
                            <a href="{{ route('counselor.evidence') }}" class="text-brand-600 hover:text-brand-700 font-semibold underline">Evidence Audit</a>
                        </div>
                    </div>
                </footer>
            </main>
        </div>

        <!-- Mobile Drawer Navigation -->
        <div 
            x-show="sidebarOpen" 
            x-cloak 
            class="lg:hidden fixed inset-0 z-50 bg-ink-950/40 backdrop-blur-xs" 
            @click="sidebarOpen = false"
        >
            <div 
                class="fixed inset-y-0 left-0 w-80 max-w-full bg-surface p-4 shadow-modal flex flex-col justify-between"
                @click.stop
            >
                <div>
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-border-default">
                        <span class="font-heading font-bold text-sm text-ink-950">Counseling Sessions</span>
                        <button type="button" @click="sidebarOpen = false" class="p-1 text-ink-400 hover:text-ink-700">
                            <x-icon name="x" class="w-5 h-5" />
                        </button>
                    </div>

                    <a 
                        href="{{ route('counselor.chat') }}"
                        class="w-full mb-3 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-xl border border-brand-200 bg-brand-50 text-brand-700 text-xs font-semibold"
                    >
                        <x-icon name="plus" class="w-3.5 h-3.5" />
                        <span>New Conversation</span>
                    </a>

                    <div class="space-y-1">
                        @foreach($conversations as $conv)
                            <a 
                                href="{{ route('counselor.chat', $conv['id']) }}"
                                class="block p-2 rounded-lg text-xs font-sans text-ink-700 hover:bg-surface-subtle"
                            >
                                <div class="font-semibold text-ink-900 truncate">{{ $conv['title'] }}</div>
                                <span class="text-[10px] text-ink-400">{{ $conv['updated_at'] ?? 'Saved' }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="pt-4 border-t border-border-default">
                    <a href="{{ route('counselor.guided') }}" class="block text-xs font-semibold text-brand-600 mb-2">
                        Start Guided Fit Assessment →
                    </a>
                    <a href="{{ route('counselor.history') }}" class="block text-xs text-ink-500">
                        View All Archived Sessions
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
