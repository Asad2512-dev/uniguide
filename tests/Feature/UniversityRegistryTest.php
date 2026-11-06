<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\University;
use App\Models\UniversitySourceRecord;
use App\Services\UniversityImport\HecUniversityImporter;
use App\Services\UniversityImport\VerifiedSnapshotImporter;
use Database\Seeders\NumlUniversitySeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UniversityRegistryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Artisan::call('migrate', ['--database' => 'sqlite', '--force' => true]);
    }

    protected function tearDown(): void
    {
        config(['database.connections.sqlite.database' => database_path('database.sqlite')]);
        DB::purge('sqlite');
        parent::tearDown();
    }

    public function test_numl_seeding_preserves_ten_distinct_campuses_and_provenance_on_repeat_runs(): void
    {
        $this->seed(NumlUniversitySeeder::class);
        $ids = Campus::orderBy('id')->pluck('id')->all();
        $sources = UniversitySourceRecord::count();

        $this->seed(NumlUniversitySeeder::class);

        $this->assertSame(1, University::count());
        $this->assertSame(5, Campus::count());
        $this->assertSame($ids, Campus::orderBy('id')->pluck('id')->all());
        $this->assertSame($sources, UniversitySourceRecord::count());
        $this->assertSame(1, Campus::where('is_main_campus', true)->count());
        $this->assertNull(Campus::where('slug', 'mirpurajk-campus')->first());
        $rawalpindi = Campus::where('slug', 'rawalpindi-campus')->firstOrFail();
        $this->assertSame('numl', $rawalpindi->university->slug);
        $this->assertSame('punjab', $rawalpindi->location->province->slug);
        $this->assertSame('hec_listed', $rawalpindi->recognition_status);
        $this->assertSame('verified', $rawalpindi->verification_status);
        $this->assertSame('2026-11-04T19:54:40+00:00', $rawalpindi->last_verified_at->toIso8601String());
        $this->assertGreaterThan(0, $rawalpindi->sourceRecords()->count());
        $this->assertNull($rawalpindi->address);
        $this->assertSame('unknown', $rawalpindi->operational_status);
    }

    public function test_reseeding_does_not_overwrite_manually_verified_campus_data(): void
    {
        $this->seed(NumlUniversitySeeder::class);
        $campus = Campus::where('slug', 'lahore-campus')->firstOrFail();
        $campus->update(['address' => 'Manually reviewed address']);

        $this->seed(NumlUniversitySeeder::class);

        $this->assertSame('Manually reviewed address', $campus->fresh()->address);
    }

    public function test_national_import_is_idempotent_and_does_not_claim_complete_campus_coverage(): void
    {
        $importer = app(VerifiedSnapshotImporter::class);
        $importer->importNuml();
        $importer->importNational();

        $result = $importer->importNational();

        $totalAllowed = University::count();
        $this->assertGreaterThan(50, $totalAllowed);
        $this->assertSame(0, $result['created']);
        $this->assertSame($totalAllowed, $result['matched']);
        $this->assertSame($totalAllowed - 1, University::where('campus_coverage', 'pending_review')->count());
        $this->assertSame(5, Campus::count());
        $this->assertSame(1, University::where('verification_status', 'verified')->count());
    }

    public function test_numl_directory_profile_campus_detail_and_campus_location_filters_render(): void
    {
        $this->seed(NumlUniversitySeeder::class);

        $this->get('/universities?q=NUML')->assertSee('National University of Modern Languages');
        $this->get('/universities?city=multan&province=punjab&sector=public')->assertSee('National University of Modern Languages');
        $this->get('/universities?province=ajk')->assertDontSee('NUML Islamabad Main Campus');
        $this->get('/universities?sector=private')->assertDontSee('NUML Islamabad Main Campus');
        $this->get(route('universities.show', 'numl'))->assertOk()->assertSee('NUML Rawalpindi Campus');
        $this->get(route('universities.campuses.show', ['slug' => 'numl', 'campusSlug' => 'rawalpindi-campus']))
            ->assertOk()->assertSee('NUML Rawalpindi Campus')->assertSee('unknown');
        $this->get('/search?q=NUML+Rawalpindi+Campus')->assertSee('National University of Modern Languages');
        $this->get(route('universities.campuses.show', ['slug' => 'numl', 'campusSlug' => 'mirpur-campus']))->assertNotFound();
    }

    public function test_national_first_then_numl_enriches_missing_fields_without_duplicate_institutions(): void
    {
        $importer = app(VerifiedSnapshotImporter::class);
        $importer->importNational();
        $numlId = University::where('slug', 'numl')->value('id');

        $importer->importNuml();

        $university = University::where('slug', 'numl')->firstOrFail();
        $this->assertSame($numlId, $university->id);
        $this->assertSame('verified', $university->verification_status);
        $this->assertSame('https://www.numl.edu.pk/', $university->website);
        $this->assertSame(5, $university->campuses()->count());
    }

    public function test_national_profile_with_unknown_fields_renders_without_fabricated_fees(): void
    {
        app(VerifiedSnapshotImporter::class)->importNational();
        $university = University::where('province', 'punjab')->firstOrFail();

        $this->get(route('universities.show', $university->slug))->assertOk()->assertSee('Not verified');
    }

    public function test_campus_under_a_different_institution_returns_404(): void
    {
        $importer = app(VerifiedSnapshotImporter::class);
        $importer->importNuml();
        $importer->importNational();
        $other = University::where('slug', '!=', 'numl')->firstOrFail();

        $this->get(route('universities.campuses.show', ['slug' => $other->slug, 'campusSlug' => 'rawalpindi-campus']))->assertNotFound();
    }

    public function test_snapshot_parser_extracts_registry_fields_without_live_network_requests(): void
    {
        Http::preventStrayRequests();
        Http::fake([
            HecUniversityImporter::URL => Http::response(
                '<li data-position="Example University"><ul><li Sector="Public" City="Islamabad" Province="Islamabad Capital Territory" CharteredBy="Government of Pakistan" Discipline="General"></li></ul><a class="read-more-arrow" href="https://www.hec.gov.pk/english/example.aspx"></a></li>'
            ),
        ]);

        $snapshot = app(HecUniversityImporter::class)->fetchSnapshot();

        $this->assertSame('Example University', $snapshot['universities'][0]['name']);
        $this->assertSame('Public', $snapshot['universities'][0]['sector']);
        $this->assertSame('Islamabad', $snapshot['universities'][0]['city']);
    }

    public function test_snapshot_parser_rejects_a_challenge_or_changed_page_instead_of_importing_empty_data(): void
    {
        $this->expectException(\RuntimeException::class);

        app(HecUniversityImporter::class)->parse('<html>Access restricted</html>');
    }

    public function test_existing_verified_city_is_preserved_and_a_conflict_is_recorded(): void
    {
        $importer = app(VerifiedSnapshotImporter::class);
        $importer->importNational();
        $university = University::where('province', 'punjab')->firstOrFail();
        $university->update(['city' => 'Taxila', 'verification_status' => 'verified']);

        $importer->importNational();

        $this->assertSame('Taxila', $university->fresh()->city);
        $this->assertSame('verified', $university->fresh()->verification_status);
        $this->assertTrue($university->sourceRecords()->where('verification_status', 'conflicting_information')->exists());
    }

    public function test_invalid_campus_url_rejects_the_batch_without_writing_records(): void
    {
        $snapshot = app(VerifiedSnapshotImporter::class)->snapshot('numl');
        $snapshot['campuses'][0]['website'] = 'javascript:alert(1)';
        $importer = $this->partialMock(VerifiedSnapshotImporter::class, function ($mock) use ($snapshot): void {
            $mock->shouldReceive('snapshot')->once()->with('numl')->andReturn($snapshot);
        });

        try {
            $importer->importNuml();
            $this->fail('An invalid URL must reject the batch.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('website', $exception->errors());
        }

        $this->assertSame(0, University::count());
        $this->assertSame(0, Campus::count());
    }

    public function test_missing_province_rejects_the_batch_without_guessing_a_location(): void
    {
        $snapshot = app(VerifiedSnapshotImporter::class)->snapshot('numl');
        $snapshot['campuses'][0]['province'] = '';
        $importer = $this->partialMock(VerifiedSnapshotImporter::class, function ($mock) use ($snapshot): void {
            $mock->shouldReceive('snapshot')->once()->with('numl')->andReturn($snapshot);
        });

        try {
            $importer->importNuml();
            $this->fail('A missing province must reject the batch.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('province', $exception->errors());
        }

        $this->assertSame(0, Campus::count());
    }
}
