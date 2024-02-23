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
        Schema::create('user_all_queries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('query_id');
            $table->foreign('query_id')->references('id')->on('user_queries');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('mufti_id');
            $table->foreign('mufti_id')->references('id')->on('users');
            $table->longText('question')->default('');
            $table->integer('status')->comment("0 for pending,1 for accepted,2 for rejected")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_all_queries');
    }
};
