@props([
    'type' => 'hec_recognized', // hec_recognized, chartered, gazette_verified, council_accredited
    'detail' => null,
    'verifiedAt' => null,
    'size' => 'sm',
])

@php
    $data = match($type) {
        'hec_recognized' => [
            'label' => 'HEC Recognized',
            'sub' => $detail ?? 'Category W (Highest)',
            'icon' => 'shield-check',
            'color' => 'text-success-700 bg-success-50 border-success-200/80',
            'iconColor' => 'text-success-600',
        ],
        'chartered' => [
            'label' => 'Chartered Institution',
            'sub' => $detail ?? 'Govt. of Pakistan Act',
            'icon' => 'award',
            'color' => 'text-brand-800 bg-brand-50 border-brand-200/80',
            'iconColor' => 'text-brand-600',
        ],
        'council_accredited' => [
            'label' => 'PEC / PMDC / NBEAC Accredited',
            'sub' => $detail ?? 'Level-II Full Accreditation',
            'icon' => 'check-circle-2',
            'color' => 'text-mint-700 bg-mint-50 border-mint-200/80',
            'iconColor' => 'text-mint-600',
        ],
        default => [
            'label' => 'Official Gazette Verified',
            'sub' => $detail ?? 'Federal Register Entry',
            'icon' => 'file-badge-2',
            'color' => 'text-ink-800 bg-surface-subtle border-border-default',
            'iconColor' => 'text-ink-600',
        ],
    };
@endphp

<div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md border text-xs font-medium font-sans {{ $data['color'] }} transition-smooth">
    <x-icon :name="$data['icon']" class="w-3.5 h-3.5 shrink-0 {{ $data['iconColor'] }}" />
    <span class="font-semibold">{{ $data['label'] }}</span>
    @if($data['sub'])
        <span class="opacity-60">•</span>
        <span class="opacity-80 font-normal">{{ $data['sub'] }}</span>
    @endif
    @if($verifiedAt)
        <span class="text-[10px] text-ink-500 font-mono ml-1" title="Data verification freshness">
            [{{ $verifiedAt }}]
        </span>
    @endif
</div>

