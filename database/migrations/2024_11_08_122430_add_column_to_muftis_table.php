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
        Schema::table('muftis', function (Blueprint $table) {
            $table->longText('reason')->default("")->after('fiqa');
            $table->integer('status')->comment("1 for pending,2 for accepted,3 for rejected")->default(1)->after('reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('muftis', function (Blueprint $table) {
            $table->dropColumn('reason');
            $table->dropColumn('status');
        });
    }
};
