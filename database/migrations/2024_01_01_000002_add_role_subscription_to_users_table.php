<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NOTE: Laravel Breeze already creates the `users` table.
// This migration ADDS the extra columns your project needs.

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'admin'])->default('user')->after('email');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete()->after('role');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['subscription_id']);
            $table->dropColumn(['role', 'subscription_id', 'subscription_expires_at']);
        });
    }
};