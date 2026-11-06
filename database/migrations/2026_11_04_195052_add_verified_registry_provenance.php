<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained()->restrictOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
            $table->unique(['province_id', 'slug']);
        });
        foreach (['universities', 'campuses'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->string('registry_key', 64)->nullable()->unique();
                $table->foreignId('city_id')->nullable()->constrained()->restrictOnDelete();
                $table->string('recognition_status')->default('pending_verification');
                $table->string('verification_status')->default('pending_verification')->index();
                $table->timestamp('last_verified_at')->nullable();
            });
        }
        Schema::table('universities', function (Blueprint $table) {
            $table->string('institution_status')->default('unknown');
            $table->string('campus_coverage')->default('pending_review');
        });
        Schema::table('campuses', function (Blueprint $table) {
            $table->string('campus_type')->default('unknown');
            $table->string('website')->nullable();
            $table->string('operational_status')->default('unknown');
        });
        Schema::table('data_sources', function (Blueprint $table) {
            $table->string('registry_key', 64)->nullable()->unique();
            $table->text('source_url')->nullable();
            $table->string('source_type')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->string('content_sha256', 64)->nullable();
        });
        Schema::create('university_source_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained()->restrictOnDelete();
            $table->foreignId('campus_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('data_source_id')->constrained()->restrictOnDelete();
            $table->string('evidence_key', 64)->unique();
            $table->string('evidence_type');
            $table->string('verification_status');
            $table->json('evidence');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_source_records');
        Schema::table('data_sources', function (Blueprint $table) {
            $table->dropColumn(['registry_key', 'source_url', 'source_type', 'fetched_at', 'verified_at', 'content_sha256']);
        });
        Schema::table('campuses', function (Blueprint $table) {
            $table->dropColumn(['campus_type', 'website', 'operational_status']);
        });
        Schema::table('universities', function (Blueprint $table) {
            $table->dropColumn(['institution_status', 'campus_coverage']);
        });
        foreach (['campuses', 'universities'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->dropConstrainedForeignId('city_id');
                $table->dropColumn(['registry_key', 'recognition_status', 'verification_status', 'last_verified_at']);
            });
        }
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
    }
};
