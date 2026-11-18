@props([
    'title' => 'Student Portal — UniGuide Pakistan',
    'description' => null,
    'workspace' => false,
])

<x-layouts.base :title="$title" :description="$description">
    <div class="portal-workspace flex min-h-dvh w-full bg-canvas">
        <!-- Persistent Collapsible Sidebar -->
        <x-nav.sidebar 
            menuKey="student" 
            roleLabel="Student Portal" 
            :userName="auth()->user()?->name ?? 'Student'"
            :userRole="auth()->user()?->profile?->stream_label ?? 'Student'"
            :userAvatar="mb_strtoupper(mb_substr(auth()->user()?->name ?? 'S', 0, 2))"
        />

        <!-- Mobile Drawer Navigation -->
        <x-nav.mobile-nav menuKey="student" roleLabel="Student Portal" />

        <!-- Main Workspace Shell -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Dashboard Topbar -->
            <x-nav.topbar 
                contextBadge="Student workspace"
                :userName="auth()->user()?->name ?? 'Student'"
                :userRole="auth()->user()?->profile?->applicant_id ?? 'Student'"
                :avatarText="mb_strtoupper(mb_substr(auth()->user()?->name ?? 'S', 0, 2))"
            />

            <!-- Scrollable Content Canvas -->
            <main id="main-content" class="min-w-0 flex-1 {{ $workspace ? 'min-h-0 flex flex-col' : 'p-4 sm:p-6 lg:p-8' }}">
                <div class="{{ $workspace ? 'min-h-0 w-full flex-1' : 'max-w-none mx-auto' }}">
                    @if($errors->any())<div role="alert" class="mb-5 rounded-lg border border-error-200 p-4 text-sm text-error-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                    {{ $slot }}
                </div>
            </main>
            <x-nav.student-bottom-nav />
        </div>
    </div>
</x-layouts.base>
