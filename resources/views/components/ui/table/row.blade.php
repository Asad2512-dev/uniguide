@props([
    'hover' => true,
    'href' => null,
])

<tr {{ $attributes->merge([
    'class' => 'border-b border-border-subtle last:border-b-0 transition-colors ' . 
    ($hover ? 'hover:bg-surface-subtle/80 cursor-pointer ' : '')
]) }}>
    {{ $slot }}
</tr>
