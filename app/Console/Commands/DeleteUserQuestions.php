<?php

namespace App\Console\Commands;

use App\Http\Requests\ReportQuestion;
use App\Models\AdminReply;
use App\Models\ScholarReply;
use Illuminate\Console\Command;
use App\Models\UserQuery;
use App\Models\UserAllQuery;
use App\Models\Question;
use App\Models\QuestionComment;
use App\Models\QuestionVote;

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
        $specificUserIds = [380, 385, 253, 15, 206, 269, 24, 285, 9, 76, 348, 243];

        $questions = UserQuery::whereIn('user_id', $specificUserIds)->pluck('id')->toArray();

        if (empty($questions)) {
            $this->info('No questions found for the specified users.');
            return;
        }

        UserAllQuery::whereIn('query_id', $questions)->delete();
        UserQuery::whereIn('user_id', $specificUserIds)->delete();

        $public_questions = Question::whereIn('user_id', $specificUserIds)->pluck('id')->toArray();

        if (empty($public_questions)) {
            $this->info('No questions found for the specified users.');
            return;
        }


        QuestionComment::whereIn('question_id', $public_questions)->delete();
        QuestionVote::whereIn('question_id', $public_questions)->delete();
        ReportQuestion::whereIn('question_id', $public_questions)->delete();
        ScholarReply::whereIn('question_id', $public_questions)->delete();
        AdminReply::whereIn('question_id', $public_questions)->delete();
        Question::whereIn('user_id', $specificUserIds)->delete();


        $this->info('All questions for the specified users have been deleted.');

    }
}
