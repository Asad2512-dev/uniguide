@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'align' => 'left', // left, center
])

@php
    $alignmentClass = match($align) {
        'center' => 'text-center mx-auto items-center',
        default => 'text-left items-start',
    };
@endphp

<div {{ $attributes->merge(['class' => "flex flex-col $alignmentClass max-w-3xl mb-8"]) }}>
    @if($eyebrow)
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold tracking-wider uppercase text-brand-700 bg-brand-50 border border-brand-200/60 mb-2.5 font-sans">
            {{ $eyebrow }}
        </div>
    @endif

    <h2 class="text-xl md:text-2xl lg:text-3xl font-heading font-bold text-ink-950 tracking-tight leading-tight">
        {{ $title }}
    </h2>

    @if($description)
        <p class="mt-2 text-sm md:text-base text-ink-600 font-sans leading-relaxed">
            {{ $description }}
        </p>
    @endif

    @if(isset($action))
        <div class="mt-4">
            {{ $action }}
        </div>
    @endif
</div>
