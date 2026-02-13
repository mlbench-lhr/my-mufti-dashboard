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
        Schema::create('user_ramadan_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('ramadan_questions')->cascadeOnDelete();
            $table->foreignId('selected_option_id')->constrained('ramadan_options')->cascadeOnDelete();
            $table->boolean('is_correct');
            $table->integer('points_earned')->default(0);
            $table->timestamp('answered_at');
            $table->timestamps();

            $table->unique(['user_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ramadan_answers');
    }
};
