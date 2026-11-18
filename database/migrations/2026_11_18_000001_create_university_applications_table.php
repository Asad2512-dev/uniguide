<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('university_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('name');
            $table->string('website');
            $table->string('city');
            $table->string('province');
            $table->string('sector');
            $table->string('contact_name');
            $table->string('email');
            $table->string('phone');
            $table->text('description');
            $table->string('status')->default('pending')->index();
            $table->foreignId('university_id')->nullable()->constrained();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_applications');
    }
};
