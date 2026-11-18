@props(['title' => 'UniGuide Admission Counselor', 'description' => null, 'workspace' => false])
@if(auth()->user()?->isStudent())
    <x-layouts.student :title="$title" :description="$description" :workspace="$workspace">
        {{ $slot }}
    </x-layouts.student>
@else
    <x-layouts.base :title="$title" :description="$description ?? 'A focused workspace for researching universities, campuses and source evidence.'">
        {{ $slot }}
    </x-layouts.base>
@endif
