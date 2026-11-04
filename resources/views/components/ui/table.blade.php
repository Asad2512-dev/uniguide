@props([
    'striped' => false,
])

<div class="w-full overflow-hidden rounded-2xl border border-border-default bg-surface shadow-card">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm font-sans text-ink-800">
            {{ $slot }}
        </table>
    </div>
</div>
