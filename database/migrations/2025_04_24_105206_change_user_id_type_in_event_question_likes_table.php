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
        Schema::table('event_question_likes', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Drop foreign key
            $table->dropColumn('user_id'); // Drop existing column
        });

        Schema::table('event_question_likes', function (Blueprint $table) {
            $table->string('user_id')->after('event_question_id'); // Add new VARCHAR user_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_question_likes', function (Blueprint $table) {
            $table->dropColumn('user_id'); // Drop string column
            $table->foreignId('user_id')   // Recreate foreign key as integer
                  ->constrained()
                  ->onDelete('cascade');
        });
    }
};
