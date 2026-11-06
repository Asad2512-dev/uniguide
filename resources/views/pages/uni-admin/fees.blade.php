<x-layouts.university-admin 
    :title="'Fee Management & Hostel Tariffs — ' . $institution['acronym']"
    :institutionName="$institution['name']"
    :institutionCode="$institution['code']"
>
    <div x-data="{ editFeeModal: false, activeCatIndex: 0, currentTuition: 0, categoryName: '' }">
        <x-ui.page-header 
            title="Fee Schedules & Hostel Tariffs Management"
            description="Manage audited tuition fee schedules, security deposits, one-time admission charges, and on-campus hostel mess tariffs."
            badge="Verified Fall 2026 Structure"
            badgeVariant="success"
        >
            <x-slot:breadcrumbs>
                <x-ui.breadcrumbs 
                    :items="[
                        ['label' => 'Institutional Console', 'url' => route('uni_admin.dashboard')],
                        ['label' => 'Fee Schedules']
                    ]"
                />
            </x-slot:breadcrumbs>

            <x-slot:actions>
                <x-ui.button variant="outline" size="sm" icon="file-text">
                    Official Fee Gazette PDF
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        <!-- Academic Tuition Schedules -->
        <div class="space-y-6 mb-10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-base text-ink-950">Academic Program Tuition Schedules</h3>
                    <p class="text-xs text-ink-500 font-sans">Official charges published on the national admissions discovery portal.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($fees['academic_categories'] as $idx => $cat)
                    <div class="p-4 sm:p-6 rounded-2xl bg-surface border border-border-default hover:border-brand-300 hover:shadow-card transition-all flex flex-col justify-between">
                        <div class="space-y-4">
                            <div class="flex items-start justify-between pb-3 border-b border-border-subtle">
                                <div>
                                    <h4 class="font-heading font-bold text-base text-ink-950">{{ $cat['category'] }}</h4>
                                    <span class="text-xs sm:text-[11px] text-ink-600 font-mono">Updated {{ $cat['updated_at'] }}</span>
                                </div>
                                <span class="text-xs sm:text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2 py-0.5 rounded">
                                    Audited
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-xs font-sans">
                                <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                    <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">Semester Tuition</span>
                                    <span class="font-mono font-bold text-base text-brand-700">PKR {{ number_format($cat['tuition_per_semester']) }}</span>
                                </div>
                                <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                    <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">Annual Tuition</span>
                                    <span class="font-mono font-bold text-ink-900">PKR {{ number_format($cat['tuition_annual']) }}</span>
                                </div>
                                <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                    <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">Lab & Computing Fee</span>
                                    <span class="font-mono text-ink-800">PKR {{ number_format($cat['lab_charges']) }}</span>
                                </div>
                                <div class="p-3 rounded-xl bg-surface-subtle border border-border-subtle">
                                    <span class="text-xs sm:text-[10px] text-ink-600 uppercase font-mono block">One-Time Admission</span>
                                    <span class="font-mono text-ink-800">PKR {{ number_format($cat['admission_fee_onetime']) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-3 border-t border-border-subtle flex items-center justify-between text-xs">
                            <span class="text-xs sm:text-[11px] text-ink-500 font-sans">Refundable Security: PKR {{ number_format($cat['security_deposit_refundable']) }}</span>
                            <button 
                                type="button" 
                                @click="activeCatIndex = {{ $idx }}; currentTuition = {{ $cat['tuition_per_semester'] }}; categoryName = '{{ addslashes($cat['category']) }}'; editFeeModal = true"
                                class="px-3 py-1.5 rounded-lg border border-border-default bg-surface hover:bg-surface-subtle font-semibold text-ink-700 transition-smooth cursor-pointer"
                            >
                                Edit Tuition Rate →
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Residential Hostel & Mess Tariffs -->
        <div class="space-y-6 mb-10">
            <div>
                <h3 class="font-heading font-bold text-base text-ink-950">On-Campus Hostels & Mess Tariffs</h3>
                <p class="text-xs text-ink-500 font-sans">Sector H-12 residential hostels tariff schedule approved by Directorate of Administration.</p>
            </div>

            <div class="rounded-2xl bg-surface border border-border-default shadow-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table data-mobile-table class="w-full text-left border-collapse" aria-label="On-Campus Hostels & Mess Tariffs">
                        <thead>
                            <tr class="border-b border-border-default bg-surface-subtle text-xs sm:text-[11px] font-mono uppercase text-ink-500 tracking-wider">
                                <th scope="col" class="py-3 px-4">Room Category</th>
                                <th scope="col" class="py-3 px-4">Per Semester Rent</th>
                                <th scope="col" class="py-3 px-4">Mess Dining (Monthly)</th>
                                <th scope="col" class="py-3 px-4">Refundable Security</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle text-xs">
                            @foreach($fees['hostel_tariffs'] as $h)
                                <tr>
                                    <td class="py-3.5 px-4 font-bold text-ink-950 font-sans">{{ $h['room_type'] }}</td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-ink-900">PKR {{ number_format($h['fee_per_semester']) }}</td>
                                    <td class="py-3.5 px-4 font-mono text-ink-800">PKR {{ number_format($h['mess_monthly']) }} / mo</td>
                                    <td class="py-3.5 px-4 font-mono text-ink-600">PKR {{ number_format($h['security']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Institutional Fee Policies Box -->
        <div class="p-4 sm:p-6 rounded-2xl bg-surface-subtle border border-border-default space-y-3 text-xs font-sans">
            <span class="text-xs sm:text-[10px] font-mono font-bold text-ink-600 uppercase block">Fee Disbursal & Refund Regulations</span>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <strong class="text-ink-900 block mb-0.5">Installment Facility:</strong>
                    <p class="text-ink-600">{{ $fees['policies']['installment_terms'] }}</p>
                </div>
                <div>
                    <strong class="text-ink-900 block mb-0.5">Published Refund Policy:</strong>
                    <p class="text-ink-600">{{ $fees['policies']['refund_policy'] }}</p>
                </div>
            </div>
        </div>

        <!-- Edit Tuition Modal -->
        <div 
            x-show="editFeeModal" 
            x-cloak 
            class="fixed inset-0 z-50 overflow-y-auto bg-ink-950/40 backdrop-blur-xs flex items-center justify-center p-4"
        >
            <div class="bg-surface rounded-2xl border border-border-default p-4 sm:p-6 max-w-md w-full shadow-elevated space-y-5" @click.outside="editFeeModal = false">
                <div class="flex items-center justify-between pb-3 border-b border-border-subtle">
                    <h3 class="font-heading font-bold text-base text-ink-950">Update Category Tuition Rate</h3>
                    <button type="button" @click="editFeeModal = false" class="text-ink-400 hover:text-ink-700 text-sm">✕</button>
                </div>

                <form action="{{ route('uni_admin.fees.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="category_index" :value="activeCatIndex" />

                    <div>
                        <span class="text-xs sm:text-[10px] font-mono uppercase text-ink-600 block mb-1">Academic Category</span>
                        <div class="font-bold text-xs text-ink-900" x-text="categoryName"></div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-ink-800 mb-1">New Semester Tuition Fee (PKR)</label>
                        <input 
                            type="number" 
                            name="tuition" 
                            x-model="currentTuition" 
                            min="10000" 
                            step="1000" 
                            required 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-border-default text-xs font-mono"
                        />
                    </div>

                    <div class="pt-3 border-t border-border-subtle flex items-center justify-end gap-2">
                        <button type="button" @click="editFeeModal = false" class="px-4 py-2 rounded-xl border border-border-default text-xs font-semibold text-ink-700">Cancel</button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold">Save Fee Revision</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.university-admin>

