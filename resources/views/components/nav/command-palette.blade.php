<div 
    x-data 
    x-modal-focus="$store.commandPalette.open"
    aria-label="Search UniGuide"
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
        <form action="{{ route('search.index') }}" method="GET" class="relative border-b border-border-default">
            <x-icon name="search" class="w-5 h-5 text-ink-400 absolute left-4 top-3.5" />
            <input 
                type="text" 
                name="q"
                placeholder="Search Pakistan universities, degree programs, scholarships, or press Enter…" 
                class="w-full bg-transparent pl-12 pr-12 py-3.5 text-sm text-ink-900 placeholder:text-ink-400 focus:outline-none font-sans"
                @keydown.escape="$store.commandPalette.close()"
                aria-label="Search universities, programs, and scholarships"
            />
            <button 
                type="button" 
                @click="$store.commandPalette.close()" 
                class="absolute right-3.5 top-3 text-ink-400 hover:text-ink-700 p-1"
                aria-label="Close search"
            >
                <kbd class="text-xs sm:text-[10px] font-mono bg-surface-subtle border border-border-default px-1.5 py-0.5 rounded text-ink-500">ESC</kbd>
            </button>
        </form>

        <div class="p-3 max-h-[55dvh] overflow-y-auto space-y-4 text-xs font-sans">
            <div>
                <p class="px-2 text-xs sm:text-[10px] font-bold text-ink-600 uppercase tracking-wider mb-1">
                    Shell Workspaces & Portals
                </p>
                <div class="space-y-0.5">
                    <a href="{{ route('universities.index') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="building-2" class="w-4 h-4 text-brand-600" />
                            <span>University Directory</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-ink-600">/universities</span>
                    </a>
                    <a href="{{ route('programs.index') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="graduation-cap" class="w-4 h-4 text-brand-600" />
                            <span>Academic Degree Program Explorer</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-ink-600">/programs</span>
                    </a>
                    <a href="{{ route('counselor.index') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="bot" class="w-4 h-4 text-brand-600" />
                            <span>AI University Counselor</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-ink-600">/counselor</span>
                    </a>
                    <a href="{{ route('compare.index') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="git-compare" class="w-4 h-4 text-brand-600" />
                            <span>University Comparison Tool</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-ink-600">/compare</span>
                    </a>
                    <a href="{{ route('eligibility.index') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="calculator" class="w-4 h-4 text-brand-600" />
                            <span>Merit & Eligibility Checker</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-ink-600">/eligibility</span>
                    </a>
                    <a href="{{ route('admissions.index') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="calendar" class="w-4 h-4 text-brand-600" />
                            <span>Admissions & Deadlines Calendar</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-ink-600">/admissions</span>
                    </a>
                    <a href="{{ route('scholarships.index') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="award" class="w-4 h-4 text-brand-600" />
                            <span>National Scholarships Directory</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-ink-600">/scholarships</span>
                    </a>
                    <a href="{{ route('student.dashboard') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="user" class="w-4 h-4 text-brand-600" />
                            <span>Student Command Center</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-ink-600">/student/dashboard</span>
                    </a>
                    <a href="{{ route('uni_admin.dashboard') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="building-2" class="w-4 h-4 text-brand-600" />
                            <span>University Admin Console</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-ink-600">/admin/university</span>
                    </a>
                    <a href="{{ route('national_admin.dashboard') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-800 hover:bg-surface-subtle group">
                        <span class="flex items-center gap-2.5 font-medium">
                            <x-icon name="shield-check" class="w-4 h-4 text-brand-600" />
                            <span>Superadmin Platform</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-ink-600">/admin/national</span>
                    </a>
                    <a href="{{ route('design-system') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-brand-700 bg-brand-50/60 font-semibold group">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="sparkles" class="w-4 h-4 text-brand-600" />
                            <span>Design System Living Catalog</span>
                        </span>
                        <span class="hidden sm:inline text-xs sm:text-[10px] font-mono text-brand-600">/design-system</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-2 text-xs sm:text-[10px] font-bold text-ink-600 uppercase tracking-wider mb-1">
                    Quick University Searches
                </p>
                <div class="space-y-0.5">
                    <a href="{{ route('universities.show', 'nust') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-700 hover:bg-surface-subtle">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="building" class="w-4 h-4 text-ink-400" />
                            <span>National University of Sciences & Technology (NUST)</span>
                        </span>
                        <span class="text-xs sm:text-[10px] bg-brand-50 text-brand-700 px-1.5 py-0.2 rounded font-mono font-semibold">University</span>
                    </a>
                    <a href="{{ route('universities.show', 'lums') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-700 hover:bg-surface-subtle">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="building" class="w-4 h-4 text-ink-400" />
                            <span>Lahore University of Management Sciences (LUMS)</span>
                        </span>
                        <span class="text-xs sm:text-[10px] bg-surface-subtle text-ink-600 px-1.5 py-0.2 rounded font-mono">Lahore</span>
                    </a>
                    <a href="{{ route('universities.show', 'fast-nuces') }}" @click="$store.commandPalette.close()" class="flex items-center justify-between gap-3 px-3 py-2 rounded-lg text-ink-700 hover:bg-surface-subtle">
                        <span class="flex items-center gap-2.5">
                            <x-icon name="building" class="w-4 h-4 text-ink-400" />
                            <span>FAST-NUCES (National University of Computer & Emerging Sciences)</span>
                        </span>
                        <span class="text-xs sm:text-[10px] bg-surface-subtle text-ink-600 px-1.5 py-0.2 rounded font-mono">Islamabad</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="px-4 py-2.5 bg-surface-subtle border-t border-border-default flex items-center justify-between text-xs sm:text-[11px] text-ink-400 font-sans">
            <div class="flex items-center gap-2">
                <span>Navigate with Tab</span>
                <span>Select <kbd class="font-mono bg-surface border px-1 rounded">↵</kbd></span>
            </div>
            <span>Press <kbd class="font-mono bg-surface border px-1 rounded">ESC</kbd> to dismiss</span>
        </div>
    </div>
</div>

