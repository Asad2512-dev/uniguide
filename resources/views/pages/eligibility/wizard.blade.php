<x-layouts.public 
    title="Academic Eligibility Checker — UniGuide Pakistan"
    description="Multi-step eligibility rules engine verifying your academic marks against PEC engineering mandates, NCEAC computing guidelines, and university prospectuses."
>
    <div 
        x-data="{
            step: 1,
            totalSteps: 6,
            qualification: 'fsc',
            academicGroup: 'pre_engineering',
            matricPercentage: 85.0,
            fscPercentage: 78.0,
            desiredProgram: 'computing',
            preferredLocation: 'all',
            testType: 'net',
            testScore: 145,

            nextStep() {
                if (!window.validateUniGuideStep(this.$root, this.step)) return;
                if (this.step < this.totalSteps) {
                    this.step++;
                    window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
                }
            },
            prevStep() {
                if (this.step > 1) {
                    this.step--;
                    window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth' });
                }
            }
        }"
        class="min-h-screen bg-canvas pb-24"
    >
        <!-- Header Strip -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'Eligibility Checker', 'url' => route('eligibility.index')]
                    ]" />
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200/80 text-emerald-700 text-xs font-semibold mb-2">
                            <x-icon name="check-circle" class="w-3.5 h-3.5 text-emerald-600" />
                            <span>Verified Statutory Rules Engine</span>
                        </div>
                        <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                            Academic Eligibility Checker
                        </h1>
                        <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                            Verify whether you meet the statutory prerequisites mandated by the Pakistan Engineering Council (PEC), NCEAC, and federal university charters.
                        </p>
                    </div>

                    <div class="shrink-0 flex items-center gap-2">
                        <span class="text-xs font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                            Step <span x-text="step"></span> of <span x-text="totalSteps"></span>
                        </span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-surface-subtle h-2 rounded-full mt-6 overflow-hidden border border-border-default">
                    <div 
                        class="bg-emerald-600 h-full rounded-full transition-all duration-300"
                        :style="'width: ' + ((step / totalSteps) * 100) + '%'"
                    ></div>
                </div>
            </div>
        </section>

        <!-- Main Wizard Container -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <form action="{{ route('eligibility.results') }}" method="GET" class="space-y-8">
                
                <!-- STEP 1: Academic Qualification -->
                <div data-step="1" x-show="step === 1" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider font-mono">Step 1 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Select Your Qualification Type</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Different statutory equivalency frameworks apply to domestic boards versus international Cambridge credentials.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($qualifications as $qual)
                                <label 
                                    class="p-4 rounded-xl border border-border-default cursor-pointer hover:border-emerald-400 hover:bg-surface-subtle transition-all flex items-start gap-3.5"
                                    :class="qualification === '{{ $qual['id'] }}' ? 'border-emerald-600 ring-2 ring-emerald-500/10 bg-emerald-50/20' : ''"
                                >
                                    <input type="radio" name="qualification" value="{{ $qual['id'] }}" x-model="qualification" class="mt-1 text-emerald-600 focus:ring-emerald-500" />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <x-icon :name="$qual['icon']" class="w-4 h-4 text-emerald-600" />
                                            <span class="text-xs font-bold text-ink-950">{{ $qual['name'] }}</span>
                                        </div>
                                        <p class="text-xs sm:text-[11px] text-ink-500 font-sans mt-0.5">{{ $qual['desc'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Academic Marks -->
                <div data-step="2" x-show="step === 2" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider font-mono">Step 2 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Enter Academic Marks</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Pakistani universities enforce strict cutoff percentages (e.g., minimum 60% for PEC engineering degrees).
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-ink-800">
                                    Matriculation / O-Level Percentage (%)
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="matric_percentage" 
                                        x-model="matricPercentage"
                                        min="45" 
                                        max="100" 
                                        step="0.1" 
                                        required
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    />
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-ink-600 font-mono">%</span>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-ink-800">
                                    Intermediate / FSc Part-1 Percentage (%)
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="fsc_percentage" 
                                        x-model="fscPercentage"
                                        min="45" 
                                        max="100" 
                                        step="0.1" 
                                        required
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    />
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-ink-600 font-mono">%</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-surface-subtle text-xs text-ink-600 font-sans space-y-1">
                            <div class="font-semibold text-ink-900 flex items-center gap-1.5">
                                <x-icon name="info" class="w-3.5 h-3.5 text-emerald-600" />
                                <span>Statutory Rule Note: PEC 60% Mandate</span>
                            </div>
                            <p>Bachelor of Engineering (BE) programs strictly require ≥ 60.0% marks under PEC Act V of 1976.</p>
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Academic Group Stream -->
                <div data-step="3" x-show="step === 3" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider font-mono">Step 3 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Higher Secondary Academic Group</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Your subject combination dictates regulatory clearance for Engineering vs. Computing.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($groups as $grp)
                                <label 
                                    class="p-4 rounded-xl border border-border-default cursor-pointer hover:border-emerald-400 hover:bg-surface-subtle transition-all flex items-start gap-3.5"
                                    :class="academicGroup === '{{ $grp['id'] }}' ? 'border-emerald-600 ring-2 ring-emerald-500/10 bg-emerald-50/20' : ''"
                                >
                                    <input type="radio" name="academic_group" value="{{ $grp['id'] }}" x-model="academicGroup" class="mt-1 text-emerald-600 focus:ring-emerald-500" />
                                    <div>
                                        <span class="text-xs font-bold text-ink-950 block">{{ $grp['name'] }}</span>
                                        <p class="text-xs sm:text-[11px] text-ink-500 font-sans mt-0.5">{{ $grp['desc'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 4: Desired Program -->
                <div data-step="4" x-show="step === 4" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider font-mono">Step 4 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Intended Degree Discipline</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                We will cross-reference this field against accredited offerings.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="p-3.5 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="desiredProgram === 'computing' ? 'border-emerald-600 bg-emerald-50/20' : ''">
                                <input type="radio" name="desired_program" value="computing" x-model="desiredProgram" class="text-emerald-600" />
                                <span class="text-xs font-bold text-ink-900 ml-2">Computing & Artificial Intelligence (BS CS / SE / AI / DS)</span>
                            </label>

                            <label class="p-3.5 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="desiredProgram === 'engineering' ? 'border-emerald-600 bg-emerald-50/20' : ''">
                                <input type="radio" name="desired_program" value="engineering" x-model="desiredProgram" class="text-emerald-600" />
                                <span class="text-xs font-bold text-ink-900 ml-2">Engineering & Technology (BE Electrical / Mechanical / Civil)</span>
                            </label>

                            <label class="p-3.5 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="desiredProgram === 'business' ? 'border-emerald-600 bg-emerald-50/20' : ''">
                                <input type="radio" name="desired_program" value="business" x-model="desiredProgram" class="text-emerald-600" />
                                <span class="text-xs font-bold text-ink-900 ml-2">Business & Management Sciences (BBA / Accounting & Finance)</span>
                            </label>

                            <label class="p-3.5 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="desiredProgram === 'medical' ? 'border-emerald-600 bg-emerald-50/20' : ''">
                                <input type="radio" name="desired_program" value="medical" x-model="desiredProgram" class="text-emerald-600" />
                                <span class="text-xs font-bold text-ink-900 ml-2">Medical & Allied Biosciences (MBBS / DPT / Biotechnology)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- STEP 5: Preferred Location -->
                <div data-step="5" x-show="step === 5" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider font-mono">Step 5 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Geographic Region Preference</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Filter universities by province or choose All Pakistan.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($locations as $loc)
                                <label class="p-3 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="preferredLocation === '{{ $loc }}' ? 'border-emerald-600 bg-emerald-50/20 font-bold' : ''">
                                    <input type="radio" name="preferred_location" value="{{ $loc }}" x-model="preferredLocation" class="text-emerald-600" />
                                    <span class="text-xs text-ink-900 ml-2">{{ $loc }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 6: Entrance Test Information -->
                <div data-step="6" x-show="step === 6" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider font-mono">Step 6 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Entrance Test & Scores</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Enter your score or expected score for aggregate calculation.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-xs font-semibold text-ink-800">Target Standardized Test:</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @foreach($entryTests as $et)
                                    <label class="p-3 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="testType === '{{ $et['id'] }}' ? 'border-emerald-600 bg-emerald-50/20' : ''">
                                        <input type="radio" name="test_type" value="{{ $et['id'] }}" x-model="testType" class="text-emerald-600" />
                                        <div class="mt-1">
                                            <div class="text-xs font-bold text-ink-950">{{ $et['name'] }}</div>
                                            <div class="text-xs sm:text-[10px] text-ink-400 font-mono">Max: {{ $et['max'] }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="space-y-2 pt-2">
                                <label class="block text-xs font-semibold text-ink-800">Your Score:</label>
                                <input 
                                    type="number" 
                                    name="test_score" 
                                    x-model="testScore"
                                    min="0" 
                                    max="1600" 
                                    required
                                    class="w-full max-w-xs px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-sm font-mono"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="flex items-center justify-between pt-4">
                    <button 
                        type="button" 
                        @click="prevStep()" 
                        x-show="step > 1"
                        class="px-5 py-2.5 rounded-xl border border-border-default bg-surface text-xs font-semibold text-ink-700 cursor-pointer flex items-center gap-1.5"
                    >
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                        <span>Previous</span>
                    </button>
                    <div x-show="step === 1"></div>

                    <button 
                        type="button" 
                        @click="nextStep()" 
                        x-show="step < totalSteps"
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs cursor-pointer flex items-center gap-1.5 ml-auto"
                    >
                        <span>Continue</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                    </button>

                    <button 
                        type="submit" 
                        x-show="step === totalSteps"
                        class="px-7 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-card cursor-pointer flex items-center gap-2 ml-auto"
                    >
                        <x-icon name="check-circle" class="w-4 h-4" />
                        <span>Check Official Eligibility</span>
                    </button>
                </div>
            </form>
        </main>
    </div>
</x-layouts.public>

