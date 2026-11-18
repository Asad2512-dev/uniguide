@props([
    'title' => 'Superadmin — UniGuide Pakistan',
    'jurisdiction' => 'UniGuide Platform Administration',
])

<x-layouts.base :title="$title">
    <div class="flex h-screen overflow-hidden bg-canvas">
        <!-- Admin Sidebar -->
        <x-nav.sidebar 
            menuKey="national_admin" 
            roleLabel="Superadmin" 
            userName="UniGuide Superadmin" 
            userRole="Platform Administrator" 
            userAvatar="SA" 
        />

        <!-- Mobile Drawer Navigation -->
        <x-nav.mobile-nav menuKey="national_admin" roleLabel="Superadmin" />

        <!-- Main Workspace Shell -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Authority Topbar -->
            <x-nav.topbar 
                contextBadge="UniGuide University Registry" 
                userName="Platform Administrator" 
                userRole="UniGuide Pakistan" 
                avatarText="SA" 
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

