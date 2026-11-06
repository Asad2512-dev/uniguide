<?php

namespace App\Services;

use App\Models\University;
use Illuminate\Support\Str;

class RegistryCounselorResponder
{
    /** @return array{content: string, citations: array, inline_cards: array, prompt_suggestions: array} */
    public function respond(string $question): array
    {
        if (app(RegionalStudentCatalog::class)->requestsOutsideCoverage($question)) {
            return ['content' => 'This UniGuide release covers Punjab and Islamabad Capital Territory only. The location you requested is outside current coverage. I can help you explore verified campus records in Punjab or Islamabad.', 'citations' => [], 'inline_cards' => [], 'prompt_suggestions' => ['Show NUML Islamabad and Punjab campuses']];
        }
        $text = Str::lower($question);
        $identities = University::query()->get(['id', 'name', 'acronym', 'slug']);
        $ids = $identities->filter(function (University $university) use ($text): bool {
            $names = array_filter([$university->acronym, $university->name, str_replace('-', ' ', $university->slug)]);
            foreach ($names as $name) {
                if (mb_strlen($name) >= 3 && preg_match('/(?<![\pL\pN])'.preg_quote(Str::lower($name), '/').'(?![\pL\pN])/u', $text)) {
                    return true;
                }
            }

            return false;
        })->pluck('id')->take(4);
        $universities = University::with(['campuses' => fn ($q) => $q->where('verification_status', 'verified'), 'sourceRecords.dataSource'])->whereIn('id', $ids)->get();
        $cards = [];
        $sources = [];
        foreach ($universities as $university) {
            $campuses = $university->campuses->filter(fn ($campus): bool => Str::contains($text, Str::lower($campus->city)));
            if ($campuses->isEmpty()) {
                $campuses = $university->campuses;
            }
            $cards[] = [
                'id' => $university->id, 'slug' => $university->slug, 'name' => $university->name,
                'acronym' => $university->acronym, 'city' => $university->campuses->pluck('city')->unique()->implode(', ') ?: $university->city,
                'province' => 'Punjab / Islamabad',
                'sector' => ucfirst($university->sector), 'verification_status' => $university->verification_status,
                'profile_url' => route('universities.show', $university->slug),
                'compare_url' => route('compare.results', ['unis' => $universities->pluck('slug')->implode(',')]),
                'campus_count' => $university->campuses->count(),
                'campuses' => $campuses->map(fn ($campus): array => [
                    'name' => $campus->name, 'city' => $campus->city,
                    'status' => $campus->verification_status, 'operating_status' => $campus->operational_status,
                    'url' => route('universities.campuses.show', ['slug' => $university->slug, 'campusSlug' => $campus->slug]),
                ])->values()->all(),
                'fees' => 'Not verified', 'admissions' => 'Not verified', 'eligibility' => 'Not calculated',
            ];
            foreach ($university->sourceRecords->filter(fn ($evidence): bool => $evidence->campus_id === null || $campuses->contains('id', $evidence->campus_id))->unique('data_source_id')->take(12) as $evidence) {
                $source = $evidence->dataSource;
                $url = $source?->source_url;
                if (! $this->safeUrl($url)) {
                    continue;
                }
                $sources[$url] = [
                    'title' => $source->name, 'university' => $university->name, 'url' => $url,
                    'context' => $evidence->evidence_type, 'status' => $evidence->verification_status,
                    'verified_at' => $source->verified_at?->toIso8601String(),
                    'outdated' => $source->verified_at === null || $source->verified_at->lt(now()->subMonths(6)),
                ];
            }
        }
        if ($cards === []) {
            $content = "## Let’s make your next step clearer\n\nI can look up universities and campuses in Punjab and Islamabad from UniGuide’s database. Name an institution—for example **NUML** or **FAST**—or ask to compare two universities.\n\n### What I can verify\n- Institution and campus records with their recorded sources.\n- The verification status and gaps in those records.\n\n### What needs an official check\nCurrent fees, admission deadlines, program availability and eligibility need current university evidence. I won’t estimate them or make an admission decision.\n\nThis workspace currently uses a registry lookup, not a connected generative AI model.";
        } else {
            $names = $universities->pluck('name')->implode(' and ');
            $content = "## Your university research, grounded in records\n\nI found **{$names}** in UniGuide’s registry. The cards below use those database records; they are research matches, not admission recommendations.\n\n### Read the evidence carefully\nA university’s listing does not verify every campus, program or admission cycle. Each card shows the record’s review status. A campus operating status of **unknown** needs confirmation.\n\n### Your next steps\n1. Open the relevant campus or university profile.\n2. Review the source links and their verification dates.\n3. Confirm current program requirements, fees and deadlines directly with the university.\n\n**Missing information:** verified program-level fees and current admission timelines are not established by these registry sources. Eligibility has not been calculated.\n\nThis is database-backed guidance, not an official university decision or a generative AI answer.";
        }

        return ['content' => $content,  'citations' => array_values($sources), 'inline_cards' => $cards,
            'prompt_suggestions' => ['Show NUML campuses', 'Compare NUML and FAST']];
    }

    public function safeUrl(?string $url): bool
    {
        return is_string($url) && filter_var($url, FILTER_VALIDATE_URL) !== false
            && in_array(strtolower(parse_url($url, PHP_URL_SCHEME) ?? ''), ['https', 'http'], true)
            && parse_url($url, PHP_URL_USER) === null && parse_url($url, PHP_URL_PASS) === null;
    }
}
