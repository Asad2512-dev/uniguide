<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->id();
            $table->string('queue_code')->unique()->index(); // e.g. vq-1
            $table->string('institution_slug')->index();
            $table->string('institution_name');
            $table->string('entity_type');
            $table->string('field_name');
            $table->longText('before_value')->nullable();
            $table->longText('after_value')->nullable();
            $table->string('priority')->default('Medium')->index(); // High, Medium, Low
            $table->string('status')->default('Pending Review')->index(); // Pending Review, Approved, Flagged for Audit
            $table->string('submitted_by')->nullable();
            $table->string('submitted_at_text')->nullable();
            $table->string('source_doc')->nullable();
            $table->decimal('confidence', 5, 2)->default(98.0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_requests');
    }
};
