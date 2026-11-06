<?php

namespace Tests\Feature;

use App\Models\Admission;
use App\Models\Campus;
use App\Models\City;
use App\Models\KnowledgeBaseChunk;
use App\Models\KnowledgeBaseDocument;
use App\Models\Program;
use App\Models\Province;
use App\Models\SavedItem;
use App\Models\Scholarship;
use App\Models\University;
use App\Models\User;
use App\Services\AdmissionService;
use App\Services\DiscoveryService;
use App\Services\EligibilityRulesEngine;
use App\Services\RegionCatalog;
use App\Services\RegistryCounselorResponder;
use App\Services\StudentService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegionalCatalogTest extends TestCase
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

    private function city(string $region): City
    {
        $province = Province::firstOrCreate(['slug' => $region], ['name' => $region]);
        $name = match ($region) {
            'punjab' => 'Rawalpindi', 'ict' => 'Islamabad', default => 'Karachi'
        };

        return City::firstOrCreate(['province_id' => $province->id, 'slug' => strtolower($name)], ['name' => $name]);
    }

    private function university(string $slug, string $region): University
    {
        $city = $this->city($region);

        return University::create(['slug' => $slug, 'name' => ucfirst($slug).' University', 'acronym' => strtoupper($slug), 'city_id' => $city->id, 'city' => $city->name, 'province' => $region]);
    }

    private function campus(University $university, string $region, string $status = 'verified'): Campus
    {
        $city = $this->city($region);

        return Campus::create(['university_id' => $university->id, 'slug' => $university->slug.'-'.$region, 'name' => $city->name.' Campus', 'city_id' => $city->id, 'city' => $city->name, 'province' => $region, 'verification_status' => $status, 'operational_status' => 'officially_listed']);
    }

    private function program(Campus $campus): Program
    {
        return Program::create(['university_id' => $campus->university_id, 'campus_id' => $campus->id, 'slug' => $campus->slug.'-cs', 'name' => 'Computer Science at '.$campus->name, 'discipline' => 'computing', 'degree_level' => 'bs']);
    }

    public function test_normalized_region_ids_allow_aliases_without_merging_rawalpindi_and_islamabad(): void
    {
        $catalog = app(RegionCatalog::class);
        foreach (['Islamabad', 'ICT', 'Federal Capital', 'Islamabad Capital Territory', 'Islamabad (ICT)'] as $alias) {
            $this->assertSame('islamabad-capital-territory', $catalog->normalizeRegion($alias));
        }
        $this->assertFalse($catalog->allowsRegion('Rawalpindi'));
        $this->assertNotSame($this->city('punjab')->province_id, $this->city('ict')->province_id);
    }

    public function test_allowed_universities_appear_and_excluded_details_return_404(): void
    {
        $this->university('punjab-fixture', 'punjab');
        $this->university('ict-fixture', 'ict');
        $excluded = $this->university('sindh-fixture', 'sindh');
        $this->assertSame(2, University::count());
        $this->assertSame(3, University::withoutGlobalScope('regional_catalog')->count());
        $this->get('/universities/'.$excluded->slug)->assertNotFound();
        $this->get('/universities?province=sindh')->assertOk()->assertViewHas('results', fn ($r) => $r['total'] === 0);
    }

    public function test_outside_headquarters_requires_verified_allowed_campus(): void
    {
        $outside = $this->university('outside', 'sindh');
        $campus = $this->campus($outside, 'punjab', 'pending_verification');
        $this->assertSame(0, University::count());
        $this->assertSame(0, Campus::count());
        $campus->update(['verification_status' => 'verified']);
        $this->assertSame([$outside->id], University::pluck('id')->all());
        $this->assertSame([$campus->id], Campus::pluck('id')->all());
    }

    public function test_allowed_headquarters_does_not_expose_excluded_campuses_or_offerings(): void
    {
        $uni = $this->university('mixed', 'punjab');
        $allowed = $this->campus($uni, 'ict');
        $excluded = $this->campus($uni, 'sindh');
        $program = $this->program($allowed);
        $outside = $this->program($excluded);
        $profile = app(DiscoveryService::class)->getUniversityBySlug('mixed');
        $this->assertSame([$allowed->id], array_column($profile['campuses'], 'id'));
        $this->assertSame([$program->id], Program::pluck('id')->all());
        $this->assertNull(app(DiscoveryService::class)->getProgramBySlug($outside->slug));
        $this->assertSame(0, app(DiscoveryService::class)->getPrograms(['q' => 'Karachi'])['total']);
        $this->get('/universities/mixed/campuses/'.$excluded->slug)->assertNotFound();
    }

    public function test_mismatched_program_university_and_campus_fail_closed(): void
    {
        $first = $this->university('first', 'punjab');
        $second = $this->university('second', 'ict');
        $program = $this->program($this->campus($first, 'punjab'));
        $program->update(['university_id' => $second->id]);
        $this->assertSame(0, Program::count());
    }

    public function test_admission_requires_verified_explicit_allowed_campus(): void
    {
        $uni = $this->university('admissions', 'punjab');
        $campus = $this->campus($uni, 'punjab');
        $out = $this->campus($uni, 'sindh');
        $base = ['university_id' => $uni->id, 'title' => 'Admission fixture', 'session' => 'Fall 2026', 'coverage_status' => 'verified'];
        Admission::create($base + ['slug' => 'allowed', 'campus_id' => $campus->id]);
        Admission::create($base + ['slug' => 'excluded', 'campus_id' => $out->id]);
        Admission::create($base + ['slug' => 'ambiguous', 'campuses' => ['Islamabad', 'Karachi']]);
        $this->assertSame(['allowed'], Admission::pluck('slug')->all());
        $this->assertNull(app(AdmissionService::class)->getAdmissionBySlug('excluded'));
        $this->assertNull(app(AdmissionService::class)->getAdmissionBySlug('ambiguous'));
    }

    public function test_scholarship_requires_explicit_region_eligibility_and_rejects_excluded_campus(): void
    {
        $uni = $this->university('aid', 'punjab');
        $out = $this->campus($uni, 'sindh');
        $base = ['provider' => 'Fixture', 'type' => 'need_based', 'coverage_status' => 'verified'];
        $allowed = Scholarship::create($base + ['slug' => 'regional-aid', 'title' => 'Regional aid']);
        $allowed->regions()->attach($this->city('punjab')->province_id);
        $excluded = Scholarship::create($base + ['slug' => 'excluded-aid', 'title' => 'Excluded aid', 'campus_id' => $out->id, 'university_id' => $uni->id]);
        $excluded->regions()->attach($this->city('punjab')->province_id);
        Scholarship::create($base + ['slug' => 'unknown-aid', 'title' => 'Unknown aid']);
        $this->assertSame([$allowed->id], Scholarship::pluck('id')->all());
        $this->assertSame(1, app(AdmissionService::class)->getScholarships()['total']);
    }

    public function test_ai_retrieval_returns_only_allowed_campuses_and_explains_karachi(): void
    {
        $uni = $this->university('mixed', 'punjab');
        $this->campus($uni, 'punjab');
        $this->campus($uni, 'sindh');
        $response = app(RegistryCounselorResponder::class)->respond('Show MIXED campuses');
        $this->assertCount(1, $response['inline_cards']);
        $this->assertCount(1, $response['inline_cards'][0]['campuses']);
        $this->assertStringNotContainsString('Karachi', json_encode($response));
        $outside = app(RegistryCounselorResponder::class)->respond('Which university should I choose in Karachi?');
        $this->assertSame([], $outside['inline_cards']);
        $this->assertStringContainsString('outside current coverage', $outside['content']);
    }

    public function test_knowledge_retrieval_excludes_unassigned_and_outside_documents(): void
    {
        $uni = $this->university('knowledge', 'punjab');
        $allowed = $this->campus($uni, 'punjab');
        $excluded = $this->campus($uni, 'sindh');
        foreach ([$allowed, $excluded, null] as $index => $campus) {
            $doc = KnowledgeBaseDocument::create(['document_code' => 'doc-'.$index, 'title' => 'Document '.$index, 'university_id' => $uni->id, 'campus_id' => $campus?->id, 'coverage_status' => 'verified', 'authority' => 'HEC']);
            KnowledgeBaseChunk::create(['document_id' => $doc->id, 'chunk_index' => 0, 'content' => 'Fixture text']);
        }
        $this->assertSame(1, KnowledgeBaseDocument::count());
        $this->assertSame(1, KnowledgeBaseChunk::count());
        $this->assertSame(3, KnowledgeBaseDocument::withoutGlobalScope('regional_catalog')->count());
    }

    public function test_comparison_rejects_excluded_institution(): void
    {
        $this->university('outside', 'sindh');
        $this->get('/compare/results?unis=outside')->assertNotFound();
    }

    public function test_eligibility_only_lists_permitted_actual_offerings_and_does_not_invent_decisions(): void
    {
        $uni = $this->university('eligibility', 'punjab');
        $program = $this->program($this->campus($uni, 'punjab'));
        $this->program($this->campus($uni, 'sindh'));
        $result = app(EligibilityRulesEngine::class)->evaluateEligibility([]);
        $this->assertSame([], $result['results']['eligible']);
        $this->assertSame([$program->id], array_column($result['results']['conditional'], 'program_id'));
    }

    public function test_saved_history_is_preserved_and_excluded_records_are_not_recommended(): void
    {
        $this->actingAs($user = User::factory()->create(['role' => 'student']));
        $uni = $this->university('outside', 'sindh');
        SavedItem::create(['user_id' => $user->id, 'item_type' => 'university', 'item_id' => $uni->id, 'item_slug' => $uni->slug, 'metadata' => ['slug' => $uni->slug, 'name' => $uni->name]]);
        $this->assertSame([], app(StudentService::class)->getSavedUniversities());
        $this->assertSame(1, SavedItem::where('user_id', $user->id)->count());
    }

    public function test_public_empty_catalog_screens_render_and_filters_exclude_other_regions(): void
    {
        $this->city('punjab');
        $this->city('ict');
        $this->city('sindh');
        foreach (['/', '/universities', '/programs', '/admissions', '/scholarships', '/counselor', '/counselor/guided', '/eligibility'] as $path) {
            $this->get($path)->assertOk();
        }
        $this->assertSame(['punjab', 'islamabad-capital-territory'], array_keys(app(RegionCatalog::class)->regions()));
        $this->assertSame(['Islamabad', 'Rawalpindi'], array_values(app(RegionCatalog::class)->cities()));
    }
}
