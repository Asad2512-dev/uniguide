<x-layouts.base 
    title="Candidate Onboarding — UniGuide Pakistan"
    description="Setup your personalized academic profile and admission criteria for Pakistani universities."
>
    <div 
        x-data="{
            step: 1,
            totalSteps: 5,
            stream: '{{ $profile['stream'] ?? 'pre_engineering' }}',
            matricPercentage: {{ $profile['matric']['percentage'] ?? 85.0 }},
            fscPercentage: {{ $profile['fsc']['percentage'] ?? 80.0 }},
            targetDisciplines: ['Computer Science & AI', 'Software Engineering'],
            targetCities: ['Islamabad / Rawalpindi', 'Lahore'],
            budgetMax: 200000,
            scholarshipInterest: 'need_and_merit',

            toggleDiscipline(item) {
                if (this.targetDisciplines.includes(item)) {
                    if (this.targetDisciplines.length > 1) {
                        this.targetDisciplines = this.targetDisciplines.filter(d => d !== item);
                    }
                } else {
                    this.targetDisciplines.push(item);
                }
            },

            toggleCity(item) {
                if (this.targetCities.includes(item)) {
                    if (this.targetCities.length > 1) {
                        this.targetCities = this.targetCities.filter(c => c !== item);
                    }
                } else {
                    this.targetCities.push(item);
                }
            },

            nextStep() {
                if (this.step < this.totalSteps) {
                    this.step++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },
            prevStep() {
                if (this.step > 1) {
                    this.step--;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        }" 
        class="min-h-screen bg-canvas flex flex-col justify-between"
    >
        <!-- Onboarding Header -->
        <header class="border-b border-border-default/80 bg-surface/90 backdrop-blur-xs py-4 px-4 sm:px-6">
            <div class="max-w-4xl mx-auto flex items-center justify-between">
                <x-brand.wordmark size="md" :href="route('home')" />
                <div class="flex items-center gap-3">
                    <span class="text-xs font-mono font-bold text-ink-500">Step <span x-text="step"></span> of <span x-text="totalSteps"></span></span>
                    <a href="{{ route('student.dashboard') }}" class="text-xs text-ink-400 hover:text-ink-700 font-sans">Skip to Dashboard</a>
                </div>
            </div>
        </header>

        <!-- Main Onboarding Body -->
        <main class="max-w-2xl mx-auto w-full px-4 py-8 sm:py-12 flex-1">
            
            <!-- Progress Bar -->
            <div class="w-full bg-surface-subtle h-1.5 rounded-full overflow-hidden mb-8 border border-border-default">
                <div 
                    class="bg-brand-600 h-full rounded-full transition-all duration-300"
                    :style="'width: ' + ((step / totalSteps) * 100) + '%'"
                ></div>
            </div>

            <form action="{{ route('student.onboarding.submit') }}" method="POST">
                @csrf

                <!-- STEP 1: Academic Qualification & Stream -->
                <div x-show="step === 1" x-transition class="space-y-6">
                    <div>
                        <span class="text-xs font-mono font-bold text-brand-600 uppercase tracking-wider">Step 1 of 5</span>
                        <h1 class="font-heading font-bold text-2xl text-ink-950 mt-1">Select Your Academic Stream</h1>
                        <p class="text-xs sm:text-sm text-ink-500 font-sans mt-1">
                            Choose your higher secondary qualification stream to calibrate entry test eligibility.
                        </p>
                    </div>

                    <div class="space-y-3">
                        @foreach($streams as $st)
                            <label 
                                class="flex items-start gap-3.5 p-4 rounded-xl border border-border-default cursor-pointer hover:border-brand-300 hover:bg-surface-subtle transition-all"
                                :class="stream === '{{ $st['id'] }}' ? 'border-brand-600 ring-2 ring-brand-500/10 bg-brand-50/20' : ''"
                            >
                                <input 
                                    type="radio" 
                                    name="stream" 
                                    value="{{ $st['id'] }}" 
                                    x-model="stream" 
                                    class="mt-1 text-brand-600 focus:ring-brand-500" 
                                />
                                <div>
                                    <div class="flex items-center gap-2">
                                        <x-icon :name="$st['icon']" class="w-4 h-4 text-brand-600" />
                                        <span class="text-sm font-bold text-ink-950">{{ $st['name'] }}</span>
                                    </div>
                                    <p class="text-xs text-ink-500 font-sans mt-0.5">{{ $st['desc'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- STEP 2: Marks & Board Normalization -->
                <div x-show="step === 2" x-transition class="space-y-6">
                    <div>
                        <span class="text-xs font-mono font-bold text-brand-600 uppercase tracking-wider">Step 2 of 5</span>
                        <h1 class="font-heading font-bold text-2xl text-ink-950 mt-1">Your Academic Marks & Percentages</h1>
                        <p class="text-xs sm:text-sm text-ink-500 font-sans mt-1">
                            Pakistani universities calculate aggregates using both Matric (SSC) and FSc (HSSC) Part-1 marks.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-surface border border-border-default space-y-6 shadow-card">
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-ink-800 font-sans">
                                Matriculation / O-Level Percentage (%)
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    name="matric_percentage" 
                                    x-model="matricPercentage"
                                    min="50" 
                                    max="100" 
                                    step="0.1" 
                                    required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                                />
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-ink-400 font-mono">%</span>
                            </div>
                            <span class="text-[11px] text-ink-400 font-sans">If O-Level, enter your verified IBCC equivalent percentage.</span>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-ink-800 font-sans">
                                FSc Part-1 / HSSC / A-Level Percentage (%)
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    name="fsc_percentage" 
                                    x-model="fscPercentage"
                                    min="50" 
                                    max="100" 
                                    step="0.1" 
                                    required
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                                />
                                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-ink-400 font-mono">%</span>
                            </div>
                            <span class="text-[11px] text-ink-400 font-sans">Used for preliminary merit rankings during Summer cycles.</span>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Preferred Degrees & Disciplines -->
                <div x-show="step === 3" x-transition class="space-y-6">
                    <div>
                        <span class="text-xs font-mono font-bold text-brand-600 uppercase tracking-wider">Step 3 of 5</span>
                        <h1 class="font-heading font-bold text-2xl text-ink-950 mt-1">Preferred Degree Programs</h1>
                        <p class="text-xs sm:text-sm text-ink-500 font-sans mt-1">
                            Select programs you are keen on pursuing. You can select multiple fields.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($disciplines as $disc)
                            <button 
                                type="button" 
                                @click="toggleDiscipline('{{ $disc }}')"
                                class="p-3.5 rounded-xl border text-left transition-all cursor-pointer text-xs font-sans"
                                :class="targetDisciplines.includes('{{ $disc }}') ? 'border-brand-600 bg-brand-50/30 text-brand-900 font-semibold ring-2 ring-brand-500/10' : 'border-border-default bg-surface text-ink-700 hover:bg-surface-subtle'"
                            >
                                <div class="flex items-center justify-between">
                                    <span>{{ $disc }}</span>
                                    <template x-if="targetDisciplines.includes('{{ $disc }}')">
                                        <x-icon name="check" class="w-4 h-4 text-brand-600" />
                                    </template>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <!-- Hidden Inputs for Form Submission -->
                    <template x-for="item in targetDisciplines" :key="item">
                        <input type="hidden" name="target_disciplines[]" :value="item" />
                    </template>
                </div>

                <!-- STEP 4: Preferred Geographic Cities -->
                <div x-show="step === 4" x-transition class="space-y-6">
                    <div>
                        <span class="text-xs font-mono font-bold text-brand-600 uppercase tracking-wider">Step 4 of 5</span>
                        <h1 class="font-heading font-bold text-2xl text-ink-950 mt-1">Target University Locations</h1>
                        <p class="text-xs sm:text-sm text-ink-500 font-sans mt-1">
                            Choose metropolitan clusters where you are open to studying or taking university hostels.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($cities as $city)
                            <button 
                                type="button" 
                                @click="toggleCity('{{ $city }}')"
                                class="p-3 rounded-xl border text-left transition-all cursor-pointer text-xs font-sans"
                                :class="targetCities.includes('{{ $city }}') ? 'border-brand-600 bg-brand-50/30 text-brand-900 font-semibold ring-2 ring-brand-500/10' : 'border-border-default bg-surface text-ink-700 hover:bg-surface-subtle'"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="truncate">{{ $city }}</span>
                                    <template x-if="targetCities.includes('{{ $city }}')">
                                        <x-icon name="check" class="w-3.5 h-3.5 text-brand-600" />
                                    </template>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <!-- Hidden Inputs for Form Submission -->
                    <template x-for="item in targetCities" :key="item">
                        <input type="hidden" name="target_cities[]" :value="item" />
                    </template>
                </div>

                <!-- STEP 5: Tuition Budget & Scholarships -->
                <div x-show="step === 5" x-transition class="space-y-6">
                    <div>
                        <span class="text-xs font-mono font-bold text-brand-600 uppercase tracking-wider">Step 5 of 5</span>
                        <h1 class="font-heading font-bold text-2xl text-ink-950 mt-1">Tuition Budget & Scholarship Interest</h1>
                        <p class="text-xs sm:text-sm text-ink-500 font-sans mt-1">
                            Set your semester fee comfort range. We will automatically highlight need-based and merit aid.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-surface border border-border-default space-y-6 shadow-card">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-ink-800">Max Semester Tuition:</span>
                                <span class="text-sm font-bold font-mono text-brand-700 bg-brand-50 px-3 py-1 rounded-full border border-brand-200">
                                    PKR <span x-text="Number(budgetMax).toLocaleString()"></span> / sem
                                </span>
                            </div>

                            <input 
                                type="range" 
                                name="budget_max" 
                                x-model="budgetMax"
                                min="50000" 
                                max="400000" 
                                step="10000" 
                                class="w-full h-2 bg-surface-subtle rounded-lg appearance-none cursor-pointer accent-brand-600"
                            />

                            <div class="flex items-center justify-between text-[11px] text-ink-400 font-mono">
                                <span>PKR 50k (Public)</span>
                                <span>PKR 200k (Chartered)</span>
                                <span>PKR 400k+ (Private)</span>
                            </div>
                        </div>

                        <div class="space-y-3 pt-4 border-t border-border-subtle">
                            <span class="block text-xs font-semibold text-ink-800">Are you interested in scholarship opportunities?</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="p-3 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="scholarshipInterest === 'need_and_merit' ? 'border-brand-500 bg-brand-50/20' : ''">
                                    <input type="radio" name="scholarship_interest" value="need_and_merit" x-model="scholarshipInterest" class="text-brand-600" />
                                    <div class="mt-1">
                                        <div class="text-xs font-bold text-ink-950">Need & Merit Scholarships</div>
                                        <div class="text-[11px] text-ink-500">HEC Need-Based, Ehsaas, PEEF, Institutional</div>
                                    </div>
                                </label>
                                <label class="p-3 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="scholarshipInterest === 'none' ? 'border-brand-500 bg-brand-50/20' : ''">
                                    <input type="radio" name="scholarship_interest" value="none" x-model="scholarshipInterest" class="text-brand-600" />
                                    <div class="mt-1">
                                        <div class="text-xs font-bold text-ink-950">Self-Financed Only</div>
                                        <div class="text-[11px] text-ink-500">Regular fee payment without financial aid</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Controls Footer -->
                <div class="flex items-center justify-between pt-8 border-t border-border-default mt-8">
                    <button 
                        type="button" 
                        @click="prevStep()" 
                        x-show="step > 1"
                        class="px-4 py-2 rounded-xl border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-700 cursor-pointer flex items-center gap-1.5"
                    >
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                        <span>Previous</span>
                    </button>
                    <div x-show="step === 1"></div>

                    <!-- Next Step Button -->
                    <button 
                        type="button" 
                        @click="nextStep()" 
                        x-show="step < totalSteps"
                        class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold shadow-xs transition-smooth cursor-pointer flex items-center gap-1.5 ml-auto"
                    >
                        <span>Continue</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                    </button>

                    <!-- Final Complete Onboarding Button -->
                    <button 
                        type="submit" 
                        x-show="step === totalSteps"
                        class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-card transition-smooth cursor-pointer flex items-center gap-2 ml-auto"
                    >
                        <x-icon name="sparkles" class="w-4 h-4" />
                        <span>Complete Profile & Open Dashboard</span>
                    </button>
                </div>
            </form>
        </main>

        <!-- Privacy & Statutory Footer -->
        <footer class="border-t border-border-default bg-surface py-4 px-4 text-center text-xs text-ink-400 font-sans">
            Candidate data stored securely in compliance with the Higher Education Commission data privacy frameworks.
        </footer>
    </div>
</x-layouts.base>
