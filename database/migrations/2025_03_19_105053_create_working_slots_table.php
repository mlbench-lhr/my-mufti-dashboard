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
        Schema::create('working_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('working_day_id');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('status')->comment("1 for working,2 for deleted")->default(1);
            $table->timestamps();
            $table->foreign('working_day_id')->references('id')->on('working_days')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_slots');
    }
};
