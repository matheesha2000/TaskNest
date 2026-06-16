<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds the missing columns that were omitted in the 2026_06_16_094236 migration.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Change status from string to enum to match the original schema
            $table->enum('status', ['pending', 'in_progress', 'completed'])
                  ->default('pending')
                  ->change();

            // Change due_date from timestamp to date to match the original schema
            $table->date('due_date')->nullable()->change();

            // Add the missing columns
            $table->enum('priority', ['low', 'medium', 'high'])
                  ->default('medium')
                  ->after('status'); // Pro only

            $table->string('category')
                  ->nullable()
                  ->after('priority'); // Pro only
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['priority', 'category']);
            $table->string('status')->default('pending')->change();
            $table->timestamp('due_date')->nullable()->change();
        });
    }
};
