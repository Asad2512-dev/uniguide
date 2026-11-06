<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eligibility_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_key')->unique();
            $table->string('title');
            $table->string('authority')->index(); // PEC, HEC, NCEAC, IBCC, NUST, etc.
            $table->string('statutory_reference')->nullable();
            $table->string('category')->index();
            $table->string('verified_date')->nullable();
            $table->longText('summary');
            $table->string('scope')->nullable();
            $table->longText('formula_note')->nullable();
            $table->decimal('minimum_percentage', 5, 2)->nullable();
            $table->json('parameters')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eligibility_rules');
    }
};
