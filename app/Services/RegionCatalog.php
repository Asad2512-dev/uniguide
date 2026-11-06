<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\Campus;
use App\Models\City;
use App\Models\Program;
use App\Models\Scholarship;
use App\Models\University;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RegionCatalog
{
    public function normalizeRegion(?string $region): ?string
    {
        $slug = Str::slug(trim($region ?? ''));
        $slug = config('uniguide.region_aliases.'.$slug, $slug);

        return array_key_exists($slug, config('uniguide.region_labels', [])) ? $slug : null;
    }

    public function allowsRegion(?string $region): bool
    {
        return in_array($this->normalizeRegion($region), config('uniguide.allowed_regions', []), true);
    }

    public function regions(): array
    {
        return array_intersect_key(config('uniguide.region_labels', []), array_flip(config('uniguide.allowed_regions', [])));
    }

    public function provinceSlugs(?string $region = null): array
    {
        $allowed = $region ? [$this->normalizeRegion($region)] : array_keys($this->regions());
        $aliases = array_keys(array_filter(config('uniguide.region_aliases', []), fn ($canonical) => in_array($canonical, $allowed, true)));

        return array_values(array_unique(array_merge(array_filter($allowed), $aliases)));
    }

    public function cities(): array
    {
        return City::whereHas('province', fn (Builder $q) => $q->whereIn('slug', $this->provinceSlugs()))
            ->orderBy('name')->pluck('name', 'name')->all();
    }

    public function universities(): Builder
    {
        return University::query();
    }

    public function campuses(): Builder
    {
        return Campus::query();
    }

    public function programs(): Builder
    {
        return Program::query();
    }

    public function admissions(): Builder
    {
        return Admission::query();
    }

    public function scholarships(): Builder
    {
        return Scholarship::query();
    }

    public function contains(Model|string $type, int $id): bool
    {
        $class = $type instanceof Model ? $type::class : match ($type) {
            'university' => University::class, 'campus' => Campus::class, 'program' => Program::class,
            'admission' => Admission::class, 'scholarship' => Scholarship::class, default => $type,
        };

        return is_subclass_of($class, Model::class) && $class::query()->whereKey($id)->exists();
    }

    public function apply(Builder $query, Model $model): void
    {
        $table = $model->getTable();
        match ($table) {
            'universities' => $query->where(function (Builder $q): void {
                $q->whereHas('location.province', fn (Builder $p) => $p->whereIn('slug', $this->provinceSlugs()))
                    ->orWhereHas('campuses', fn (Builder $c) => $c->where('verification_status', 'verified'));
            }),
            'campuses' => $query->whereHas('location.province', fn (Builder $p) => $p->whereIn('slug', $this->provinceSlugs()))
                ->whereNotIn('verification_status', ['rejected', 'conflicting_information'])
                ->whereNotIn('operational_status', ['closed', 'inactive'])
                ->where(function (Builder $c): void {
                    $c->where('verification_status', 'verified')->orWhereExists(function ($q): void {
                        $q->selectRaw('1')->from('universities as campus_parent')
                            ->join('cities as parent_city', 'parent_city.id', '=', 'campus_parent.city_id')
                            ->join('provinces as parent_region', 'parent_region.id', '=', 'parent_city.province_id')
                            ->whereColumn('campus_parent.id', 'campuses.university_id')->whereNull('campus_parent.deleted_at')
                            ->whereIn('parent_region.slug', $this->provinceSlugs());
                    });
                }),
            'programs', 'admissions', 'departments' => $query->whereHas('campus', fn (Builder $c) => $c->whereColumn('campuses.university_id', $table.'.university_id'))->whereHas('university'),
            'scholarships' => $query->where('coverage_status', 'verified')->whereHas('regions', fn (Builder $p) => $p->whereIn('slug', $this->provinceSlugs()))
                ->where(function (Builder $q): void {
                    $q->where(function (Builder $q): void {
                        $q->whereNotNull('campus_id')->whereHas('campus', fn (Builder $c) => $c->where(function (Builder $c): void {
                            $c->whereColumn('campuses.university_id', 'scholarships.university_id')->orWhereNull('scholarships.university_id');
                        }));
                    })->orWhere(function (Builder $q): void {
                        $q->whereNull('campus_id')->where(function (Builder $q): void {
                            $q->whereNull('university_id')->orWhereHas('university.location.province', fn (Builder $p) => $p->whereIn('slug', $this->provinceSlugs()));
                        });
                    });
                }),
            'data_sources', 'knowledge_base_documents' => $query->where('coverage_status', 'verified')->whereHas('campus', fn (Builder $c) => $c->where('verification_status', 'verified')->whereColumn('campuses.university_id', $table.'.university_id')),
            'knowledge_base_chunks' => $query->whereHas('document'),
            'university_source_records' => $query->where(function (Builder $q): void {
                $q->whereHas('campus')->orWhere(function (Builder $q): void {
                    $q->whereNull('campus_id')->whereHas('university');
                });
            }),
            default => null,
        };
        if ($table === 'admissions') {
            $query->where('coverage_status', 'verified');
        }
    }
}
