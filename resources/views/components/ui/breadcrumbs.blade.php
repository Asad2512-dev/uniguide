@props([
    'items' => [], // array of ['label' => 'Universities', 'url' => '/universities']
    'homeUrl' => route('home'),
])

<nav aria-label="Breadcrumb" class="flex items-center text-xs font-sans text-ink-500">
    <ol class="flex flex-wrap items-center gap-x-1.5 gap-y-2">
        <li class="inline-flex min-w-0 items-center">
            <a href="{{ $homeUrl }}" class="inline-flex items-center text-ink-500 hover:text-brand-600 transition-colors">
                <x-icon name="building-2" class="w-3.5 h-3.5 mr-1" />
                <span>Portal</span>
            </a>
        </li>

        @foreach($items as $item)
            <li class="inline-flex min-w-0 items-center">
                <x-icon name="chevron-right" class="w-3.5 h-3.5 text-ink-300 mx-1 shrink-0" />
                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="text-ink-500 hover:text-brand-600 transition-colors">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="font-medium text-ink-900 break-words" aria-current="page">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

