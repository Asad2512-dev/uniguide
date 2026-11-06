<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('applicant_id')->unique()->index();
            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('domicile_province')->nullable();
            $table->string('domicile_label')->nullable();
            $table->string('cnic')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('stream')->default('pre_engineering');
            $table->string('stream_label')->nullable();
            $table->unsignedInteger('monthly_household_income')->nullable();
            $table->json('matric_data')->nullable();
            $table->json('fsc_data')->nullable();
            $table->json('entry_tests_data')->nullable();
            $table->json('preferences')->nullable();
            $table->boolean('onboarding_completed')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
