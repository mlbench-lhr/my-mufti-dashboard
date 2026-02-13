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
        Schema::create('ramadan_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('ramadan_topics')->cascadeOnDelete();
            $table->integer('question_number');
            $table->text('question');
            $table->integer('points')->default(20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ramadan_questions');
    }
};
