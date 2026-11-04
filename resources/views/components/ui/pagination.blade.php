@props([
    'currentPage' => 1,
    'totalPages' => 1,
    'totalItems' => 0,
    'perPage' => 10,
    'queryParam' => 'page',
])

@php
    $startItem = $totalItems > 0 ? (($currentPage - 1) * $perPage) + 1 : 0;
    $endItem = min($currentPage * $perPage, $totalItems);

    // Build page range with ellipsis
    $delta = 2;
    $left = max(1, $currentPage - $delta);
    $right = min($totalPages, $currentPage + $delta);
    $pages = [];
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == 1 || $i == $totalPages || ($i >= $left && $i <= $right)) {
            $pages[] = $i;
        } elseif (count($pages) > 0 && end($pages) !== '…') {
            $pages[] = '…';
        }
    }
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

    @if($totalPages > 1)
        <nav aria-label="Pagination" class="inline-flex items-center gap-1">
            @if($currentPage <= 1)
                <span class="inline-flex items-center justify-center px-2.5 py-1.5 rounded-lg border border-border-default bg-surface text-ink-400 opacity-50 cursor-not-allowed">
                    <x-icon name="chevron-left" class="w-4 h-4 mr-1" />
                    <span>Previous</span>
                </span>
            @else
                <a 
                    href="{{ request()->fullUrlWithQuery([$queryParam => $currentPage - 1]) }}" 
                    class="inline-flex items-center justify-center px-2.5 py-1.5 rounded-lg border border-border-default bg-surface text-ink-700 hover:bg-surface-subtle transition-smooth"
                    aria-label="Previous page"
                >
                    <x-icon name="chevron-left" class="w-4 h-4 mr-1" />
                    <span>Previous</span>
                </a>
            @endif

            <div class="hidden sm:inline-flex items-center gap-1 px-1">
                @foreach($pages as $page)
                    @if($page === '…')
                        <span class="px-1 text-ink-400">…</span>
                    @elseif($page == $currentPage)
                        <span class="w-8 h-8 rounded-lg bg-brand-600 text-white font-semibold flex items-center justify-center" aria-current="page">
                            {{ $page }}
                        </span>
                    @else
                        <a 
                            href="{{ request()->fullUrlWithQuery([$queryParam => $page]) }}" 
                            class="w-8 h-8 rounded-lg border border-border-default bg-surface text-ink-700 hover:bg-surface-subtle flex items-center justify-center transition-smooth"
                        >
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            </div>

            @if($currentPage >= $totalPages)
                <span class="inline-flex items-center justify-center px-2.5 py-1.5 rounded-lg border border-border-default bg-surface text-ink-400 opacity-50 cursor-not-allowed">
                    <span>Next</span>
                    <x-icon name="chevron-right" class="w-4 h-4 ml-1" />
                </span>
            @else
                <a 
                    href="{{ request()->fullUrlWithQuery([$queryParam => $currentPage + 1]) }}" 
                    class="inline-flex items-center justify-center px-2.5 py-1.5 rounded-lg border border-border-default bg-surface text-ink-700 hover:bg-surface-subtle transition-smooth"
                    aria-label="Next page"
                >
                    <span>Next</span>
                    <x-icon name="chevron-right" class="w-4 h-4 ml-1" />
                </a>
            @endif
        </nav>
    @endif
</div>

