@props([
    'title' => 'Student Portal — UniGuide Pakistan',
    'description' => null,
])

<x-layouts.base :title="$title" :description="$description">
    <div class="portal-workspace flex min-h-dvh w-full bg-canvas">
        <!-- Persistent Collapsible Sidebar -->
        <x-nav.sidebar 
            menuKey="student" 
            roleLabel="Student Portal" 
            userName="Ahmad Raza Khan" 
            userRole="FSc Pre-Engineering • Roll #8841" 
            userAvatar="AR" 
        />

        <!-- Mobile Drawer Navigation -->
        <x-nav.mobile-nav menuKey="student" roleLabel="Student Portal" />

        <!-- Main Workspace Shell -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Dashboard Topbar -->
            <x-nav.topbar 
                contextBadge="Fall 2026 Admission Cycle Active" 
                userName="Ahmad Raza Khan" 
                userRole="Applicant #UG-2026-8841" 
                avatarText="AR" 
            />

            <!-- Scrollable Content Canvas -->
            <main id="main-content" class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-none mx-auto">
                    {{ $slot }}
                </div>
            </main>
            <x-nav.student-bottom-nav />
        </div>
    </div>
</x-layouts.base>
