<?php

namespace App\Console\Commands;

use App\Models\AdminReply;
use App\Models\Event;
use App\Models\EventQuestion;
use App\Models\EventQuestionLike;
use App\Models\EventScholar;
use App\Models\Question;
use App\Models\QuestionComment;
use App\Models\QuestionVote;
use App\Models\ReportQuestion;
use App\Models\SaveEvent;
use App\Models\ScholarReply;
use App\Models\UserAllQuery;
use App\Models\UserQuery;
use Illuminate\Console\Command;

class DeleteUserQuestions extends Command
{
    protected $signature = 'questions:delete';
    protected $description = 'Delete all questions for specific users';

    public function handle()
    {
        $specificUserIds = [380, 385, 253, 15, 206, 269, 24, 285, 9, 76, 348, 243, 28];

        $questions = UserQuery::whereIn('user_id', $specificUserIds)->pluck('id')->toArray();
        $public_questions = Question::whereIn('user_id', $specificUserIds)->pluck('id')->toArray();
        $events = Event::whereIn('user_id', $specificUserIds)->pluck('id')->toArray();

        if (empty($public_questions) && empty($questions) && empty($events)) {
            $this->info('No data found for the specified users.');
            return;
        }

        UserAllQuery::whereIn('query_id', $questions)->delete();
        UserQuery::whereIn('user_id', $specificUserIds)->delete();

        QuestionComment::whereIn('question_id', $public_questions)->delete();
        QuestionVote::whereIn('question_id', $public_questions)->delete();
        ReportQuestion::whereIn('question_id', $public_questions)->delete();
        ScholarReply::whereIn('question_id', $public_questions)->delete();
        AdminReply::whereIn('question_id', $public_questions)->delete();
        Question::whereIn('user_id', $specificUserIds)->delete();

        EventScholar::whereIn('event_id', $events)->delete();
        SaveEvent::whereIn('event_id', $events)->delete();
        $eventQuestions = EventQuestion::whereIn('event_id', $events)->pluck('id')->toArray();
        EventQuestionLike::whereIn('event_question_id', $eventQuestions)->delete();
        EventQuestion::whereIn('event_id', $events)->delete();
        Event::whereIn('user_id', $specificUserIds)->delete();

        $this->info('All questions for the specified users have been deleted.');
    }
}
