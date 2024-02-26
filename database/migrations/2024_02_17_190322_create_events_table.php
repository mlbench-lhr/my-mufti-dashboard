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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('image')->default('');
            $table->string('event_title')->default('');
            $table->json('event_category')->default("[]");
            $table->timestamp('date')->useCurrent();
            $table->string('location')->default('');
            $table->double('latitude')->default(0);
            $table->double('longitude')->default(0);
            $table->longText('about');
            $table->integer('event_status')->comment("0 for rejected,1 for approved,2 for pending")->default(2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
