@props([
    'title' => 'Institutional Console — UniGuide Pakistan',
    'institutionName' => 'National University of Sciences & Technology (NUST)',
    'institutionCode' => 'INST-PK-019',
])

<x-layouts.base :title="$title">
    <div class="flex h-screen overflow-hidden bg-canvas">
        <!-- University Admin Sidebar -->
        <x-nav.sidebar 
            menuKey="university_admin" 
            roleLabel="Institutional Console" 
            userName="Dr. Tariq Mahmood" 
            userRole="Director of Admissions • NUST" 
            userAvatar="TM" 
        />

        <!-- Mobile Drawer Navigation -->
        <x-nav.mobile-nav menuKey="university_admin" roleLabel="University Admin" />

        <!-- Main Workspace Shell -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Institutional Authority Topbar -->
            <x-nav.topbar 
                contextBadge="{{ $institutionName }}" 
                userName="Dr. Tariq Mahmood" 
                userRole="Registrar Office • Verified" 
                avatarText="TM" 
            />

            <!-- Institutional Context Strip -->
            <div class="bg-surface-subtle border-b border-border-default px-6 py-2 flex items-center justify-between text-xs font-sans text-ink-600">
                <div class="flex items-center gap-3">
                    <span class="font-bold text-ink-900 font-heading">{{ $institutionName }}</span>
                    <span class="text-ink-300">•</span>
                    <span class="font-mono text-ink-500">{{ $institutionCode }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <x-domain.verification-badge type="hec_recognized" detail="Charter Verified" size="sm" />
                </div>
            </div>

            <!-- Scrollable Content Canvas -->
            <main id="main-content" class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <div class="max-w-none mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
</x-layouts.base>

