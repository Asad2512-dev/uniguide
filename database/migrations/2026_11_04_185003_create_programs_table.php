<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->foreignId('campus_id')->nullable()->constrained('campuses')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('slug')->index();
            $table->string('name');
            $table->string('degree_level')->default('undergraduate')->index(); // undergraduate, graduate, postgraduate, diploma
            $table->string('degree_title')->nullable(); // BS, BE, BBA, MS, PhD
            $table->string('discipline')->index(); // computing, engineering, business, medical, social_sciences, natural_sciences
            $table->unsignedTinyInteger('duration_years')->default(4);
            $table->unsignedTinyInteger('total_semesters')->default(8);
            $table->unsignedSmallInteger('credit_hours')->default(130);
            $table->string('fee_per_semester')->nullable();
            $table->string('annual_fee')->nullable();
            $table->string('cutoff_merit')->nullable();
            $table->string('estimated_closing_merit')->nullable();
            $table->string('accreditation_council')->nullable()->index(); // PEC, NCEAC, NBEAC, PMDC
            $table->string('accreditation_status')->nullable();
            $table->unsignedSmallInteger('seat_capacity')->default(60);
            $table->json('eligibility_criteria')->nullable();
            $table->longText('description')->nullable();
            $table->longText('curriculum_summary')->nullable();
            $table->longText('career_prospects')->nullable();
            $table->boolean('featured')->default(false)->index();
            $table->string('status')->default('active')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['university_id', 'degree_level', 'discipline']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
