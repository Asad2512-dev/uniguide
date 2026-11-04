@props([
    'source' => 'Official HEC National University Directory 2026',
    'reference' => 'HEC Act No. LIII of 2002 • Gazette No. SRO 45(I)/2026',
    'verifiedDate' => 'Verified Nov 2026',
    'url' => '#',
])

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 p-2 px-3 rounded-lg bg-surface-subtle border border-border-default text-xs font-sans text-ink-700 hover:border-border-strong transition-smooth']) }}>
    <x-icon name="file-text" class="w-3.5 h-3.5 text-brand-600 shrink-0" />
    <div class="flex flex-col sm:flex-row sm:items-center sm:gap-2">
        <span class="font-medium text-ink-900">{{ $source }}</span>
        <span class="hidden sm:inline text-ink-300">•</span>
        <span class="text-ink-500 font-mono text-[11px]">{{ $reference }}</span>
    </div>
    <span class="text-[10px] text-success-700 font-semibold bg-success-50 px-1.5 py-0.2 rounded border border-success-200/60 ml-auto">
        {{ $verifiedDate }}
    </span>
    @if($url)
        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="text-brand-600 hover:text-brand-700" title="Inspect official citation source">
            <x-icon name="external-link" class="w-3 h-3" />
        </a>
    @endif
</div>

