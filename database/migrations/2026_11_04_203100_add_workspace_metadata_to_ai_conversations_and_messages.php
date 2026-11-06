<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_conversations', function (Blueprint $table) {
            $table->timestamp('pinned_at')->nullable();
            $table->index(['user_id', 'last_activity_at']);
        });
        Schema::table('ai_messages', function (Blueprint $table) {
            $table->uuid('request_id')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('ai_messages', fn (Blueprint $table) => $table->dropColumn('request_id'));
        Schema::table('ai_conversations', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'last_activity_at']);
            $table->dropColumn('pinned_at');
        });
    }
};
