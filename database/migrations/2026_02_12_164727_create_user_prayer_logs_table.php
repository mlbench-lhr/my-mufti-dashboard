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
        Schema::create('user_prayer_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('prayer_id')
                ->constrained('prayers')
                ->cascadeOnDelete();

            $table->date('prayer_date');

            $table->enum('status', ['offered', 'missed'])
                ->default('offered');

            $table->integer('points_earned')->default(0);

            $table->text('notes')->nullable();
            $table->boolean('is_late')->default(false);

            $table->timestamps();

            $table->unique(['user_id', 'prayer_id', 'prayer_date']);
            $table->index(['user_id', 'prayer_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_prayer_logs');
    }
};
