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
    Schema::table('temp_media', function (Blueprint $table) {
        $table->string('degree_title');
        $table->string('institute_name');
        $table->string('degree_startDate');
        $table->string('degree_endDate');
    });
}

public function down()
{
    Schema::table('temp_media', function (Blueprint $table) {
        $table->dropColumn(['degree_title', 'institute_name', 'degree_startDate', 'degree_endDate']);
    });
}

};
