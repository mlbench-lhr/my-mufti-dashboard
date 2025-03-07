<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('mufti_appointments', function (Blueprint $table) {
        $table->integer('status')->comment("1 for pending,2 for completed")->default(1)->after('user_id');
    });
}
/**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('mufti_appointments', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
