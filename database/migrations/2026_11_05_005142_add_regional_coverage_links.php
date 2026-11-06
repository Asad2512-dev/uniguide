<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['admissions', 'scholarships', 'data_sources', 'knowledge_base_documents'] as $name) {
            Schema::table($name, function (Blueprint $table) use ($name): void {
                $table->foreignId('campus_id')->nullable()->constrained('campuses')->restrictOnDelete();
                $table->string('coverage_status')->default('pending_review')->index();
                if (in_array($name, ['data_sources', 'knowledge_base_documents'], true)) {
                    $table->foreignId('university_id')->nullable()->constrained('universities')->restrictOnDelete();
                }
            });
        }
        Schema::create('scholarship_regions', function (Blueprint $table): void {
            $table->foreignId('scholarship_id')->constrained()->cascadeOnDelete();
            $table->foreignId('province_id')->constrained()->restrictOnDelete();
            $table->primary(['scholarship_id', 'province_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_regions');
        foreach (['admissions', 'scholarships', 'data_sources', 'knowledge_base_documents'] as $name) {
            Schema::table($name, function (Blueprint $table) use ($name): void {
                $table->dropConstrainedForeignId('campus_id');
                $table->dropColumn('coverage_status');
                if (in_array($name, ['data_sources', 'knowledge_base_documents'], true)) {
                    $table->dropConstrainedForeignId('university_id');
                }
            });
        }
    }
};
