<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scraping_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_code')->unique()->index(); // e.g. JOB-CRW-9842
            $table->foreignId('data_source_id')->nullable()->constrained('data_sources')->nullOnDelete();
            $table->string('target');
            $table->string('url');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->string('duration')->nullable();
            $table->unsignedInteger('pages_crawled')->default(0);
            $table->unsignedInteger('records_extracted')->default(0);
            $table->string('status')->default('completed')->index(); // queued, running, completed, failed
            $table->unsignedSmallInteger('http_code')->default(200);
            $table->longText('log_output')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraping_jobs');
    }
};
