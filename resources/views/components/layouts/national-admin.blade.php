@props([
    'title' => 'Superadmin — UniGuide Pakistan',
    'jurisdiction' => 'UniGuide Platform Administration',
])

<x-layouts.base :title="$title">
    <div class="portal-workspace flex min-h-dvh w-full bg-canvas">
        <!-- National Admin Sidebar -->
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
            <!-- National Authority Topbar -->
            <x-nav.topbar 
                contextBadge="UniGuide University Registry" 
                userName="Platform Administrator" 
                userRole="UniGuide Pakistan" 
                avatarText="SA" 
            />

            <!-- Federal Compliance Banner -->
            <div class="bg-gradient-to-r from-brand-900 via-brand-800 to-indigo-950 text-white px-6 py-2 flex items-center justify-between text-xs font-sans shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-bold tracking-wide">National Registry Node Active</span>
                    <span class="opacity-40">•</span>
                    <span class="text-brand-100 font-mono text-xs sm:text-[11px]">248 Chartered Institutions • 6,420 Programs Monitored</span>
                </div>
                <div class="hidden sm:flex items-center gap-2">
                    <span class="text-xs sm:text-[10px] font-mono uppercase tracking-wider bg-white/10 px-2 py-0.5 rounded">
                        Federal Ordinance 2002
                    </span>
                </div>
            </div>

            <!-- Scrollable Content Canvas -->
            <main id="main-content" class="min-w-0 flex-1 p-4 sm:p-6 lg:p-8">
                <div class="max-w-none mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-layouts.base>
