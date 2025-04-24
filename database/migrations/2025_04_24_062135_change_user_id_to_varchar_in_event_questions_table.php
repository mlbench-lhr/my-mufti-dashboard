<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_questions', function (Blueprint $table) {
            // Drop foreign key and index
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('event_questions', function (Blueprint $table) {
            // Add back as varchar
            $table->string('user_id')->after('event_id');
        });
    }

    public function down(): void
    {
        Schema::table('event_questions', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });

        Schema::table('event_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('event_id');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};