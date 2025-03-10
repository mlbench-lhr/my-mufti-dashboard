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
        Schema::table('stages', function (Blueprint $table) {
            if (Schema::hasColumn('stages', 'temp_id')) {
                $table->dropColumn('temp_id');
            }
            if (Schema::hasColumn('stages', 'user_type')) {
                $table->dropColumn('user_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stages', function (Blueprint $table) {
            $table->string('temp_id')->unique()->nullable();
            $table->enum('user_type', ['scholar', 'lifecoach'])->nullable();
        });
    }
};