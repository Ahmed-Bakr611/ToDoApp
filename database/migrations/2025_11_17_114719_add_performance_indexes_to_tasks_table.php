<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Composite index for filtered queries with cursor pagination
            $table->index(['user_id', 'completed', 'id'], 'tasks_user_completed_id_index');

            // If you need to sort by created_at
            $table->index(['user_id', 'completed', 'created_at'], 'tasks_user_completed_created_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
        });
    }
};
