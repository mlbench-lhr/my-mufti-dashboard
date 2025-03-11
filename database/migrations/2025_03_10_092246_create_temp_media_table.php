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
        Schema::create('temp_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temp_id');
            $table->foreign('temp_id')->references('id')->on('stages')->onDelete('cascade');
            $table->string('degree_title');
            $table->string('institute_name');
            $table->string('degree_startDate');
            $table->string('degree_endDate')->nullable();
            $table->string('degree_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_media');
    }
};