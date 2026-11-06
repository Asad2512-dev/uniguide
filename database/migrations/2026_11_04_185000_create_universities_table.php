<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('acronym')->nullable()->index();
            $table->string('charter_type')->nullable(); // Public Sector, Federal Charter, Provincial Act
            $table->string('sector')->default('public')->index(); // public, private
            $table->string('hec_ranking')->nullable();
            $table->string('hec_category')->nullable()->index(); // Category W, W4, X
            $table->unsignedSmallInteger('established')->nullable();
            $table->string('charter_citation')->nullable();
            $table->string('gazette_reference')->nullable();
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('province')->index(); // ict, punjab, sindh, kpk, balochistan, ajk_gb
            $table->string('province_label')->nullable();
            $table->string('city')->index();
            $table->string('fee_range')->nullable();
            $table->unsignedInteger('fee_min')->default(0)->index();
            $table->unsignedInteger('fee_max')->default(0)->index();
            $table->string('acceptance_rate')->nullable();
            $table->string('total_students')->nullable();
            $table->string('faculty_phd_ratio')->nullable();
            $table->string('campus_size')->nullable();
            $table->string('hostel_availability')->nullable();
            $table->string('status')->default('open')->index(); // open, closing_soon, upcoming, closed
            $table->string('deadline')->nullable();
            $table->boolean('featured')->default(false)->index();
            $table->json('accreditations')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('universities');
    }
};
