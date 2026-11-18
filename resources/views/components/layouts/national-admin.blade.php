@props([
    'title' => 'Superadmin — UniGuide Pakistan',
    'jurisdiction' => 'UniGuide Platform Administration',
])

<x-layouts.base :title="$title">
    <div class="portal-workspace flex min-h-dvh w-full bg-canvas">
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
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Authority Topbar -->
            <x-nav.topbar 
                contextBadge="UniGuide University Registry" 
                userName="Platform Administrator" 
                userRole="UniGuide Pakistan" 
                avatarText="SA" 
            />

            <!-- Scrollable Content Canvas -->
            <main id="main-content" class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-none mx-auto">
                    @if($errors->any())<div role="alert" class="mb-5 rounded-lg border border-error-200 p-4 text-sm text-error-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-layouts.base>
