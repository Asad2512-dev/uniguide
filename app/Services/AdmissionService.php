<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\Scholarship;
use App\Models\University;

class AdmissionService
{
    use PaginatesCatalog;

    public function getAdmissions(array $filters = [], string $sort = 'deadline_asc', int $perPage = 6, int $page = 1): array
    {
        $query = Admission::with(['university', 'campus']);
        if (! empty($filters['q'])) {
            $term = '%'.trim($filters['q']).'%';
            $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhere('entry_test_requirement', 'like', $term)
                ->orWhereHas('university', fn ($u) => $u->where('name', 'like', $term)->orWhere('acronym', 'like', $term))
                ->orWhereHas('campus', fn ($c) => $c->where('city', 'like', $term)));
        }
        foreach (['status', 'session'] as $column) {
            if (! empty($filters[$column]) && $filters[$column] !== 'all') {
                $query->where($column, $filters[$column]);
            }
        }
        if (! empty($filters['province']) && $filters['province'] !== 'all') {
            $catalog = app(RegionCatalog::class);
            $provinces = $catalog->allowsRegion($filters['province']) ? $catalog->provinceSlugs($filters['province']) : [];
            $query->whereHas('campus', fn ($c) => $c->whereIn('province', $provinces));
        }
        if (! empty($filters['degree_level']) && $filters['degree_level'] !== 'all') {
            $query->whereJsonContains('degree_levels', $filters['degree_level']);
        }
        if (! empty($filters['university']) && $filters['university'] !== 'all') {
            $query->whereHas('university', fn ($u) => $u->where('slug', $filters['university']));
        }
        match ($sort) {
            'title_asc' => $query->orderBy('title'),
            'university_asc' => $query->orderBy(University::select('name')->whereColumn('universities.id', 'admissions.university_id')),
            default => $query->orderBy('deadline_date', $sort === 'deadline_desc' ? 'desc' : 'asc'),
        };

