<?php

namespace Tests\Feature;

use App\Jobs\ProcessWebScrapeJob;
use App\Models\Campus;
use App\Models\City;
use App\Models\DataSource;
use App\Models\Province;
use App\Models\ScrapingJob;
use App\Models\University;
use App\Models\UniversitySourceRecord;
use App\Services\UniversityImport\VerifiedSnapshotImporter;
use Database\Seeders\NumlUniversitySeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RegionalImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:',
            'uniguide.allowed_regions' => ['punjab', 'islamabad-capital-territory']]);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);
    }

    public function test_numl_seeder_imports_only_five_allowed_campuses_and_is_idempotent(): void
    {
        $this->seed(NumlUniversitySeeder::class);
        $ids = Campus::withoutGlobalScope('regional_catalog')->orderBy('id')->pluck('id')->all();
        $evidenceCount = UniversitySourceRecord::count();

        $this->seed(NumlUniversitySeeder::class);

        $this->assertSame(['Faisalabad', 'Islamabad', 'Lahore', 'Multan', 'Rawalpindi'], Campus::orderBy('city')->pluck('city')->all());
        $this->assertSame($ids, Campus::withoutGlobalScope('regional_catalog')->orderBy('id')->pluck('id')->all());
        $this->assertSame($evidenceCount, UniversitySourceRecord::count());
        $this->assertSame('punjab', Campus::where('city', 'Rawalpindi')->firstOrFail()->location->province->slug);
        $this->assertSame('islamabad-capital-territory', Campus::where('city', 'Islamabad')->firstOrFail()->location->province->slug);
    }

    public function test_national_snapshot_import_skips_outside_headquarters_without_eligible_campuses(): void
    {
        $importer = app(VerifiedSnapshotImporter::class);
        $result = $importer->importNational();
        $repeat = $importer->importNational();

        $this->assertGreaterThan(0, $result['excluded']);
        $this->assertSame(0, $repeat['created']);
        $this->assertSame($result['created'], $repeat['matched']);
        $this->assertFalse(University::withoutGlobalScope('regional_catalog')->where('name', 'Abasyn University')->exists());
        $this->assertTrue(University::where('name', 'National University of Modern Languages')->exists());
    }

    public function test_national_import_retains_an_outside_headquarters_with_a_verified_allowed_campus(): void
    {
        $university = University::create(['slug' => 'abasyn-example', 'name' => 'Abasyn University', 'province' => 'kpk', 'city' => 'Peshawar', 'sector' => 'private']);
        $province = Province::create(['name' => 'Punjab', 'slug' => 'punjab']);
        $city = City::create(['province_id' => $province->id, 'name' => 'Lahore', 'slug' => 'lahore']);
        Campus::create(['university_id' => $university->id, 'slug' => 'fixture-campus', 'name' => 'Test campus', 'city_id' => $city->id,
            'city' => 'Lahore', 'province' => 'punjab', 'verification_status' => 'verified', 'operational_status' => 'officially_listed']);

        app(VerifiedSnapshotImporter::class)->importNational();

        $this->assertSame(1, University::withoutGlobalScope('regional_catalog')->where('name', 'Abasyn University')->count());
        $this->assertNotNull($university->fresh()->registry_key);
    }

    public function test_restricting_imports_does_not_delete_archived_national_campuses(): void
    {
        config(['uniguide.allowed_regions' => ['punjab', 'islamabad-capital-territory', 'sindh', 'khyber-pakhtunkhwa', 'balochistan', 'azad-jammu-and-kashmir', 'gilgit-baltistan']]);
        $this->seed(NumlUniversitySeeder::class);
        $allIds = Campus::withoutGlobalScope('regional_catalog')->orderBy('id')->pluck('id')->all();
        $this->assertCount(10, $allIds);
        config(['uniguide.allowed_regions' => ['punjab', 'islamabad-capital-territory']]);

        $this->seed(NumlUniversitySeeder::class);

        $this->assertSame($allIds, Campus::withoutGlobalScope('regional_catalog')->orderBy('id')->pluck('id')->all());
        $this->assertSame(5, Campus::count());
    }

    public function test_unresolved_scraping_source_cannot_fetch_or_publish_data(): void
    {
        Http::preventStrayRequests();
        DataSource::create(['name' => 'Unresolved national source', 'institution' => 'Example', 'base_url' => 'https://example.edu.pk', 'coverage_status' => 'pending']);

        (new ProcessWebScrapeJob('Unresolved national source'))->handle();

        $job = ScrapingJob::firstOrFail();
        $this->assertSame('blocked_out_of_coverage', $job->status);
        $this->assertSame(0, $job->pages_crawled);
        $this->assertSame(0, $job->records_extracted);
        Http::assertNothingSent();
        $this->assertDatabaseCount('ai_extractions', 0);
    }

    public function test_permitted_source_is_queued_for_review_without_faking_a_crawl(): void
    {
        Http::preventStrayRequests();
        $this->seed(NumlUniversitySeeder::class);
        $campus = Campus::where('slug', 'lahore-campus')->firstOrFail();
        DataSource::create(['name' => 'NUML Lahore source', 'institution' => 'NUML', 'base_url' => 'https://www.numl.edu.pk/lahore',
            'source_url' => 'https://www.numl.edu.pk/lahore', 'university_id' => $campus->university_id, 'campus_id' => $campus->id, 'coverage_status' => 'verified']);

        (new ProcessWebScrapeJob('NUML Lahore source'))->handle();

        $job = ScrapingJob::firstOrFail();
        $this->assertSame('awaiting_manual_review', $job->status);
        $this->assertSame(0, $job->records_extracted);
        Http::assertNothingSent();
    }

    public function test_previously_verified_outside_campus_source_cannot_be_published_after_restriction(): void
    {
        Http::preventStrayRequests();
        config(['uniguide.allowed_regions' => ['punjab', 'islamabad-capital-territory', 'sindh']]);
        $this->seed(NumlUniversitySeeder::class);
        $campus = Campus::where('slug', 'karachi-campus')->firstOrFail();
        DataSource::create(['name' => 'Archived Karachi source', 'institution' => 'NUML', 'base_url' => 'https://www.numl.edu.pk/karachi',
            'university_id' => $campus->university_id, 'campus_id' => $campus->id, 'coverage_status' => 'verified']);
        config(['uniguide.allowed_regions' => ['punjab', 'islamabad-capital-territory']]);

        (new ProcessWebScrapeJob('Archived Karachi source'))->handle();

        $this->assertSame('blocked_out_of_coverage', ScrapingJob::firstOrFail()->status);
        $this->assertDatabaseCount('ai_extractions', 0);
        Http::assertNothingSent();
    }
}
