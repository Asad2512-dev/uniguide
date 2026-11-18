<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\City;
use App\Models\Program;
use App\Models\University;
use App\Services\DiscoveryService;
use Illuminate\Support\Facades\DB;
use Tests\DatabaseFeatureTestCase;

class PerformanceTest extends DatabaseFeatureTestCase
{
    public function test_directory_query_count_does_not_grow_with_the_number_of_results(): void
    {
        $city = City::where('name', 'Multan')->firstOrFail();
        for ($i = 0; $i < 20; $i++) {
            University::create(['slug' => 'performance-'.$i, 'name' => 'Performance '.$i, 'city_id' => $city->id, 'city' => 'Multan', 'province' => 'punjab', 'sector' => 'public']);
        }
        DB::enableQueryLog();
        $results = app(DiscoveryService::class)->getUniversities([], 'name_asc', 50);
        $queries = count(DB::getQueryLog());
        DB::disableQueryLog();
        $this->assertCount(21, $results['data']);
        $this->assertLessThanOrEqual(4, $queries, 'A directory page must not fetch programs once per university.');
    }

    public function test_login_does_not_query_the_university_or_program_catalog(): void
    {
        DB::enableQueryLog();
        $this->get('/login')->assertOk()->assertSee('data-page-progress', false);
        $catalogQueries = array_filter(DB::getQueryLog(), fn ($query) => preg_match('/from ["`](?:programs|universities|campuses)["`]/i', $query['query']));
        DB::disableQueryLog();
        $this->assertSame([], array_values($catalogQueries));
    }

    public function test_program_pagination_filters_and_sorts_before_hydrating_models(): void
    {
        $university = University::where('slug', 'numl')->firstOrFail();
        $campus = Campus::where('university_id', $university->id)->firstOrFail();
        foreach (range(1, 12) as $i) {
            Program::create(['university_id' => $university->id, 'campus_id' => $campus->id,
                'slug' => 'performance-program-'.$i, 'name' => sprintf('Degree %02d', $i),
                'degree_level' => 'undergraduate', 'discipline' => 'computing', 'duration_years' => 4,
                'fee_per_semester' => 'PKR '.number_format($i * 10000)]);
        }
        $hydrated = 0;
        Program::retrieved(function () use (&$hydrated) {
            $hydrated++;
        });
        $results = app(DiscoveryService::class)->getPrograms(['max_fee' => 100000], 'fee_desc', 3, 2);
        $this->assertSame(10, $results['total']);
        $this->assertSame(['Degree 07', 'Degree 06', 'Degree 05'], array_column($results['data'], 'title'));
        $this->assertSame(3, $hydrated, 'Only the requested page should be hydrated.');
    }
}
