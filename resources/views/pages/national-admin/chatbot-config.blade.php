<x-layouts.national-admin title="AI Chatbot Configuration & Statutory Guardrails">
    <x-ui.page-header 
        title="AI Chatbot Configuration"
        description="Federal oversight hyperparameters, prompt engineering, statutory rules injection, and strict citation enforcement for UniGuide's AI Counselor."
        badge="Regulatory Safety Tier"
        badgeVariant="brand"
    >
        <x-slot:breadcrumbs>
            <x-ui.breadcrumbs 
                :items="[
                    ['label' => 'Federal Oversight', 'url' => route('national_admin.dashboard')],
                    ['label' => 'AI Chatbot Config']
                ]"
            />
        </x-slot:breadcrumbs>
    </x-ui.page-header>

    @if(session('toast_success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between text-xs font-sans shadow-xs">
            <div class="flex items-center gap-2.5">
                <x-icon name="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0" />
                <span class="font-medium">{{ session('toast_success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 cursor-pointer">
                <x-icon name="x" class="w-4 h-4" />
            </button>
        </div>
    @endif

    <form method="POST" action="{{ route('national_admin.chatbot_config.update') }}" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Hyperparameters & Prompt (7 Cols) -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Hyperparameters Card -->
                <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-5">
                    <h3 class="font-heading font-bold text-base text-ink-950 flex items-center gap-2">
                        <x-icon name="sliders" class="w-4 h-4 text-brand-600" />
                        Model Hyperparameters
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Inference Model</label>
                            <select 
                                name="model" 
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"
                            >
                                <option value="gemini-1.5-pro" {{ ($config['model'] ?? '') === 'gemini-1.5-pro' ? 'selected' : '' }}>Google Gemini 1.5 Pro</option>
                                <option value="gpt-4o" {{ ($config['model'] ?? '') === 'gpt-4o' ? 'selected' : '' }}>OpenAI GPT-4o</option>
                                <option value="claude-3-5-sonnet" {{ ($config['model'] ?? '') === 'claude-3-5-sonnet' ? 'selected' : '' }}>Anthropic Claude 3.5 Sonnet</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Max Generation Tokens</label>
                            <input 
                                type="number" 
                                name="max_tokens" 
                                value="{{ $config['max_tokens'] ?? 2048 }}"
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Temperature (Factual Precision)</label>
                            <input 
                                type="number" 
                                step="0.05" 
                                min="0" 
                                max="1" 
                                name="temperature" 
                                value="{{ $config['temperature'] ?? 0.20 }}"
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"
                            />
                            <span class="text-[10px] text-ink-400 mt-1 block">Low temperature (0.20) guarantees strict factual grounding.</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-ink-700 mb-1.5 font-sans">Citation Cosine Threshold</label>
                            <input 
                                type="number" 
                                step="0.05" 
                                min="0" 
                                max="1" 
                                name="citation_threshold" 
                                value="{{ $config['citation_threshold'] ?? 0.85 }}"
                                class="w-full text-xs rounded-xl border border-border-default bg-canvas px-3 py-2 text-ink-800 focus:outline-none focus:ring-2 focus:ring-brand-500 font-mono"
                            />
                            <span class="text-[10px] text-ink-400 mt-1 block">Requires minimum 0.85 similarity to cite statutory gazettes.</span>
                        </div>
                    </div>
                </div>

                <!-- Master System Prompt Card -->
                <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-heading font-bold text-base text-ink-950 flex items-center gap-2">
                            <x-icon name="bot" class="w-4 h-4 text-brand-600" />
                            National Regulatory System Prompt
                        </h3>
                        <span class="text-[11px] font-mono text-ink-400">Strict Persona Invariant</span>
                    </div>

                    <textarea 
                        name="system_prompt" 
                        rows="7"
                        class="w-full p-4 text-xs rounded-xl border border-border-default bg-canvas font-mono text-ink-800 focus:bg-surface focus:outline-none focus:ring-2 focus:ring-brand-500 leading-relaxed resize-y"
                    >{{ $config['system_prompt'] ?? '' }}</textarea>
                </div>
            </div>

            <!-- Right Column: Statutory Guardrails & Save (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Guardrails Injection Rules Card -->
                <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                    <h3 class="font-heading font-bold text-base text-ink-950 flex items-center gap-2">
                        <x-icon name="shield-check" class="w-4 h-4 text-emerald-600" />
                        Statutory Guardrail Injection
                    </h3>
                    <p class="text-xs text-ink-500 font-sans">
                        Dynamic prompt-injection layers enforcing mandatory regulatory checks during inference.
                    </p>

                    <div class="space-y-3.5 pt-2">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="inject_pec_rules" 
                                value="1"
                                {{ !empty($config['inject_pec_rules']) ? 'checked' : '' }}
                                class="mt-0.5 rounded border-border-default text-brand-600 focus:ring-brand-500"
                            />
                            <div>
                                <span class="text-xs font-semibold text-ink-950 block">PEC Washington Accord Ruleset</span>
                                <span class="text-[11px] text-ink-500 block leading-relaxed">Prohibits claiming unaccredited programs possess international Washington Accord equivalence.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="inject_hec_premed_rules" 
                                value="1"
                                {{ !empty($config['inject_hec_premed_rules']) ? 'checked' : '' }}
                                class="mt-0.5 rounded border-border-default text-brand-600 focus:ring-brand-500"
                            />
                            <div>
                                <span class="text-xs font-semibold text-ink-950 block">HEC Pre-Med to CS Remedial Policy (SRO 45)</span>
                                <span class="text-[11px] text-ink-500 block leading-relaxed">Enforces 6-credit math deficiency course reminders for FSc Pre-Medical applicants.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="inject_ibcc_rules" 
                                value="1"
                                {{ !empty($config['inject_ibcc_rules']) ? 'checked' : '' }}
                                class="mt-0.5 rounded border-border-default text-brand-600 focus:ring-brand-500"
                            />
                            <div>
                                <span class="text-xs font-semibold text-ink-950 block">IBCC Equivalence Formulas</span>
                                <span class="text-[11px] text-ink-500 block leading-relaxed">Computes official O/A Level marks conversion according to Federal Ministry tables.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="enforce_strict_citations" 
                                value="1"
                                {{ !empty($config['enforce_strict_citations']) ? 'checked' : '' }}
                                class="mt-0.5 rounded border-border-default text-brand-600 focus:ring-brand-500"
                            />
                            <div>
                                <span class="text-xs font-semibold text-ink-950 block">Strict Citation Mandate</span>
                                <span class="text-[11px] text-ink-500 block leading-relaxed">Suppresses any admission advice that lacks direct source links to verified prospectuses.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="guardrails_hallucination_block" 
                                value="1"
                                {{ !empty($config['guardrails_hallucination_block']) ? 'checked' : '' }}
                                class="mt-0.5 rounded border-border-default text-brand-600 focus:ring-brand-500"
                            />
                            <div>
                                <span class="text-xs font-semibold text-ink-950 block">Hallucination Circuit Breaker</span>
                                <span class="text-[11px] text-ink-500 block leading-relaxed">Returns polite refusal if an institution has no verified public gazette on record.</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Save Action Button -->
                <div class="flex justify-end">
                    <x-ui.button type="submit" variant="primary" size="lg" icon="save">
                        Save AI Counselor Configuration
                    </x-ui.button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.national-admin>

