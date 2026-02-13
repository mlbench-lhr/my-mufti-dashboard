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
        Schema::create('ramadan_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('week_id')->constrained('ramadan_weeks')->cascadeOnDelete();
            $table->integer('day_number'); // 1–30
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->integer('max_points')->default(100);
            $table->integer('total_questions')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramadan_topics');
    }
};
