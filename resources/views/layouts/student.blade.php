@props([
    'title' => 'Student Portal — UniGuide Pakistan',
    'description' => null,
])

<x-layouts.base :title="$title" :description="$description">
    <div class="flex h-screen overflow-hidden bg-canvas">
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
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Dashboard Topbar -->
            <x-nav.topbar 
                contextBadge="Student workspace"
                :userName="auth()->user()?->name ?? 'Student'"
                :userRole="auth()->user()?->profile?->applicant_id ?? 'Student'"
                :avatarText="mb_strtoupper(mb_substr(auth()->user()?->name ?? 'S', 0, 2))"
            />

            <!-- Scrollable Content Canvas -->
            <main id="main-content" class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <div class="max-w-none mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-layouts.base>