        return $this->catalogPage($query->orderBy('id'), $perPage, $page, fn (Admission $a) => $this->presentAdmission($a));
    }

    /**
     * Get admission by slug.
     */
    public function getAdmissionBySlug(string $slug): ?array
    {
        $record = Admission::with(['university', 'campus'])->where('slug', $slug)->first();

        return $record ? $this->presentAdmission($record) : null;
    }

    private function presentAdmission(Admission $record): array
    {
        $data = $record->toArray();
        foreach (['summary', 'eligibility_summary', 'weightage_formula', 'application_fee', 'official_url', 'source_citation', 'verified_at', 'deadline', 'merit_date', 'classes_date', 'session'] as $key) {
            $data[$key] = $data[$key] ?? '';
        }

        return array_replace($data, [
            'university_slug' => $record->university->slug, 'university_name' => $record->university->name, 'university_acronym' => $record->university->acronym ?? '',
            'city' => $record->campus->city, 'province' => app(RegionCatalog::class)->normalizeRegion($record->campus->province), 'province_label' => config('uniguide.region_labels.'.$record->campus->province, $record->campus->province),
            'campuses' => [$record->campus->name], 'entry_test' => $record->entry_test_requirement ?? 'Not recorded', 'deadline_date' => $record->deadline_date?->toDateString(),
            'available_programs' => $record->available_programs ?? [], 'timeline_steps' => [], 'application_steps' => [], 'required_documents' => [],
        ]);
    }

    /**
     * Get calendar events across admission deadlines and milestones.
     */
    public function getCalendarEvents(int $year = 2026, ?int $month = null): array
    {
        return Admission::with('university')->whereYear('deadline_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('deadline_date', $month))->get()->map(fn (Admission $a): array => [
                'id' => $a->id, 'title' => $a->title, 'university' => $a->university->name,
                'university_slug' => $a->university->slug, 'category' => 'Deadline', 'type' => 'deadline',
                'date' => $a->deadline_date->toDateString(), 'day' => $a->deadline_date->format('d'),
                'month_name' => $a->deadline_date->format('F'), 'month_num' => $a->deadline_date->month,
                'year' => $year, 'status' => $a->status, 'time' => 'Confirm with institution', 'admission_slug' => $a->slug,
            ])->all();
    }

    public function getScholarships(array $filters = [], string $sort = 'deadline_asc', int $perPage = 6, int $page = 1): array
    {
        $query = Scholarship::with(['university', 'campus', 'regions']);
        if (! empty($filters['q'])) {
            $term = '%'.trim($filters['q']).'%';
            $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhere('provider', 'like', $term)->orWhere('coverage', 'like', $term)->orWhere('applicable_universities', 'like', $term));
        }
        foreach (['type', 'status'] as $column) {
            if (! empty($filters[$column]) && $filters[$column] !== 'all') {
                $query->where($column, $filters[$column]);
            }
        }
        if ($sort === 'title_asc') {
            $query->orderBy('title');
        } else {
            $query->orderBy('deadline_date', $sort === 'deadline_desc' ? 'desc' : 'asc');
        }

        return $this->catalogPage($query->orderBy('id'), $perPage, $page, fn (Scholarship $s) => $this->presentScholarship($s));
    }

    /**
     * Get single scholarship by slug.
     */
    public function getScholarshipBySlug(string $slug): ?array
    {
        $record = Scholarship::with(['regions', 'university', 'campus'])->where('slug', $slug)->first();

        return $record ? $this->presentScholarship($record) : null;
    }

    private function presentScholarship(Scholarship $record): array
    {
        $data = $record->toArray();
        foreach (['benefits', 'eligibility_criteria', 'selection_process', 'required_documents', 'degree_levels'] as $key) {
            $data[$key] = $data[$key] ?? [];
        }
        foreach (['provider', 'coverage', 'applicable_universities', 'universities_scope', 'type_label', 'beneficiaries', 'deadline', 'official_url', 'source_citation', 'verified_at'] as $key) {
            $data[$key] = $data[$key] ?? '';
        }

        return array_replace($data, ['deadline_date' => $record->deadline_date?->toDateString(), 'target_provinces' => $record->regions->pluck('slug')->all()]);
    }

    /**
     * Get comprehensive Admission Document Checklist.
     */
    public function getDocumentChecklist(): array
    {
        return [
            [
                'id' => 'academic',
                'category' => 'Academic Transcripts & Certificates',
                'description' => 'Mandatory educational certificates verifying eligibility percentages for university enrollment.',
                'icon' => 'graduation-cap',
                'items' => [
                    [
                        'id' => 'matric_transcript',
                        'title' => 'Matriculation / SSC Detailed Marks Certificate (DMC)',
                        'issuer' => 'Relevant BISE Board (e.g. FBISE, BISE Lahore, BISE Karachi)',
                        'attestation' => 'Board Verification & IBCC Attestation for federal universities (NUST/QAU)',
                        'required_copies' => 'Original + 3 Attested Photocopies',
                        'tips' => 'Original diploma certificate required if SSC was completed more than 2 years prior.',
                    ],
                    [
                        'id' => 'fsc_transcript',
                        'title' => 'Intermediate / HSSC Detailed Marks Certificate (DMC) or Hope Certificate',
                        'issuer' => 'Relevant BISE Board or College Principal (for Hope Certificate)',
                        'attestation' => 'Principal stamp with college seal on Hope Certificate',
                        'required_copies' => 'Original + 3 Attested Photocopies',
                        'tips' => 'Awaiting Result students must submit a formal Hope Certificate stating minimum expected percentage.',
                    ],
                    [
                        'id' => 'ibcc_equivalence',
                        'title' => 'IBCC Equivalence Certificate (O-Level & A-Level candidates only)',
                        'issuer' => 'Inter Board Coordination Commission (IBCC Islamabad, Lahore, Karachi, Peshawar)',
                        'attestation' => 'Official embossed QR-coded IBCC Certificate',
                        'required_copies' => 'Original + 2 Attested Copies',
                        'tips' => 'Mandatory for all Cambridge, Edexcel, or foreign high school qualifications. Conversion determines merit percentage.',
                    ],
                    [
                        'id' => 'character_certificate',
                        'title' => 'College / School Character Certificate',
                        'issuer' => 'Last attended institution (School / College Principal)',
                        'attestation' => 'Institutional seal & signature',
                        'required_copies' => 'Original + 2 Photocopies',
                        'tips' => 'Certifies disciplinary standing and enrollment history.',
                    ],
                ],
            ],
            [
                'id' => 'identity',
                'category' => 'Identity & Domicile Credentials',
                'description' => 'State-issued identification establishing citizenship, provincial domicile, and quota rights.',
                'icon' => 'user-check',
                'items' => [
                    [
                        'id' => 'cnic_bform',
                        'title' => 'Candidate CNIC / Smart National Identity Card or NADRA B-Form',
                        'issuer' => 'National Database & Registration Authority (NADRA)',
                        'attestation' => 'Attested by Grade 17+ Gazetted Officer',
                        'required_copies' => '3 Attested Photocopies',
                        'tips' => 'Candidates aged 18+ must possess a valid NADRA CNIC or official token of application.',
                    ],
                    [
                        'id' => 'guardian_cnic',
                        'title' => 'Father / Mother / Guardian CNIC',
                        'issuer' => 'NADRA',
                        'attestation' => 'Attested by Grade 17+ Gazetted Officer',
                        'required_copies' => '2 Attested Photocopies',
                        'tips' => 'In case of deceased father, official NADRA death certificate must be appended.',
                    ],
                    [
                        'id' => 'domicile_certificate',
                        'title' => 'District Domicile Certificate & Permanent Residence Certificate (PRC Form-C)',
                        'issuer' => 'Deputy Commissioner (DC) Office / Assistant Commissioner Office',
                        'attestation' => 'Attested by District Magistrate / Gazetted Officer',
                        'required_copies' => 'Original + 2 Attested Copies',
                        'tips' => 'Mandatory for provincial quotas (Punjab, Sindh, Balochistan, KPK, Gilgit-Baltistan, AJK). PRC Form-C is specifically required in Sindh.',
                    ],
                    [
                        'id' => 'photographs',
                        'title' => 'Recent Passport-Size Photographs',
                        'issuer' => 'Professional Studio (White / Sky Blue Background)',
                        'attestation' => 'Name & father name written on reverse, attested on back',
                        'required_copies' => '6 to 8 Photographs',
                        'tips' => 'Required for university identity card, registration dossier, and examination admit card.',
                    ],
                ],
            ],
            [
                'id' => 'testing',
                'category' => 'Standardized Entry Test Documents',
                'description' => 'Official scorecards and test admit cards required to compute final weighted merit rankings.',
                'icon' => 'calculator',
                'items' => [
                    [
                        'id' => 'test_admit_slip',
                        'title' => 'University Entry Test Roll Number Slip / Admit Card',
                        'issuer' => 'University Admissions Directorate / Testing Agency (NTS, ETEA, PMDC)',
                        'attestation' => 'Original Printed Slip with Photo verification',
                        'required_copies' => '1 Printed Copy',
                        'tips' => 'Must be brought to the test center alongside original CNIC/B-Form. Kept safely for merit verification.',
                    ],
                    [
                        'id' => 'test_result_card',
                        'title' => 'Official Entry Test Scorecard / Result Printout',
                        'issuer' => 'Testing Agency (NET, NAT, MDCAT, ECAT, LAT, GIKI Test)',
                        'attestation' => 'QR verification / Official stamped printout',
                        'required_copies' => '2 Copies',
                        'tips' => 'Required during physical document submission and interview scrutiny.',
                    ],
                ],
            ],
            [
                'id' => 'financial',
                'category' => 'Financial Aid & Scholarship Proofs',
                'description' => 'Verifiable household financial documentation required when applying for Need-Based tuition assistance or Ehsaas/HEC grants.',
                'icon' => 'receipt',
                'items' => [
                    [
                        'id' => 'salary_slip',
                        'title' => 'Father / Guardian Salary Slip or Income Affidavit',
                        'issuer' => 'Employer (for salaried) or Revenue Officer / Tehsildar on Stamp Paper (for non-salaried)',
                        'attestation' => 'Company seal / Notary Public Stamp on PKR 100 Stamp Paper',
                        'required_copies' => 'Original + 1 Copy',
                        'tips' => 'For business or agriculture, gross income, land ownership, and tax returns (if filed) must be itemized.',
                    ],
                    [
                        'id' => 'utility_bills',
                        'title' => 'Past 6 Months Utility Bills (Electricity, Gas, Water)',
                        'issuer' => 'WAPDA/IESCO/LESCO/K-Electric & SNGPL/SSGC',
                        'attestation' => 'Original paid vouchers or digital receipts',
                        'required_copies' => '1 Copy of each month',
                        'tips' => 'Meter reference numbers must correspond to the family residence address declared on the admission form.',
                    ],
                    [
                        'id' => 'rent_agreement',
                        'title' => 'Tenancy / Rent Agreement (if applicable)',
                        'issuer' => 'Landlord and Tenant on Legal Stamp Paper',
                        'attestation' => 'Signed by witnesses and Notary Public',
                        'required_copies' => '1 Copy',
                        'tips' => 'Confirms rented residence status to assess monthly living expenses.',
                    ],
                ],
            ],
        ];
    }

    /**
     * Filter options for dropdowns.
     */
    public function getFilterOptions(): array
    {
        return [
            'provinces' => ['all' => 'All covered regions'] + app(RegionCatalog::class)->regions(),
            'statuses' => [
                'all' => 'All Statuses',
                'open' => 'Admissions Open',
                'closing_soon' => 'Closing Soon',
                'upcoming' => 'Admissions Upcoming',
                'merit_announced' => 'Merit Announced',
                'closed' => 'Admissions Closed',
                'unverified' => 'Pending Verification',
            ],
            'degree_levels' => [
                'all' => 'All Degree Levels',
                'bs' => 'Undergraduate (BS / BE / BBA)',
                'ms' => 'Postgraduate (MS / MPhil / MBA)',
                'phd' => 'Doctoral (PhD)',
            ],
            'sessions' => [
                'all' => 'All Academic Sessions',
                'Fall 2026' => 'Fall 2026 Intake',
                'Spring 2027' => 'Spring 2027 Intake',
            ],
            'scholarship_types' => [
                'all' => 'All Scholarship Types',
                'need_based' => 'Need-Based Financial Aid',
                'national_endowment' => 'Poverty Alleviation & Ehsaas',
                'provincial_endowment' => 'Provincial Endowment Trusts (PEEF / BEEF)',
                'institutional_fully_funded' => 'Fully Funded University Fellowships',
                'merit_need' => 'Talent Hunt Programs',
            ],
        ];
    }
}
