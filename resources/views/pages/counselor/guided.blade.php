<x-layouts.public 
    title="Guided Merit & Fit Assessment — UniGuide Pakistan"
    description="6-step intelligent admission assessment. Calculate your exact statutory aggregates and get structured Reach, Match, and Safety university tiers."
>
    <div 
        x-data="{
            step: 1,
            totalSteps: 6,
            matricPct: 82.5,
            fscPct: 78.0,
            boardStream: 'pre_engineering',
            discipline: 'computing',
            location: 'islamabad',
            budgetMax: 200000,
            testType: 'net',
            testScore: 145,
            monthlyIncome: 55000,
            scholarshipNeed: 'yes',

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
        class="min-h-screen bg-canvas pb-24"
    >
        <!-- Header Strip -->
        <section class="border-b border-border-default/80 bg-surface">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Breadcrumbs -->
                <div class="mb-4">
                    <x-ui.breadcrumbs :items="[
                        ['label' => 'AI Counselor', 'url' => route('counselor.index')],
                        ['label' => 'Guided Fit Assessment', 'url' => route('counselor.guided')]
                    ]" />
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                            <x-icon name="sparkles" class="w-3.5 h-3.5 text-brand-600" />
                            <span>Step-by-Step Strategic Assessment</span>
                        </div>
                        <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                            Guided Merit & Fit Assessment
                        </h1>
                        <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                            Answer 6 questions to generate your institutional Reach, Match, and Safety tiers with verified aggregates.
                        </p>
                    </div>

                    <!-- Step Progress Pill -->
                    <div class="shrink-0 flex items-center gap-2">
                        <span class="text-xs font-mono font-bold text-brand-700 bg-brand-50 border border-brand-200 px-3 py-1 rounded-full">
                            Step <span x-text="step"></span> of <span x-text="totalSteps"></span>
                        </span>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-surface-subtle h-2 rounded-full mt-6 overflow-hidden border border-border-default">
                    <div 
                        class="bg-brand-600 h-full rounded-full transition-all duration-300"
                        :style="'width: ' + ((step / totalSteps) * 100) + '%'"
                    ></div>
                </div>
            </div>
        </section>

        <!-- Main Form Container -->
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <form action="{{ route('counselor.recommendations') }}" method="GET" class="space-y-8">
                
                <!-- STEP 1: Academic Qualifications -->
                <div x-show="step === 1" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider font-mono">Step 1 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Your Academic Qualifications</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                We normalize Pakistani BISE boards, FBISE, and Cambridge O/A-Levels for merit calculation.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Matric / O-Level -->
                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-ink-800 font-sans">
                                    Matriculation / O-Level Percentage (%)
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="matric_percentage" 
                                        x-model="matricPct"
                                        min="50" 
                                        max="100" 
                                        step="0.1" 
                                        required
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 font-sans"
                                    />
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-ink-400 font-mono">%</span>
                                </div>
                                <span class="text-[11px] text-ink-400 font-sans">If O-Level, input IBCC equivalence percentage.</span>
                            </div>

                            <!-- FSc / A-Level -->
                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-ink-800 font-sans">
                                    FSc Part-1 / HSSC / A-Level Percentage (%)
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        name="fsc_percentage" 
                                        x-model="fscPct"
                                        min="50" 
                                        max="100" 
                                        step="0.1" 
                                        required
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 font-sans"
                                    />
                                    <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-ink-400 font-mono">%</span>
                                </div>
                                <span class="text-[11px] text-ink-400 font-sans">For Fall 2026 admissions, Part-1 marks are used for provisional merit.</span>
                            </div>
                        </div>

                        <!-- Academic Board Group Stream -->
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-ink-800 font-sans">
                                Secondary Study Stream (FSc / HSSC)
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="boardStream === 'pre_engineering' ? 'border-brand-500 bg-brand-50/30' : ''">
                                    <input type="radio" name="board_stream" value="pre_engineering" x-model="boardStream" class="text-brand-600 focus:ring-brand-500" />
                                    <div>
                                        <div class="text-xs font-bold text-ink-900">Pre-Engineering</div>
                                        <div class="text-[11px] text-ink-500">Physics, Chem, Math</div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="boardStream === 'ics' ? 'border-brand-500 bg-brand-50/30' : ''">
                                    <input type="radio" name="board_stream" value="ics" x-model="boardStream" class="text-brand-600 focus:ring-brand-500" />
                                    <div>
                                        <div class="text-xs font-bold text-ink-900">ICS (Computer)</div>
                                        <div class="text-[11px] text-ink-500">Physics, Comp, Math</div>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all" :class="boardStream === 'pre_medical' ? 'border-brand-500 bg-brand-50/30' : ''">
                                    <input type="radio" name="board_stream" value="pre_medical" x-model="boardStream" class="text-brand-600 focus:ring-brand-500" />
                                    <div>
                                        <div class="text-xs font-bold text-ink-900">Pre-Medical</div>
                                        <div class="text-[11px] text-ink-500">Bio, Chem, Physics</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Preferred Discipline -->
                <div x-show="step === 2" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider font-mono">Step 2 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Select Your Intended Field of Study</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Choose your primary academic interest. You can evaluate other fields later.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($disciplines as $disc)
                                <label 
                                    class="flex items-start gap-3.5 p-4 rounded-xl border border-border-default cursor-pointer hover:border-brand-300 hover:bg-surface-subtle transition-all"
                                    :class="discipline === '{{ $disc['id'] }}' ? 'border-brand-600 ring-2 ring-brand-500/10 bg-brand-50/20' : ''"
                                >
                                    <input 
                                        type="radio" 
                                        name="discipline" 
                                        value="{{ $disc['id'] }}" 
                                        x-model="discipline" 
                                        class="mt-1 text-brand-600 focus:ring-brand-500"
                                    />
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <x-icon :name="$disc['icon']" class="w-4 h-4 text-brand-600" />
                                            <span class="text-xs font-bold text-ink-950">{{ $disc['name'] }}</span>
                                        </div>
                                        <p class="text-[11px] text-ink-500 font-sans mt-1 leading-normal">{{ $disc['desc'] }}</p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 3: Location Preference -->
                <div x-show="step === 3" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider font-mono">Step 3 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Preferred University Geographic Region</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Select where you intend to study, or choose All Pakistan for maximum scope.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            @foreach($locations as $loc)
                                <label 
                                    class="flex items-center gap-3 p-4 rounded-xl border border-border-default cursor-pointer hover:border-brand-300 hover:bg-surface-subtle transition-all"
                                    :class="location === '{{ $loc['id'] }}' ? 'border-brand-600 ring-2 ring-brand-500/10 bg-brand-50/20' : ''"
                                >
                                    <input 
                                        type="radio" 
                                        name="location" 
                                        value="{{ $loc['id'] }}" 
                                        x-model="location" 
                                        class="text-brand-600 focus:ring-brand-500"
                                    />
                                    <div class="flex items-center gap-2.5">
                                        <x-icon :name="$loc['icon']" class="w-4 h-4 text-brand-600" />
                                        <span class="text-xs font-bold text-ink-900">{{ $loc['name'] }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- STEP 4: Budget & Tuition Ceilings -->
                <div x-show="step === 4" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider font-mono">Step 4 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Tuition Budget & Fee Ceiling</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Set the maximum tuition fee per semester your family is comfortable funding.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-ink-700">Per Semester Budget Ceiling:</span>
                                <span class="font-mono font-bold text-sm text-brand-700 bg-brand-50 px-3 py-1 rounded-full border border-brand-200">
                                    PKR <span x-text="Number(budgetMax).toLocaleString()"></span> / semester
                                </span>
                            </div>

                            <input 
                                type="range" 
                                name="budget_max" 
                                x-model="budgetMax"
                                min="60000" 
                                max="450000" 
                                step="10000" 
                                class="w-full h-2 bg-surface-subtle rounded-lg appearance-none cursor-pointer accent-brand-600"
                            />

                            <div class="flex items-center justify-between text-[11px] text-ink-400 font-mono">
                                <span>PKR 60k (Public Subsidized)</span>
                                <span>PKR 200k (National Chartered)</span>
                                <span>PKR 450k+ (Premier Private)</span>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-surface-subtle border border-border-default text-xs text-ink-600 font-sans space-y-1">
                            <div class="font-semibold text-ink-900 flex items-center gap-1.5">
                                <x-icon name="info" class="w-3.5 h-3.5 text-brand-600" />
                                <span>Public vs Private Tuition Landscape</span>
                            </div>
                            <p>
                                Public universities like UET Lahore and Quaid-i-Azam charge PKR 60,000 to PKR 90,000. Semi-government chartered universities like NUST and FAST charge PKR 190,000 to PKR 220,000.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- STEP 5: Entry-Test Profile -->
                <div x-show="step === 5" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider font-mono">Step 5 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">National & University Entry Tests</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Entry tests carry 50% to 75% weightage in major Pakistani university merit lists.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-xs font-semibold text-ink-800 font-sans">
                                Select Primary Test You Have Taken or Plan to Take:
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($entryTests as $et)
                                    <label 
                                        class="p-3 rounded-xl border border-border-default cursor-pointer hover:bg-surface-subtle transition-all"
                                        :class="testType === '{{ $et['id'] }}' ? 'border-brand-600 bg-brand-50/20 ring-2 ring-brand-500/10' : ''"
                                    >
                                        <input 
                                            type="radio" 
                                            name="test_type" 
                                            value="{{ $et['id'] }}" 
                                            x-model="testType" 
                                            class="text-brand-600 focus:ring-brand-500"
                                        />
                                        <div class="mt-2">
                                            <div class="text-xs font-bold text-ink-950">{{ $et['name'] }}</div>
                                            <div class="text-[11px] text-ink-400 font-mono">Max Marks: {{ $et['max'] }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="space-y-2 pt-2">
                                <label class="block text-xs font-semibold text-ink-800 font-sans">
                                    Your Actual or Target Score:
                                </label>
                                <div class="relative max-w-xs">
                                    <input 
                                        type="number" 
                                        name="test_score" 
                                        x-model="testScore"
                                        min="0" 
                                        max="1600" 
                                        required
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 font-sans"
                                    />
                                </div>
                                <span class="text-[11px] text-ink-400 font-sans">E.g., 148 for NUST NET, 72 for FAST NU Test, 280 for ECAT.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 6: Scholarships & Financial Aid -->
                <div x-show="step === 6" x-transition class="space-y-6">
                    <div class="bg-surface rounded-2xl border border-border-default p-6 sm:p-8 shadow-card space-y-6">
                        <div>
                            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider font-mono">Step 6 of 6</span>
                            <h2 class="font-heading font-bold text-xl text-ink-950 mt-1">Financial Aid & Scholarship Eligibility</h2>
                            <p class="text-xs text-ink-500 font-sans mt-1">
                                Check qualification for HEC Need-Based, Ehsaas, and provincial endowment funds.
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="block text-xs font-semibold text-ink-800 font-sans">
                                    Total Gross Monthly Household Income:
                                </label>
                                <div class="relative max-w-sm">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-ink-400 font-mono">PKR</span>
                                    <input 
                                        type="number" 
                                        name="monthly_income" 
                                        x-model="monthlyIncome"
                                        min="10000" 
                                        max="500000" 
                                        step="5000"
                                        class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-sm focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 font-sans"
                                    />
                                </div>
                                <span class="text-[11px] text-ink-400 font-sans">HEC Need-Based threshold is typically below PKR 45,000/month.</span>
                            </div>

                            <div class="p-4 rounded-xl bg-success-50/50 border border-success-200/60 text-xs text-success-800 font-sans space-y-1">
                                <div class="font-bold flex items-center gap-1.5 text-success-900">
                                    <x-icon name="check-circle" class="w-4 h-4 text-success-600" />
                                    <span>Instant Financial Aid Verification Active</span>
                                </div>
                                <p>
                                    Your assessment will automatically screen for 100% full tuition scholarships and living stipends based on this income declaration.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Controls Footer -->
                <div class="flex items-center justify-between pt-4">
                    <button 
                        type="button" 
                        @click="prevStep()" 
                        x-show="step > 1"
                        class="px-5 py-2.5 rounded-xl border border-border-default bg-surface hover:bg-surface-subtle text-xs font-semibold text-ink-700 transition-smooth cursor-pointer flex items-center gap-1.5"
                    >
                        <x-icon name="arrow-left" class="w-3.5 h-3.5" />
                        <span>Previous Step</span>
                    </button>
                    <div x-show="step === 1"></div>

                    <!-- Next Button for Steps 1-5 -->
                    <button 
                        type="button" 
                        @click="nextStep()" 
                        x-show="step < totalSteps"
                        class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-semibold font-sans shadow-xs transition-smooth cursor-pointer flex items-center gap-1.5 ml-auto"
                    >
                        <span>Continue</span>
                        <x-icon name="arrow-right" class="w-3.5 h-3.5" />
                    </button>

                    <!-- Final Submission Button on Step 6 -->
                    <button 
                        type="submit" 
                        x-show="step === totalSteps"
                        class="px-7 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold font-sans shadow-card transition-smooth cursor-pointer flex items-center gap-2 ml-auto"
                    >
                        <x-icon name="sparkles" class="w-4 h-4" />
                        <span>Generate My Recommendation Tiers</span>
                    </button>
                </div>
            </form>
        </main>
    </div>
</x-layouts.public>

