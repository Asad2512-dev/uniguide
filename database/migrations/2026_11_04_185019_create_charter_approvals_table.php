<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charter_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('approval_code')->unique()->index(); // e.g. appr-1
            $table->string('institution_name');
            $table->string('institution_slug')->nullable()->index();
            $table->string('application_type');
            $table->string('applicant_rector')->nullable();
            $table->longText('details');
            $table->longText('inspection_committee')->nullable();
            $table->string('submission_date')->nullable();
            $table->string('status')->default('pending_review')->index(); // pending_review, approved, rejected
            $table->string('status_label')->nullable();
            $table->timestamp('ratified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charter_approvals');
    }
};
