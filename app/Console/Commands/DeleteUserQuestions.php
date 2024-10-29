<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserQuery;
use App\Models\UserAllQuery;

class DeleteUserQuestions extends Command
{
    protected $signature = 'questions:delete';
    protected $description = 'Delete all questions for specific users';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $specificUserIds = [380, 385, 253];

        $questions = UserQuery::whereIn('user_id', $specificUserIds)->pluck('id')->toArray();

        if (empty($questions)) {
            $this->info('No questions found for the specified users.');
            return;
        }

        UserAllQuery::whereIn('query_id', $questions)->delete();
        UserQuery::whereIn('user_id', $specificUserIds)->delete();

        $this->info('All questions for the specified users have been deleted.');
    }
}
