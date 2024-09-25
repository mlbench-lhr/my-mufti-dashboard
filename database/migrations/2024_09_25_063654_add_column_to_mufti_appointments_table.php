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
            $table->string('contact_number')->default("")->after('duration');
            $table->string('email')->default("")->after('contact_number');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mufti_appointments', function (Blueprint $table) {
            $table->dropColumn('contact_number');
            $table->dropColumn('email');
        });
    }
};
