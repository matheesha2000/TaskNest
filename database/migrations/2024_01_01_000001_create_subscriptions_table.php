<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // 'Free', 'Pro'
            $table->decimal('price', 8, 2)->default(0);   // 0.00 for Free
            $table->integer('duration')->default(30);      // days
            $table->json('features');                      // ["unlimited_tasks","priority","categories",...]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};