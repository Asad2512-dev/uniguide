<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('provider')->index(); // HEC, PEEF, LUMS NOP, USAID, Internal
            $table->string('type')->default('need_based')->index();
            $table->string('type_label')->nullable();
            $table->string('category')->default('need_based')->index(); // need_based, merit_based, provincial, international
            $table->string('funding_type')->nullable(); // 100% Tuition, 50% Tuition, Full Ride
            $table->text('coverage')->nullable();
            $table->string('coverage_amount')->nullable();
            $table->string('deadline')->nullable();
            $table->date('deadline_date')->nullable()->index();
            $table->string('beneficiaries')->nullable();
            $table->text('applicable_universities')->nullable();
            $table->string('universities_scope')->nullable();
            $table->json('target_provinces')->nullable();
            $table->json('degree_levels')->nullable();
            $table->string('gpa_requirement')->nullable();
            $table->unsignedInteger('income_ceiling')->nullable();
            $table->longText('description')->nullable();
            $table->json('eligibility_criteria')->nullable();
            $table->json('benefits')->nullable();
            $table->json('selection_process')->nullable();
            $table->json('required_documents')->nullable();
            $table->longText('application_process')->nullable();
            $table->string('official_url')->nullable();
            $table->string('source_citation')->nullable();
            $table->string('verified_at')->nullable();
            $table->string('status')->default('open')->index();
            $table->boolean('featured')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
