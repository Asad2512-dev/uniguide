@props([
    'variant' => 'line', // line, card, avatar, table-row
    'class' => '',
])

@if($variant === 'line')
    <div {{ $attributes->merge(['class' => "h-4 bg-ink-200/60 rounded-md animate-pulse $class"]) }}></div>
@elseif($variant === 'avatar')
    <div {{ $attributes->merge(['class' => "w-10 h-10 bg-ink-200/60 rounded-full animate-pulse $class"]) }}></div>
@elseif($variant === 'card')
    <div {{ $attributes->merge(['class' => "p-6 rounded-2xl border border-border-default bg-surface space-y-4 animate-pulse $class"]) }}>
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-ink-200/60 rounded-xl shrink-0"></div>
            <div class="flex-1 space-y-2">
                <div class="h-4 bg-ink-200/60 rounded w-3/4"></div>
                <div class="h-3 bg-ink-200/60 rounded w-1/2"></div>
            </div>
        </div>
        <div class="h-16 bg-surface-subtle rounded-xl"></div>
        <div class="flex justify-between pt-2">
            <div class="h-4 bg-ink-200/60 rounded w-1/4"></div>
            <div class="h-8 bg-ink-200/60 rounded-lg w-28"></div>
        </div>
    </div>
@elseif($variant === 'table-row')
    <tr class="animate-pulse border-b border-border-subtle">
        <td class="px-5 py-4"><div class="h-4 bg-ink-200/60 rounded w-3/4"></div></td>
        <td class="px-5 py-4"><div class="h-4 bg-ink-200/60 rounded w-1/2"></div></td>
        <td class="px-5 py-4"><div class="h-4 bg-ink-200/60 rounded w-2/3"></div></td>
        <td class="px-5 py-4 text-right"><div class="h-8 bg-ink-200/60 rounded-lg w-20 ml-auto"></div></td>
    </tr>
@endif
