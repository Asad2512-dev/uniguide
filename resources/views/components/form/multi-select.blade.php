@props([
    'label' => 'Filter',
    'options' => [], // array of ['id' => '1', 'name' => 'Islamabad', 'count' => 45]
    'selected' => [],
    'placeholder' => 'Search options...',
])

<div 
    x-data="{
        open: false,
        search: '',
        selected: {{ json_encode($selected) }},
        options: {{ json_encode($options) }},
        toggle(id) {
            if (this.selected.includes(id)) {
                this.selected = this.selected.filter(item => item !== id);
            } else {
                this.selected.push(id);
            }
        },
        clear() {
            this.selected = [];
        },
        get filteredOptions() {
            if (!this.search) return this.options;
            return this.options.filter(opt => opt.name.toLowerCase().includes(this.search.toLowerCase()));
        }
    }" 
    @click.outside="open = false"
    class="relative inline-block text-left"
>
    <button 
        type="button" 
        @click="open = !open" 
        :aria-expanded="open"
        class="inline-flex items-center justify-between gap-2 px-3 py-1.5 rounded-lg border border-border-default bg-surface text-xs font-medium text-ink-800 hover:border-border-strong hover:bg-surface-subtle focus:outline-none focus:ring-2 focus:ring-brand-500/20 transition-smooth shadow-2xs cursor-pointer"
    >
        <span class="flex items-center gap-1.5">
            <span>{{ $label }}</span>
            <span 
                x-show="selected.length > 0" 
                x-text="selected.length" 
                class="px-1.5 py-0.2 bg-brand-600 text-white font-bold rounded-full text-xs sm:text-[10px]"
            ></span>
        </span>
        <x-icon name="chevron-down" class="w-3.5 h-3.5 text-ink-400 transition-transform duration-200" ::class="open ? 'rotate-180' : ''" />
    </button>

    <div 
        x-show="open" 
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute z-40 mt-1.5 w-64 rounded-xl border border-border-default bg-surface p-2 shadow-dropdown focus:outline-none"
    >
        <div class="relative mb-2">
            <x-icon name="search" class="w-3.5 h-3.5 text-ink-400 absolute left-2.5 top-2.5" />
            <input 
                type="text" 
                x-model="search" 
                placeholder="{{ $placeholder }}" 
                class="w-full rounded-md border border-border-default bg-surface-subtle pl-8 pr-2.5 py-1 text-xs text-ink-900 focus:outline-none focus:border-brand-500 focus:bg-surface"
            />
        </div>

        <div class="max-h-52 overflow-y-auto space-y-1 pr-1">
            <template x-for="opt in filteredOptions" :key="opt.id">
                <label 
                    class="flex items-center justify-between px-2 py-1.5 rounded-lg text-xs hover:bg-surface-subtle cursor-pointer transition-colors"
                >
                    <div class="flex items-center gap-2">
                        <input 
                            type="checkbox" 
                            :checked="selected.includes(opt.id)"
                            @change="toggle(opt.id)"
                            class="w-3.5 h-3.5 rounded border-border-default text-brand-600 focus:ring-brand-500/20"
                        />
                        <span class="text-ink-800" x-text="opt.name"></span>
                    </div>
                    <span x-show="opt.count" class="text-xs sm:text-[10px] text-ink-600 font-mono" x-text="opt.count"></span>
                </label>
            </template>
        </div>

        <div class="mt-2 pt-2 border-t border-border-subtle flex items-center justify-between text-xs sm:text-[11px]">
            <button 
                type="button" 
                @click="clear()" 
                class="text-ink-500 hover:text-ink-800 font-medium cursor-pointer"
            >
                Clear all
            </button>
            <button 
                type="button" 
                @click="open = false" 
                class="text-brand-600 hover:text-brand-700 font-semibold cursor-pointer"
            >
                Apply
            </button>
        </div>
    </div>
</div>

