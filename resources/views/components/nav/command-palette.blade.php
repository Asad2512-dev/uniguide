<div 
    x-data 
    x-show="$store.commandPalette.open" 
    x-cloak
    @keydown.escape.window="$store.commandPalette.close()"
    class="fixed inset-0 z-50 overflow-y-auto p-4 sm:p-6 md:p-20" 
    role="dialog" 
    aria-modal="true"
>
    <!-- Backdrop Blur -->
    <div 
        x-show="$store.commandPalette.open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-ink-950/40 backdrop-blur-xs transition-opacity" 
        @click="$store.commandPalette.close()"
        aria-hidden="true"
    ></div>

    <!-- Command Palette Dialog -->
    <div class="mx-auto max-w-xl transform rounded-2xl bg-surface border border-border-default shadow-modal overflow-hidden transition-all relative z-10">
        <div class="relative border-b border-border-default">
            <x-icon name="search" class="w-5 h-5 text-ink-400 absolute left-4 top-3.5" />
            <input 
                type="text" 
                placeholder="Search Pakistan universities, degree programs, scholarships, or jump to portals…" 
                class="w-full bg-transparent pl-12 pr-12 py-3.5 text-sm text-ink-900 placeholder:text-ink-400 focus:outline-none font-sans"
                @keydown.escape="$store.commandPalette.close()"
                autofocus
            />
            <button 
                type="button" 
                @click="$store.commandPalette.close()" 
                class="absolute right-3.5 top-3 text-ink-400 hover:text-ink-700 p-1"
                aria-label="Close search"
            >
                <kbd class="text-[10px] font-mono bg-surface-subtle border border-border-default px-1.5 py-0.5 rounded text-ink-500">ESC</kbd>
            </button>
        </div>

        <div class="p-3 max-h-96 overflow-y-auto space-y-4 text-xs font-sans">
            <div>
                <p class="px-2 text-[10px] font-bold text-ink-400 uppercase tracking-wider mb-1">
                    Shell Workspaces & Portals
                </p>
                <div class="space-y-0.5">
                    <a href="{{ route('preview.public') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="compass" class="w-4 h-4 text-brand-600" />
                            <span>Public Discovery & Marketing Shell</span>
                        </span>
                        <span class="text-[10px] font-mono text-ink-400">/preview/public</span>
                    </a>
                    <a href="{{ route('preview.student') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="graduation-cap" class="w-4 h-4 text-brand-600" />
                            <span>Student Dashboard Shell</span>
                        </span>
                        <span class="text-[10px] font-mono text-ink-400">/preview/student</span>
                    </a>
                    <a href="{{ route('preview.university_admin') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="building-2" class="w-4 h-4 text-brand-600" />
                            <span>University Institutional Admin Shell</span>
                        </span>
                        <span class="text-[10px] font-mono text-ink-400">/preview/university-admin</span>
                    </a>
                    <a href="{{ route('preview.national_admin') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="shield-check" class="w-4 h-4 text-brand-600" />
                            <span>HEC National Command Shell</span>
                        </span>
                        <span class="text-[10px] font-mono text-ink-400">/preview/national-admin</span>
                    </a>
                    <a href="{{ route('design-system') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between px-3 py-2 rounded-lg text-brand-700 bg-brand-50/60 font-semibold group">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="sparkles" class="w-4 h-4 text-brand-600" />
                            <span>Design System Living Catalog</span>
                        </span>
                        <span class="text-[10px] font-mono text-brand-600">/design-system</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-2 text-[10px] font-bold text-ink-400 uppercase tracking-wider mb-1">
                    Quick University Searches
                </p>
                <div class="space-y-0.5">
                    <a href="{{ route('design-system') }}#domain-cards" @click="$store.commandPalette.close()" class="flex items-center justify-between px-3 py-2 rounded-lg text-ink-700 hover:bg-surface-subtle">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="building" class="w-4 h-4 text-ink-400" />
                            <span>National University of Sciences & Technology (NUST)</span>
                        </span>
                        <span class="text-[10px] bg-brand-50 text-brand-700 px-1.5 py-0.2 rounded font-mono font-semibold">HEC #1</span>
                    </a>
                    <a href="{{ route('design-system') }}#domain-cards" @click="$store.commandPalette.close()" class="flex items-center justify-between px-3 py-2 rounded-lg text-ink-700 hover:bg-surface-subtle">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="building" class="w-4 h-4 text-ink-400" />
                            <span>Lahore University of Management Sciences (LUMS)</span>
                        </span>
                        <span class="text-[10px] bg-surface-subtle text-ink-600 px-1.5 py-0.2 rounded font-mono">Lahore</span>
                    </a>
                    <a href="{{ route('design-system') }}#domain-cards" @click="$store.commandPalette.close()" class="flex items-center justify-between px-3 py-2 rounded-lg text-ink-700 hover:bg-surface-subtle">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="building" class="w-4 h-4 text-ink-400" />
                            <span>FAST-NUCES (National University of Computer & Emerging Sciences)</span>
                        </span>
                        <span class="text-[10px] bg-surface-subtle text-ink-600 px-1.5 py-0.2 rounded font-mono">Islamabad</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="px-4 py-2.5 bg-surface-subtle border-t border-border-default flex items-center justify-between text-[11px] text-ink-400 font-sans">
            <div class="flex items-center gap-2">
                <span>Navigate <kbd class="font-mono bg-surface border px-1 rounded">↑</kbd><kbd class="font-mono bg-surface border px-1 rounded">↓</kbd></span>
                <span>Select <kbd class="font-mono bg-surface border px-1 rounded">↵</kbd></span>
            </div>
            <span>Press <kbd class="font-mono bg-surface border px-1 rounded">ESC</kbd> to dismiss</span>
        </div>
    </div>
</div>

