<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('institution')->index();
            $table->string('base_url');
            $table->string('endpoint')->default('/');
            $table->string('feed_type')->default('HTML Table Scraper')->index(); // PDF Gazette Parser, HTML Table Scraper, REST JSON API Scraper, Statutory Gazette Scraper
            $table->decimal('reliability_score', 5, 2)->default(100.00);
            $table->string('status')->default('healthy')->index(); // healthy, warning, error
            $table->unsignedSmallInteger('http_status')->default(200);
            $table->string('crawl_frequency')->default('Daily');
            $table->timestamp('last_crawled_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_sources');
    }
};
