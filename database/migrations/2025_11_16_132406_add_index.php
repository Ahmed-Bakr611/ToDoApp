<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_tag', function (Blueprint $table) {
            // Single-column indexes
            $table->index('task_id');
            $table->index('tag_id');

            // Composite index for faster queries filtering by both
            $table->index(['tag_id', 'task_id']);
        });
    }

    public function down(): void
    {
        Schema::table('task_tag', function (Blueprint $table) {
            $table->dropIndex(['task_id']);
            $table->dropIndex(['tag_id']);
            $table->dropIndex(['tag_id', 'task_id']);
        });
    }
};
