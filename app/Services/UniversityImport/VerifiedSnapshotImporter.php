<?php

namespace App\Services\UniversityImport;

use App\Models\Campus;
use App\Models\City;
use App\Models\DataSource;
use App\Models\Province;
use App\Models\University;
use App\Models\UniversitySourceRecord;
use App\Services\RegionCatalog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VerifiedSnapshotImporter
{
    public const PROVINCES = [
        'Punjab' => 'punjab', 'Sindh' => 'sindh', 'Khyber Pakhtunkhwa' => 'kpk',
        'Balochistan' => 'balochistan', 'Islamabad Capital Territory' => 'ict',
        'Azad Jammu & Kashmir' => 'ajk', 'Gilgit Baltistan' => 'gb',
    ];

    /** @return array<string, mixed> */
    public function snapshot(string $name): array
    {
        return json_decode(file_get_contents(database_path('data/'.$name.'.json')), true, flags: JSON_THROW_ON_ERROR);
    }

    public function importNuml(): University
    {
        $snapshot = $this->snapshot('numl');
        $this->validateUniversity($snapshot['university']);
        if (collect($snapshot['campuses'])->where('campus_type', 'main')->count() !== 1) {
            throw ValidationException::withMessages(['campuses' => 'Exactly one documented main campus is required.']);
        }
        $identities = [];
        foreach ($snapshot['campuses'] as $campus) {
            if (isset($identities[$campus['source_id']])) {
                throw ValidationException::withMessages(['campuses' => 'Duplicate campus source identity in snapshot.']);
            }
            $identities[$campus['source_id']] = true;
            Validator::make($campus, [
                'source_id' => ['required', 'string'], 'slug' => ['required', 'string'],
                'name' => ['required', 'string', 'max:255'], 'city' => ['required', 'string'],
                'province' => ['required', Rule::in(array_keys(self::PROVINCES))],
                'campus_type' => ['required', Rule::in(['main', 'regional', 'constituent', 'sub_campus', 'study_center'])],
                'recognition_status' => ['required', Rule::in(['hec_listed', 'pending_verification', 'conflicting_information'])],
                'operational_status' => ['required', Rule::in(['officially_listed', 'unknown', 'closed'])],
                'address' => ['nullable', 'string', 'max:255'],
                'phone' => ['nullable', 'string', 'max:255'],
                'website' => ['nullable', 'url:http,https'], 'email' => ['nullable', 'email'],
                'sources' => ['required', 'array', 'min:1'], 'sources.*' => ['required', 'url:http,https'],
                'verification_status' => ['required', Rule::in(['verified', 'pending_verification', 'conflicting_information', 'outdated', 'rejected'])],
            ])->validate();
            foreach ($campus['sources'] as $url) {
                $this->validateOfficialUrl($url);
            }
        }

        return DB::transaction(function () use ($snapshot): University {
            $university = $this->importUniversity($snapshot['university'], $snapshot);
            foreach ($snapshot['campuses'] as $row) {
                if (! app(RegionCatalog::class)->allowsRegion($row['province'])) {
                    continue;
                }
                $key = hash('sha256', $row['source_id']);
                $campus = Campus::withoutGlobalScope('regional_catalog')->withTrashed()->where('registry_key', $key)->first()
                    ?? Campus::withoutGlobalScope('regional_catalog')->withTrashed()->where('university_id', $university->id)->where('slug', $row['slug'])->first();
                if ($campus && ($campus->trashed() || $campus->university_id !== $university->id)) {
                    throw ValidationException::withMessages(['campus' => 'Existing campus requires manual identity review.']);
                }
                $city = $this->city($row['city'], $row['province']);
                if (! $campus) {
                    $campus = Campus::create([
                        'university_id' => $university->id, 'registry_key' => $key,
                        'name' => $row['name'], 'slug' => $row['slug'], 'city_id' => $city->id,
                        'city' => $city->name, 'province' => app(RegionCatalog::class)->normalizeRegion($row['province']),
                        'address' => $row['address'] ?? null, 'phone' => $row['phone'] ?? null,
                        'email' => $row['email'] ?? null, 'website' => $row['website'],
                        'campus_type' => $row['campus_type'], 'is_main_campus' => $row['campus_type'] === 'main',
                        'status' => 'listed', 'operational_status' => $row['operational_status'],
                        'verification_status' => $row['verification_status'], 'recognition_status' => $row['recognition_status'],
                        'last_verified_at' => Carbon::parse($snapshot['verified_at'])->utc(),
                    ]);
                }
                foreach ($row['sources'] as $url) {
                    $this->evidence($university, $campus, $url, $snapshot, $row, 'campus_listing');
                }
            }

            return $university;
        });
    }

    /** @return array{created: int, matched: int, excluded: int} */
    public function importNational(): array
    {
        $snapshot = $this->snapshot('hec-universities');
        foreach ($snapshot['universities'] as $row) {
            $this->validateUniversity($row);
        }

        return DB::transaction(function () use ($snapshot): array {
            $counts = ['created' => 0, 'matched' => 0, 'excluded' => 0];
            foreach ($snapshot['universities'] as $row) {
                if (! $this->eligibleUniversity($row)) {
                    $counts['excluded']++;

                    continue;
                }
                $university = $this->importUniversity($row, $snapshot);
                $counts[$university->wasRecentlyCreated ? 'created' : 'matched']++;
            }

            return $counts;
        });
    }

    /** @param array<string, mixed> $row */
    private function eligibleUniversity(array $row): bool
    {
        if (app(RegionCatalog::class)->allowsRegion($row['province'])) {
            return true;
        }

        $university = University::withoutGlobalScope('regional_catalog')
            ->where('registry_key', hash('sha256', $row['source_url']))
            ->orWhere('name', $row['name'])->first();

        return $university !== null && Campus::query()->where('university_id', $university->id)
            ->where('verification_status', 'verified')->exists();
    }

    /** @param array<string, mixed> $row */
    private function validateUniversity(array $row): void
    {
        Validator::make($row, [
            'name' => ['required', 'string', 'max:255'], 'city' => ['required', 'string', 'max:255'],
            'province' => ['required', Rule::in(array_keys(self::PROVINCES))],
            'sector' => ['required', Rule::in(['Public', 'Private'])],
            'source_url' => ['required', 'url:https'], 'website' => ['nullable', 'url:http,https'],
        ])->validate();
        $this->validateOfficialUrl($row['source_url']);
    }

    private function validateOfficialUrl(string $url): void
    {
        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
        if (! in_array($host, ['hec.gov.pk', 'www.hec.gov.pk', 'numl.edu.pk', 'www.numl.edu.pk'], true) && ! str_ends_with($host, '.numl.edu.pk')) {
            throw ValidationException::withMessages(['source_url' => 'Source must be an approved official HEC or NUML host.']);
        }
    }

    private function city(string $name, string $province): City
    {
        $slug = app(RegionCatalog::class)->normalizeRegion($province);
        $region = Province::all()->first(fn (Province $candidate): bool => app(RegionCatalog::class)->normalizeRegion($candidate->slug) === $slug)
            ?? Province::firstOrCreate(['slug' => $slug], ['name' => $province]);

        return City::firstOrCreate(['province_id' => $region->id, 'slug' => Str::slug($name)], ['name' => $name]);
    }

    /** @param array<string, mixed> $row
     * @param  array<string, mixed>  $snapshot
     */
    private function importUniversity(array $row, array $snapshot): University
    {
        $key = hash('sha256', $row['source_url']);
        $aliases = [
            'National University of Modern Languages' => 'numl',
            'National University of Computer & Emerging Sciences' => 'fast-nuces',
            'Ghulam Ishaq Khan Institute of Engineering Sciences & Technology' => 'giki',
        ];
        $slug = $aliases[$row['name']] ?? Str::slug($row['name'].' '.$row['city'].' '.self::PROVINCES[$row['province']]);
        $university = University::withoutGlobalScope('regional_catalog')->withTrashed()->where('registry_key', $key)->first()
            ?? University::withoutGlobalScope('regional_catalog')->withTrashed()->where('slug', $slug)->first()
            ?? University::withoutGlobalScope('regional_catalog')->withTrashed()->where('name', $row['name'])->whereIn('province', [self::PROVINCES[$row['province']], app(RegionCatalog::class)->normalizeRegion($row['province'])])->first();
        if ($university?->registry_key && $university->registry_key !== $key) {
            throw ValidationException::withMessages(['university' => 'Conflicting registry identity requires manual review.']);
        }
        if ($university?->trashed()) {
            throw ValidationException::withMessages(['university' => 'Soft-deleted institution requires manual review; it will not be recreated.']);
        }
        $city = $this->city($row['city'], $row['province']);
        $status = $row['verification_status'] ?? 'pending_verification';
        $conflicts = [];
        if ($university) {
            foreach (['city' => $row['city'], 'province' => app(RegionCatalog::class)->normalizeRegion($row['province']), 'sector' => strtolower($row['sector'])] as $field => $value) {
                if (($field === 'province' ? app(RegionCatalog::class)->normalizeRegion($university->{$field}) : $university->{$field}) !== $value) {
                    $conflicts[$field] = ['existing' => $university->{$field}, 'source' => $value];
                }
            }
            if ($conflicts) {
                $status = 'conflicting_information';
            }
            if (! $university->registry_key) {
                $university->update(['registry_key' => $key, 'recognition_status' => 'hec_listed',
                    'verification_status' => $university->verification_status === 'verified' ? 'verified' : $status,
                    'city_id' => $conflicts ? null : $city->id]);
            }
        } else {
            $university = University::create([
                'registry_key' => $key, 'slug' => $row['slug'] ?? $slug,
                'name' => $row['name'], 'acronym' => $row['acronym'] ?? null,
                'city' => $city->name, 'city_id' => $city->id, 'province' => app(RegionCatalog::class)->normalizeRegion($row['province']),
                'province_label' => $row['province'], 'sector' => strtolower($row['sector']),
                'charter_type' => $row['charteredby'] ?? null, 'website' => $row['website'] ?? null,
                'address' => $row['address'] ?? null, 'phone' => $row['phone'] ?? null, 'email' => $row['email'] ?? null,
                'recognition_status' => 'hec_listed', 'verification_status' => $status,
                'institution_status' => $row['institution_status'] ?? 'hec_listed',
                'campus_coverage' => $row['campus_coverage'] ?? 'pending_review',
                'last_verified_at' => $status === 'verified' ? Carbon::parse($snapshot['verified_at'])->utc() : null,
                'status' => 'unknown',
            ]);
        }
        if (($row['verification_status'] ?? null) === 'verified' && $university->verification_status !== 'verified' && $conflicts === []) {
            foreach (['acronym', 'website', 'address', 'phone', 'email'] as $field) {
                if (empty($university->{$field}) && isset($row[$field])) {
                    $university->{$field} = $row[$field];
                }
            }
            $university->verification_status = 'verified';
            $university->last_verified_at = Carbon::parse($snapshot['verified_at'])->utc();
            $university->campus_coverage = $row['campus_coverage'] ?? 'pending_review';
            $university->save();
        }
        $this->evidence($university, null, $snapshot['source_url'], $snapshot, $row + ['conflicts' => $conflicts], 'hec_registry');

        return $university;
    }

    /** @param array<string, mixed> $snapshot
     * @param  array<string, mixed>  $row
     */
    private function evidence(University $university, ?Campus $campus, string $url, array $snapshot, array $row, string $type): void
    {
        $source = DataSource::withoutGlobalScope('regional_catalog')->updateOrCreate(['registry_key' => hash('sha256', $url)], [
            'name' => $type === 'hec_registry' ? 'HEC university reference registry' : 'Official campus evidence',
            'institution' => $type === 'hec_registry' ? 'HEC' : $university->name,
            'base_url' => $url, 'source_url' => $url, 'source_type' => $type,
            'feed_type' => 'Verified Snapshot', 'status' => 'snapshot', 'http_status' => 200,
            'fetched_at' => Carbon::parse($snapshot['verified_at'])->utc(), 'verified_at' => Carbon::parse($snapshot['verified_at'])->utc(),
            'content_sha256' => $snapshot['sources'][$url]['content_sha256'] ?? ($url === $snapshot['source_url'] ? $snapshot['content_sha256'] : null),
        ]);
        UniversitySourceRecord::firstOrCreate([
            'evidence_key' => hash('sha256', $university->id.'|'.($campus?->id ?? 'university').'|'.$url.'|'.$snapshot['verified_at'].'|'.hash('sha256', json_encode($row, JSON_THROW_ON_ERROR))),
        ], [
            'university_id' => $university->id, 'campus_id' => $campus?->id, 'data_source_id' => $source->id,
            'evidence_type' => $type, 'verification_status' => ! empty($row['conflicts']) ? 'conflicting_information' : ($row['verification_status'] ?? 'pending_verification'),
            'evidence' => $row + ['observed_at' => $snapshot['verified_at'], 'snapshot_sha256' => $snapshot['sources'][$url]['content_sha256'] ?? $snapshot['content_sha256']],
        ]);
    }
}
