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
        Schema::table('mufti_appointments', function (Blueprint $table) {
            $table->integer('selected_slot')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mufti_appointments', function (Blueprint $table) {
            $table->dropColumn('selected_slot');
        });
    }
};
