@props([
    'currentPage' => 1,
    'totalPages' => 25,
    'totalItems' => 248,
    'perPage' => 10,
])

@php
    $startItem = (($currentPage - 1) * $perPage) + 1;
    $endItem = min($currentPage * $perPage, $totalItems);
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 py-4 px-2 text-xs font-sans text-ink-600">
    <div class="flex items-center gap-1.5">
        <span>Showing</span>
        <span class="font-semibold text-ink-900">{{ $startItem }}</span>
        <span>to</span>
        <span class="font-semibold text-ink-900">{{ $endItem }}</span>
        <span>of</span>
        <span class="font-semibold text-ink-900">{{ $totalItems }}</span>
        <span>results</span>
    </div>

    <nav aria-label="Pagination" class="inline-flex items-center gap-1">
        <button 
            type="button" 
            @if($currentPage <= 1) disabled @endif
            class="inline-flex items-center justify-center px-2.5 py-1.5 rounded-lg border border-border-default bg-surface text-ink-700 hover:bg-surface-subtle disabled:opacity-40 disabled:cursor-not-allowed transition-smooth"
            aria-label="Previous page"
        >
            <x-icon name="chevron-left" class="w-4 h-4 mr-1" />
            <span>Previous</span>
        </button>

        <div class="hidden sm:inline-flex items-center gap-1 px-1">
            <button type="button" class="w-8 h-8 rounded-lg bg-brand-600 text-white font-semibold flex items-center justify-center">
                1
            </button>
            <button type="button" class="w-8 h-8 rounded-lg border border-border-default bg-surface text-ink-700 hover:bg-surface-subtle flex items-center justify-center transition-smooth">
                2
            </button>
            <button type="button" class="w-8 h-8 rounded-lg border border-border-default bg-surface text-ink-700 hover:bg-surface-subtle flex items-center justify-center transition-smooth">
                3
            </button>
            <span class="px-1 text-ink-400">…</span>
            <button type="button" class="w-8 h-8 rounded-lg border border-border-default bg-surface text-ink-700 hover:bg-surface-subtle flex items-center justify-center transition-smooth">
                25
            </button>
        </div>

        <button 
            type="button" 
            @if($currentPage >= $totalPages) disabled @endif
            class="inline-flex items-center justify-center px-2.5 py-1.5 rounded-lg border border-border-default bg-surface text-ink-700 hover:bg-surface-subtle disabled:opacity-40 disabled:cursor-not-allowed transition-smooth"
            aria-label="Next page"
        >
            <span>Next</span>
            <x-icon name="chevron-right" class="w-4 h-4 ml-1" />
        </button>
    </nav>
</div>

