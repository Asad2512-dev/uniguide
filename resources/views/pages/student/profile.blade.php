<x-layouts.student 
    title="Academic Profile & Qualifications — UniGuide Pakistan"
    description="Manage your secondary board records, FSc marks, entry test scores, and attested documents for Pakistani university admissions."
>
    <div class="space-y-8 pb-12">
        
        <!-- Header Strip -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-border-default">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-brand-50 border border-brand-200/80 text-brand-700 text-xs font-semibold mb-2">
                    <x-icon name="user-check" class="w-3.5 h-3.5 text-brand-600" />
                    <span>Verified Academic Credentials</span>
                </div>
                <h1 class="font-heading font-bold text-2xl sm:text-3xl text-ink-950 tracking-tight">
                    Academic Profile & Qualifications
                </h1>
                <p class="text-xs sm:text-sm text-ink-600 font-sans mt-1">
                    Your normalized educational marks, board roll numbers, and entrance test scores used for national merit formulas.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs font-mono font-bold px-3 py-1.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200">
                    Profile Completeness: {{ $completeness['percentage'] }}%
                </span>
            </div>
        </div>

        <form action="{{ route('student.profile.update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- 1. Candidate Personal & Identity Details -->
            <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h2 class="font-heading font-bold text-base text-ink-950">Candidate Identification</h2>
                    <span class="text-xs text-ink-400 font-mono">CNIC: {{ $profile['cnic'] }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-ink-800">Full Legal Name</label>
                        <input 
                            type="text" 
                            name="full_name" 
                            value="{{ $profile['full_name'] }}" 
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-ink-800">Primary Mobile Phone</label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="{{ $profile['phone'] }}" 
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-ink-800">Residential City</label>
                        <input 
                            type="text" 
                            name="city" 
                            value="{{ $profile['city'] }}" 
                            required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-sans focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500"
                        />
                    </div>
                </div>
            </div>

            <!-- 2. Secondary & Higher Secondary Board Qualifications -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Matric / SSC Card -->
                <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span>
                            <h3 class="font-heading font-bold text-sm text-ink-950">Matriculation / O-Level</h3>
                        </div>
                        <span class="text-[10px] font-mono text-success-700 bg-success-50 px-2 py-0.5 rounded font-bold">
                            {{ $profile['matric']['grade'] }}
                        </span>
                    </div>

                    <div class="space-y-4 text-xs font-sans">
                        <div class="space-y-1">
                            <label class="block text-ink-600 font-medium">Examining Board</label>
                            <input 
                                type="text" 
                                name="matric_board" 
                                value="{{ $profile['matric']['board'] }}" 
                                class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface text-ink-900 text-xs"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-ink-600 font-medium">Roll Number</label>
                                <input 
                                    type="text" 
                                    name="matric_roll" 
                                    value="{{ $profile['matric']['roll_number'] }}" 
                                    class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-mono"
                                />
                            </div>
                            <div class="space-y-1">
                                <label class="block text-ink-600 font-medium">Obtained Percentage (%)</label>
                                <input 
                                    type="number" 
                                    name="matric_percentage" 
                                    value="{{ $profile['matric']['percentage'] }}" 
                                    step="0.1" 
                                    min="50" 
                                    max="100" 
                                    required
                                    class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-mono"
                                />
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-surface-subtle text-[11px] text-ink-500 font-sans">
                            Official Sanad verified against FBISE National Database.
                        </div>
                    </div>
                </div>

                <!-- FSc / HSSC Card -->
                <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span>
                            <h3 class="font-heading font-bold text-sm text-ink-950">FSc Pre-Engineering / HSSC</h3>
                        </div>
                        <span class="text-[10px] font-mono text-brand-700 bg-brand-50 px-2 py-0.5 rounded font-bold">
                            {{ $profile['fsc']['grade'] }}
                        </span>
                    </div>

                    <div class="space-y-4 text-xs font-sans">
                        <div class="space-y-1">
                            <label class="block text-ink-600 font-medium">Examining Board</label>
                            <input 
                                type="text" 
                                name="fsc_board" 
                                value="{{ $profile['fsc']['board'] }}" 
                                class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface text-ink-900 text-xs"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="space-y-1">
                                <label class="block text-ink-600 font-medium">Roll Number</label>
                                <input 
                                    type="text" 
                                    name="fsc_roll" 
                                    value="{{ $profile['fsc']['roll_number'] }}" 
                                    class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-mono"
                                />
                            </div>
                            <div class="space-y-1">
                                <label class="block text-ink-600 font-medium">Part-1 Percentage (%)</label>
                                <input 
                                    type="number" 
                                    name="fsc_percentage" 
                                    value="{{ $profile['fsc']['percentage'] }}" 
                                    step="0.1" 
                                    min="50" 
                                    max="100" 
                                    required
                                    class="w-full px-3 py-2 rounded-xl border border-border-default bg-surface text-ink-900 text-xs font-mono"
                                />
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-surface-subtle text-[11px] text-ink-500 font-sans">
                            Part-1 result verified: {{ $profile['fsc']['obtained_marks'] }} / {{ $profile['fsc']['total_marks'] }} Marks.
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Standardized Entry Test Score Records -->
            <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <div>
                        <h2 class="font-heading font-bold text-base text-ink-950">Standardized Entry Test Scores</h2>
                        <p class="text-xs text-ink-500 font-sans">Institutional entrance exams verified for Fall 2026 admissions.</p>
                    </div>
                    <span class="text-xs font-mono text-ink-400">{{ count($profile['entry_tests']) }} Tests on Record</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($profile['entry_tests'] as $test)
                        <div class="p-4 rounded-xl border border-border-default bg-surface-subtle space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-heading font-bold text-xs text-ink-950">{{ $test['name'] }}</span>
                                <span class="text-[10px] font-mono font-bold text-success-700 bg-success-50 px-1.5 py-0.2 rounded">
                                    {{ $test['status'] }}
                                </span>
                            </div>
                            <div class="flex items-baseline gap-1 mt-1">
                                <span class="font-heading font-bold text-2xl text-brand-700">{{ $test['score'] }}</span>
                                <span class="text-xs font-mono text-ink-400">/ {{ $test['max_score'] }}</span>
                                <span class="text-xs font-mono font-bold text-ink-600 ml-auto">({{ $test['percentage'] }}%)</span>
                            </div>
                            <div class="text-[11px] text-ink-400 font-mono">
                                Roll: {{ $test['roll_number'] }} • {{ $test['test_date'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. Verified Document Locker Strip -->
            <div class="p-6 rounded-2xl bg-surface border border-border-default shadow-card space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <div>
                        <h2 class="font-heading font-bold text-base text-ink-950">Admission Documents Locker</h2>
                        <p class="text-xs text-ink-500 font-sans">Attested certificates required for provincial and HEC verification.</p>
                    </div>
                </div>

                <div class="space-y-2.5">
                    @foreach($profile['documents'] as $doc)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 rounded-xl border border-border-default bg-surface-subtle gap-2 text-xs font-sans">
                            <div class="flex items-center gap-2.5">
                                <x-icon name="file-text" class="w-4 h-4 text-brand-600 shrink-0" />
                                <div>
                                    <span class="font-semibold text-ink-900">{{ $doc['title'] }}</span>
                                    <span class="text-[11px] text-ink-400 font-mono block">{{ $doc['file_name'] }} • Uploaded {{ $doc['uploaded_at'] }}</span>
                                </div>
                            </div>

                            <span class="text-[11px] font-mono font-bold px-2.5 py-0.5 rounded-full {{ $doc['status'] === 'verified' ? 'bg-success-50 text-success-700 border border-success-200' : 'bg-warning-50 text-warning-700 border border-warning-200' }}">
                                {{ $doc['status_label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end gap-3 pt-4">
                <button 
                    type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold font-sans shadow-card transition-smooth cursor-pointer flex items-center gap-2"
                >
                    <x-icon name="check" class="w-4 h-4" />
                    <span>Save & Recalculate Merit Aggregates</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.student>

