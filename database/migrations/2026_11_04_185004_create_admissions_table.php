<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('session')->default('Fall 2026')->index();
            $table->string('status')->default('open')->index(); // open, closing_soon, upcoming, closed
            $table->json('degree_levels')->nullable();
            $table->json('disciplines')->nullable();
            $table->json('campuses')->nullable();
            $table->string('registration_start')->nullable();
            $table->string('deadline')->nullable();
            $table->date('deadline_date')->nullable()->index();
            $table->string('test_dates')->nullable();
            $table->string('merit_date')->nullable();
            $table->string('classes_date')->nullable();
            $table->string('entry_test_requirement')->nullable();
            $table->string('application_fee')->nullable();
            $table->string('official_url')->nullable();
            $table->string('source_citation')->nullable();
            $table->string('verified_at')->nullable();
            $table->boolean('featured')->default(false)->index();
            $table->longText('summary')->nullable();
            $table->longText('eligibility_summary')->nullable();
            $table->longText('weightage_formula')->nullable();
            $table->json('available_programs')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
