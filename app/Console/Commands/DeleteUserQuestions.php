<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\AdminReply;
use App\Models\Event;
use App\Models\EventQuestion;
use App\Models\EventQuestionLike;
use App\Models\EventScholar;
use App\Models\MuftiAppointment;
use App\Models\Notification;
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
        $specificUserIds = [380, 385, 253, 15, 206, 269, 285, 76, 348, 243, 28, 320, 324];
        $defaultMufti = [9];

        QuestionComment::whereIn('user_id', $specificUserIds)->delete();
        QuestionVote::whereIn('user_id', $specificUserIds)->delete();
        ReportQuestion::whereIn('user_id', $specificUserIds)->delete();
        ScholarReply::whereIn('user_id', $specificUserIds)->delete();
        AdminReply::whereIn('user_id', $specificUserIds)->delete();


        UserAllQuery::whereIn('user_id', $specificUserIds)->delete();

        EventScholar::whereIn('user_id', $specificUserIds)->delete();
        SaveEvent::whereIn('user_id', $specificUserIds)->delete();
        EventQuestionLike::whereIn('user_id', $specificUserIds)->delete();

        $eventQuestions = EventQuestion::whereIn('user_id', $specificUserIds)->pluck('id')->toArray();
        EventQuestionLike::whereIn('event_question_id', $eventQuestions)->delete();
        EventQuestion::whereIn('user_id', $specificUserIds)->delete();

        $questions = UserQuery::whereIn('user_id', $specificUserIds)->pluck('id')->toArray();
        $muftiQuestions = UserQuery::whereIn('user_id', $defaultMufti)->pluck('id')->toArray();

        $public_questions = Question::whereIn('user_id', $specificUserIds)->pluck('id')->toArray();
        $events = Event::whereIn('user_id', $specificUserIds)->pluck('id')->toArray();
        MuftiAppointment::whereIn('user_id', $specificUserIds)->delete();
        Activity::whereIn('data_id', $specificUserIds)->delete();
        Notification::whereIn('user_id', $specificUserIds)->delete();

        if (empty($public_questions) && empty($questions) && empty($events) && empty($muftiQuestions)) {
            $this->info('No data found for the specified users.');
            return;
        }

        UserAllQuery::whereIn('query_id', $questions)->delete();
        UserQuery::whereIn('user_id', $specificUserIds)->delete();

        UserAllQuery::whereIn('query_id', $muftiQuestions)->delete();
        UserQuery::whereIn('user_id', $defaultMufti)->delete();

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
