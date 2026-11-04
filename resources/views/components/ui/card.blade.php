@props([
    'hover' => false,
    'padding' => 'p-6',
    'class' => '',
])

<div {{ $attributes->merge([
    'class' => "bg-surface rounded-2xl border border-border-default shadow-card transition-smooth overflow-hidden " . 
    ($hover ? "hover:border-border-strong hover:shadow-elevated hover:-translate-y-0.5 " : "") . 
    $class
]) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-border-subtle bg-surface-subtle/50">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-3.5 border-t border-border-subtle bg-surface-subtle/30">
            {{ $footer }}
        </div>
    @endif
</div>

