@props([
    'items' => [], // array of ['label' => 'Universities', 'url' => '/universities']
    'homeUrl' => route('preview.public'),
])

<nav aria-label="Breadcrumb" class="flex items-center text-xs font-sans text-ink-500">
    <ol class="inline-flex items-center space-x-1.5 md:space-x-2">
        <li class="inline-flex items-center">
            <a href="{{ $homeUrl }}" class="inline-flex items-center text-ink-500 hover:text-brand-600 transition-colors">
                <x-icon name="building-2" class="w-3.5 h-3.5 mr-1" />
                <span>Portal</span>
            </a>
        </li>

        @foreach($items as $item)
            <li class="inline-flex items-center">
                <x-icon name="chevron-right" class="w-3.5 h-3.5 text-ink-300 mx-1 shrink-0" />
                @if(isset($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" class="text-ink-500 hover:text-brand-600 transition-colors">
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="font-medium text-ink-900" aria-current="page">
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

