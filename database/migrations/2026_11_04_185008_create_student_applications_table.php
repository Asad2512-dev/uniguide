<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->string('university_name');
            $table->string('university_acronym')->nullable();
            $table->string('university_slug')->nullable()->index();
            $table->string('program_name');
            $table->string('campus')->nullable();
            $table->string('stage')->default('interested')->index(); // interested, preparing, applied, test_scheduled, awaiting_result, offer_received, closed
            $table->string('stage_label')->nullable();
            $table->string('application_ref')->nullable()->index();
            $table->string('deadline')->nullable();
            $table->date('deadline_date')->nullable();
            $table->string('fee_challan_status')->nullable();
            $table->string('test_date')->nullable();
            $table->decimal('calculated_aggregate', 5, 2)->nullable();
            $table->string('closing_cutoff_ref')->nullable();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_applications');
    }
};
