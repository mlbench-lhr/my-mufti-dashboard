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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('');
            $table->string('email')->default('');
            $table->string('password')->default('');
            $table->string('image')->default('');
            $table->string('phone_number')->default('');
            $table->string('fiqa')->default('');
            $table->integer('mufti_status')->comment("1 for pending,2 for accepted,3 for rejected")->default(0);
            $table->string('user_type')->default('user');
            $table->longText('device_id')->default('');
            $table->longText('a_code')->default('');
            $table->longText('g_code')->default('');
            $table->integer('email_code')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};