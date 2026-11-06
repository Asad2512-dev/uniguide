<?php

namespace App\Services;

use App\Models\Campus;
use App\Models\Program;
use App\Models\Scholarship;
use App\Models\University;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RegionalStudentCatalog
{
    public function programs(): Collection
    {
        return Program::with(['university', 'campus'])->whereHas('campus')->whereHas('university')->orderBy('name')->get();
    }

    /** @return array<int, array<string, mixed>> */
    public function suggestions(): array
    {
        return $this->programs()->map(fn (Program $program): array => [
            'university' => $program->university->name,
            'acronym' => $program->university->acronym ?? 'UNI',
            'program' => $program->name,
            'campus' => $program->campus->name,
            'campus_id' => $program->campus_id,
            'program_id' => $program->id,
            'fee_semester' => 'Not verified',
            'fee_numeric' => 0,
            'calculated_aggregate' => 0,
            'closing_cutoff_2025' => 'Not verified',
            'diff' => 0,
            'accreditation' => 'Check official program evidence',
            'deadline' => 'Not verified',
            'slug' => $program->university->slug,
            'program_slug' => $program->slug,
            'rationale' => 'An offering recorded at an eligible campus. Admission eligibility requires current verified requirements.',
        ])->all();
    }

    /** @return array<int, array<string, mixed>> */
    public function scholarships(): array
    {
        return Scholarship::query()->orderBy('title')->get()->map(fn (Scholarship $scholarship): array => [
            'name' => $scholarship->title, 'slug' => $scholarship->slug,
            'coverage' => $scholarship->coverage ?? 'Not verified',
            'status' => 'Within platform coverage; personal eligibility requires review',
        ])->all();
    }

    /** @param array<int, array<string, mixed>> $items
     * @return array<int, array<string, mixed>>
     */
    public function activeSaved(array $items, string $type): array
    {
        $model = match ($type) {
            'university' => University::class,
            'program' => Program::class,
            'scholarship' => Scholarship::class,
        };
        $slugs = $model::query()->whereIn('slug', array_column($items, 'slug'))->pluck('slug')->all();
        $campusIds = Campus::query()->pluck('id')->all();
        $regionCatalog = app(RegionCatalog::class);

        return array_values(array_filter($items, function (array $item) use ($slugs, $campusIds, $regionCatalog): bool {
            if (in_array($item['slug'] ?? null, $slugs, true)) {
                return ! isset($item['campus_id']) || in_array((int) $item['campus_id'], $campusIds, true);
            }

            $province = $item['province'] ?? $item['province_label'] ?? null;
            if ($province && $regionCatalog->allowsRegion($province)) {
                return true;
            }

            $city = strtolower($item['city'] ?? '');
            if (in_array($city, ['islamabad', 'rawalpindi', 'lahore', 'faisalabad', 'multan', 'taxila', 'gujranwala', 'sialkot', 'bahawalpur'], true)) {
                return true;
            }

            return false;
        }));
    }

    public function requestsOutsideCoverage(string $text): bool
    {
        if (count(config('uniguide.allowed_regions', [])) !== 2) {
            return false;
        }

        // This only explains unsupported requests; database scopes authorize every retrieved record.
        return preg_match('/\b(karachi|sindh|jamshoro|sukkur|hyderabad|peshawar|khyber pakhtunkhwa|kpk|swabi|topi|abbottabad|quetta|balochistan|balochistan|azad kashmir|azad jammu|mirpur|gilgit|baltistan)\b/iu', Str::lower($text)) === 1;
    }
}
