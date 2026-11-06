<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete();
            $table->string('institution_slug')->index();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('category')->default('general')->index();
            $table->string('subject');
            $table->longText('message');
            $table->longText('reply_content')->nullable();
            $table->string('status')->default('pending')->index(); // pending, in_progress, resolved
            $table->string('priority')->default('medium')->index(); // low, medium, high
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
