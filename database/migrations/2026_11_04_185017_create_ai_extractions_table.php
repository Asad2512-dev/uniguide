<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_extractions', function (Blueprint $table) {
            $table->id();
            $table->string('extraction_code')->unique()->index(); // e.g. ext-1
            $table->foreignId('scraping_job_id')->nullable()->constrained('scraping_jobs')->nullOnDelete();
            $table->string('institution_slug')->index();
            $table->string('university_name');
            $table->string('entity_type');
            $table->string('field_name');
            $table->longText('before_value')->nullable();
            $table->longText('after_value')->nullable();
            $table->decimal('confidence_score', 5, 2)->default(95.0);
            $table->string('status')->default('pending')->index(); // pending, approved, rejected, edited
            $table->string('source_reference')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_extractions');
    }
};
