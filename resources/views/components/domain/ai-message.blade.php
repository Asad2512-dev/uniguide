@props([
    'advisorName' => 'UniGuide Admission Advisor',
    'confidence' => '96% High Confidence',
    'timestamp' => 'Just now',
    'citations' => [],
    'prompts' => [],
])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-border-default bg-surface p-5 shadow-subtle']) }}>
    <!-- Header: Identity & Confidence -->
    <div class="flex items-center justify-between pb-3.5 mb-3.5 border-b border-brand-100/70">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-brand-600 text-white flex items-center justify-center shadow-xs">
                <x-icon name="message-circle" class="w-4 h-4" />
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-heading font-bold text-sm text-ink-950">{{ $advisorName }}</span>
                </div>
                <span class="text-xs sm:text-[11px] text-ink-600 font-sans">{{ $timestamp }}</span>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" class="text-ink-400 hover:text-ink-700 p-1 rounded" title="Copy response">
                <x-icon name="copy" class="w-3.5 h-3.5" />
            </button>
        </div>
    </div>

    <!-- Message Content Body -->
    <div class="prose prose-sm text-ink-800 text-sm font-sans leading-relaxed space-y-3">
        {{ $slot }}
    </div>

    <!-- Official Citations -->
    @if(count($citations) > 0)
        <div class="mt-4 pt-3.5 border-t border-border-subtle">
            <div class="text-xs sm:text-[11px] font-semibold text-ink-500 uppercase tracking-wider mb-2 font-sans flex items-center gap-1.5">
                <x-icon name="file-text" class="w-3 h-3 text-brand-600" />
                University sources
            </div>
            <div class="space-y-1.5">
                @foreach($citations as $cite)
                    <x-domain.source-citation 
                        :source="$cite['source'] ?? ''" 
                        :reference="$cite['reference'] ?? ''" 
                        :verifiedDate="$cite['date'] ?? 'Verified'" 
                    />
                @endforeach
            </div>
        </div>
    @endif

    <!-- Suggested Follow-up Actions -->
    @if(count($prompts) > 0)
        <div class="mt-4 pt-3 border-t border-border-subtle flex flex-wrap items-center gap-2">
            <span class="text-xs font-medium text-ink-600">Ask next:</span>
            @foreach($prompts as $prompt)
                <button 
                    type="button" 
                    class="text-xs font-sans text-brand-700 bg-brand-50 hover:bg-brand-100 hover:text-brand-900 px-3 py-1 rounded-md border border-brand-200 transition-smooth cursor-pointer"
                >
                    {{ $prompt }} →
                </button>
            @endforeach
        </div>
    @endif
</div>

