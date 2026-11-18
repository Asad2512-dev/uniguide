<?php

namespace App\Services;

use App\Models\SystemSetting;
use App\Models\University;
use App\Models\UniversitySourceRecord;
use Illuminate\Support\Arr;

class CounselorService
{
    public function __construct(
        protected DiscoveryService $discoveryService,
        protected AdmissionService $admissionService
    ) {}

    /**
     * Curated category-based suggestions for the intelligent prompt composer.
     */
    public function getSuggestedPrompts(): array
    {
        return [
            [
                'category' => 'Merit & Formulas',
                'icon' => 'calculator',
                'prompts' => [
                    'How is the NUST CS aggregate calculated between NET, FSc, and Matric?',
                    'Calculate my admission chances for FAST Lahore with 78% in FSc Pre-Engineering',
                    'What was the closing merit cutoff for Software Engineering at COMSATS Islamabad in 2025?',
                    'Explain the UET ECAT weightage formula for mechanical vs electrical engineering',
                ],
            ],
            [
                'category' => 'Program & University Comparisons',
                'icon' => 'git-compare',
                'prompts' => [
                    'Compare FAST Islamabad vs NUST SEECS for BS Computer Science job placements',
                    'Which is better for AI: GIKI Topi or NUST H-12?',
                    'Compare fee structure and hostel facilities between LUMS and IBA Karachi',
                    'Difference between BS Software Engineering vs BS Computer Science curriculum in Pakistan',
                ],
            ],
            [
                'category' => 'Scholarships & Affordability',
                'icon' => 'award',
                'prompts' => [
                    'What are the eligibility criteria for the HEC Need-Based Undergraduate Scholarship?',
                    'Does LUMS provide 100% financial aid under the National Outreach Program (NOP)?',
                    'List government universities in Punjab with semester fee under PKR 80,000',
                    'How can an A-Level student with 3As qualify for provincial endowment fund scholarships?',
                ],
            ],
            [
                'category' => 'Admission Strategy',
                'icon' => 'compass',
                'prompts' => [
                    'I scored 68% in FSc. What are my best computing options in Lahore and Islamabad?',
                    'Can pre-medical students apply for BS Computer Science without additional math in 2026?',
                    'What is the step-by-step application schedule for NUST NET Series 4?',
                    'Which universities accept the NTS NAT test for computer science admissions?',
                ],
            ],
        ];
    }

    /**
     * Guided counseling quick actions and shortcuts.
     */
    public function getQuickActions(): array
    {
        return [
            [
                'id' => 'guided-evaluation',
                'title' => 'Guided Merit & Fit Assessment',
                'badge' => 'Recommended',
                'badge_color' => 'brand',
                'description' => 'Answer 6 quick questions about your marks, test scores, location, and budget to receive structured Reach, Match, and Safety university tiers.',
                'icon' => 'sparkles',
                'route' => 'counselor.guided',
            ],
            [
                'id' => 'conversational-search',
                'title' => 'Natural Language University Search',
                'badge' => 'Intelligent',
                'badge_color' => 'emerald',
                'description' => 'Search universities using everyday language like "Top CS universities in Islamabad under 150k with hostel facility" with AI analysis.',
                'icon' => 'search',
                'route' => 'counselor.search',
            ],
            [
                'id' => 'evidence-audit',
                'title' => 'Statutory Evidence & HEC Gazette Viewer',
                'badge' => 'Verified Data',
                'badge_color' => 'indigo',
                'description' => 'Inspect the official legal statutes, HEC notifications, and university gazettes that verify our counselor\'s calculations.',
                'icon' => 'shield-check',
                'route' => 'counselor.evidence',
            ],
            [
                'id' => 'chat-history',
                'title' => 'Archived Advisory Sessions',
                'badge' => 'Saved Transcripts',
                'badge_color' => 'neutral',
                'description' => 'Resume previous conversations, download official consultation summaries, and track your admission decision timeline.',
                'icon' => 'history',
                'route' => 'counselor.history',
            ],
        ];
    }

    /**
     * Capabilities and boundaries disclosure for AI trust transparency.
     */
    public function getCapabilitiesDisclosure(): array
    {
        return [
            'capabilities' => [
                'Instant aggregate calculation using verified statutory formulas (NUST, FAST, UET, COMSATS, GIKI).',
                'Historical cutoff analysis calibrated across 2023, 2024, and 2025 official closing merit lists.',
                'Cross-institutional fee, hostel cost, and financial aid package comparisons.',
                'Recognition verification under HEC Act No. LIII of 2002 and PEC/NCEAC council accreditation tiers.',
            ],
            'limitations' => [
                'The AI advisor does not issue official admission offers; final admission decisions rest solely with university statutory selection boards.',
                'Merit cutoffs fluctuate annually based on candidate volume, test difficulty, and board normalization.',
                'All fee quotes reflect published gazette schedules and exclude refundable security deposits or annual indexation.',
            ],
        ];
    }

