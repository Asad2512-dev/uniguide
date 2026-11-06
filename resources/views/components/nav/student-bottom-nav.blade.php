@php
    $destinations = [
        ['label' => 'Home', 'route' => 'student.dashboard', 'active' => 'student.dashboard', 'icon' => 'layout-dashboard'],
        ['label' => 'Explore', 'route' => 'universities.index', 'active' => 'universities.*', 'icon' => 'compass'],
        ['label' => 'AI Counselor', 'route' => 'counselor.chat', 'active' => 'counselor.*', 'icon' => 'sparkles'],
        ['label' => 'Saved', 'route' => 'student.saved.universities', 'active' => 'student.saved.*', 'icon' => 'bookmark'],
        ['label' => 'Profile', 'route' => 'student.profile', 'active' => 'student.profile*', 'icon' => 'user'],
    ];
@endphp

<nav aria-label="Student mobile navigation" class="student-bottom-nav md:hidden grid shrink-0 grid-cols-5 border-t border-border-default bg-surface px-2 pt-2 safe-area-bottom">
    @foreach($destinations as $destination)
        @php $active = request()->routeIs($destination['active']); @endphp
        <a href="{{ route($destination['route']) }}" @if($active) aria-current="page" @endif
            class="flex min-h-14 min-w-0 flex-col items-center justify-center gap-1 rounded-xl px-1 pb-2 text-xs sm:text-[11px] font-semibold {{ $active ? 'text-brand-700 bg-brand-50' : 'text-ink-600 hover:bg-surface-subtle' }}">
            <span class="flex h-7 w-9 items-center justify-center rounded-lg {{ $destination['route'] === 'counselor.chat' ? 'bg-brand-600 text-white shadow-xs' : '' }}"><x-icon :name="$destination['icon']" class="size-5" /></span>
            <span class="text-center leading-tight">{{ $destination['label'] }}</span>
        </a>
    @endforeach
</nav>
