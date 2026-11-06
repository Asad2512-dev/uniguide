<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_base_documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_code')->unique()->index(); // e.g. kb-doc-1
            $table->string('title');
            $table->string('authority')->index();
            $table->string('statutory_reference')->nullable();
            $table->string('category')->default('statutory')->index();
            $table->unsignedInteger('chunks_count')->default(0);
            $table->string('status')->default('Indexed')->index(); // Indexed, Processing, Failed
            $table->timestamp('last_embedded_at')->nullable();
            $table->string('file_path')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_base_documents');
    }
};
