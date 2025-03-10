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
            $table->unsignedBigInteger('temp_id')->index();
            $table->foreign('temp_id')->references('id')->on('stages')->onDelete('cascade');
            $table->string('label')->nullable();
            $table->integer('index')->default(0);
            $table->string('media')->nullable(); // Store file path
            $table->string('media_type')->nullable(); // Type (e.g., image, pdf, etc.)
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