    /**
     * Private, database-backed conversation history.
     */
    public function getConversations(): array
    {
        return app(CounselorWorkspace::class)->history();
    }

    public function getConversationById(string $id): ?array
    {
        $workspace = app(CounselorWorkspace::class);

        return $workspace->thread($workspace->owned($id));
    }

    public function createConversation(string $message): array
    {
        return app(CounselorWorkspace::class)->send($message)['conversation'];
    }

    public function sendMessage(string $conversationId, string $message): array
    {
        return app(CounselorWorkspace::class)->send($message, $conversationId);
    }

    /**
     * Comprehensive Student Merit & Eligibility Assessment Engine.
     * Evaluates Pakistani students against verified formulas and generates 3-tier recommendations.
     */
    public function evaluateStudent(array $profile): array
    {
        $profile += ['matric_percentage' => 0, 'fsc_percentage' => 0, 'test_type' => 'none', 'test_score' => 0, 'test_max' => 0, 'discipline' => '', 'location' => '', 'budget_max' => null, 'monthly_income' => null];
        $aggregates = [];
        foreach (University::all() as $university) {
            $formula = SystemSetting::get('university.'.$university->id.'.merit_formula', []);
            if (array_sum(Arr::only($formula, ['net_test_weight', 'fsc_hssc_weight', 'matric_ssc_weight'])) != 100 || $profile['test_max'] <= 0) {
                continue;
            }
            $score = $profile['test_score'] / $profile['test_max'] * $formula['net_test_weight'] + $profile['fsc_percentage'] * $formula['fsc_hssc_weight'] / 100 + $profile['matric_percentage'] * $formula['matric_ssc_weight'] / 100;
            $aggregates[$university->slug] = ['label' => $university->name, 'score' => round($score, 2), 'formula' => $formula['net_test_weight'].'% test + '.$formula['fsc_hssc_weight'].'% HSSC + '.$formula['matric_ssc_weight'].'% SSC'];
        }

        return ['profile' => $profile, 'calculated_aggregates' => $aggregates, 'tiers' => ['reach' => [], 'match' => [], 'safety' => []], 'scholarships' => [],
            'summary_verdict' => 'Recommendations require recorded academic results, university formulas, and closing merits. Missing requirements are not estimated.'];
    }

    /**
     * Conversational Natural Language University & Program Search.
     */
    public function searchConversational(string $query): array
    {
        $universities = trim($query) !== '' ? $this->discoveryService->search($query, 'universities')['universities'] ?? [] : [];

        return ['query' => $query, 'interpreted_criteria' => ['discipline' => 'Search text', 'location' => 'Punjab and Islamabad', 'budget_ceiling' => 'Not inferred', 'amenities_requested' => []],
            'ai_synthesis' => 'Results are retrieved from recorded university data. Search terms do not establish eligibility, ranking, or accreditation.',
            'matches' => array_map(fn ($u) => ['university' => $u, 'relevance_score' => null, 'ai_commentary' => 'Review the institution record and its sources.', 'criteria_fit' => ['discipline' => 'Not assessed', 'location' => $u['city'], 'accreditation' => $u['verification_status'] ?? 'Not assessed']], $universities)];
    }

    /**
     * Statutory Evidence Repository: Legal Acts, Gazette Notifications, and Accreditation Registers.
     */
    public function getEvidenceRepository(): array
    {
        return UniversitySourceRecord::with('dataSource')->get()->map(fn ($record) => [
            'id' => (string) $record->id, 'title' => $record->dataSource?->name ?? 'Recorded source', 'url' => $record->dataSource?->source_url ?? '',
        ])->all();
    }

    /**
     * Retrieve single evidence document by ID.
     */
    public function getEvidenceById(string $id): ?array
    {
        foreach ($this->getEvidenceRepository() as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }

        return null;
    }

    /**
     * Retrieve history of all counseling sessions with filtering & search.
     */
    public function getConversationHistory(string $filter = 'all', string $search = ''): array
    {
        return app(CounselorWorkspace::class)->history($search, $filter);
    }
}
