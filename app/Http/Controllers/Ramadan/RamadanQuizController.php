<?php

namespace App\Http\Controllers\Ramadan;

use App\Http\Controllers\Controller;
use App\Models\RamadanQuestion;
use App\Models\RamadanTopic;
use App\Models\UserRamadanAnswer;
use App\Models\UserRamadanQuizProgress;
use App\Models\UserRamadanStat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RamadanQuizController extends Controller
{
    private function currentRamadanDay()
    {
        $ramadanStart = Carbon::create(2026, 2, 13);
        return max(1, Carbon::today()->diffInDays($ramadanStart) + 1);
    }

    private function calculateLevel($points)
    {
        if ($points >= 1000) return 'Master';
        if ($points >= 500) return 'Scholar';
        if ($points >= 200) return 'Learner';
        return 'Beginner';
    }

    private function nextLevelThreshold($points)
    {
        if ($points < 200) return 200;
        if ($points < 500) return 500;
        if ($points < 1000) return 1000;
        return null;
    }

    private function currentWeek($currentDay)
    {
        return (int) ceil($currentDay / 7);
    }

    public function dashboard(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $userId = $request->user_id;
        $currentDay = $this->currentRamadanDay();

        $stats = UserRamadanStat::firstOrCreate(
            ['user_id' => $userId],
            ['total_points' => 0]
        );

        $level = $this->calculateLevel($stats->total_points);

        $todayTopic = RamadanTopic::with(['week', 'questions.options'])
            ->where('day_number', $currentDay)
            ->first();

        $next = $this->nextLevelThreshold($stats->total_points);
        $currentWeek = $this->currentWeek($currentDay);

        return response()->json([
            'current_ramadan_day' => $currentDay,
            'current_week' => $currentWeek,
            'total_points' => $stats->total_points,
            'level' => $level,
            'progress_percentage' => round(($stats->total_points / 3000) * 100) ?? 0,
            'points_to_next_level' => $next ? $next - $stats->total_points : 0,
            'today_topic' => $todayTopic ? [
                'id' => $todayTopic->id,
                'title' => $todayTopic->title,
                'subtitle' => $todayTopic->subtitle,
                'is_unlocked' => true
            ] : null
        ]);
    }

    public function weekTopics(Request $request, $week)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $userId = $request->user_id;
        $currentDay = $this->currentRamadanDay();

        $topics = RamadanTopic::whereHas('week', function ($q) use ($week) {
            $q->where('week_number', $week);
        })
            ->withCount('questions')
            ->get()
            ->map(function ($topic) use ($currentDay, $userId) {

                $progress = UserRamadanQuizProgress::where([
                    'user_id' => $userId,
                    'topic_id' => $topic->id
                ])->first();

                return [
                    'id' => $topic->id,
                    'title' => $topic->title,
                    'subtitle' => $topic->subtitle,
                    'day' => $topic->day_number,
                    'is_unlocked' => $topic->day_number <= $currentDay,
                    'is_completed' => $progress?->is_completed ?? false,
                    'points_earned' => $progress?->points_earned ?? 0,
                    'max_points' =>  $topic->max_points,
                    'total_questions' =>  $topic->total_questions,
                    'correct_answers' => $progress?->correct_answers ?? 0
                ];
            });

        return response()->json([
            'week_number' => (int) $week,
            'current_day' => $currentDay,
            'topics' => $topics
        ]);
    }

    public function topicQuiz(Request $request, $topicId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $userId = $request->user_id;
        $currentDay = $this->currentRamadanDay();

        $topic = RamadanTopic::with('questions.options')
            ->findOrFail($topicId);

        if ($topic->day_number > $currentDay) {
            return response()->json([
                'error' => 'TOPIC_LOCKED'
            ], 403);
        }

        $questions = $topic->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'question_number' => $q->question_number,
                'question' => $q->question,
                'options' => $q->options->map(fn($opt) => [
                    'id' => $opt->id,
                    'text' => $opt->option_text
                ])
            ];
        });

        return response()->json([
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
            'topic_subtitle' => $topic->subtitle,
            'total_questions' => 5,
            'points_per_question' => 20,
            'max_points' => 100,
            'questions' => $questions
        ]);
    }

    public function submitQuestion(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'question_id' => 'required|exists:ramadan_questions,id',
            'selected_option_id' => 'required|exists:ramadan_options,id',
        ]);

        $userId = $request->user_id;

        $question = RamadanQuestion::with('options', 'topic')
            ->findOrFail($request->question_id);

        $currentDay = $this->currentRamadanDay();

        if ($question->topic->day_number > $currentDay) {
            return response()->json([
                'error' => 'TOPIC_LOCKED'
            ], 403);
        }

        if (UserRamadanAnswer::where([
            'user_id' => $userId,
            'question_id' => $question->id
        ])->exists()) {
            return response()->json([
                'error' => 'ALREADY_ANSWERED'
            ], 422);
        }

        $correctOption = $question->options->where('is_correct', true)->first();

        $isCorrect = $correctOption->id == $request->selected_option_id;
        $points = $isCorrect ? 20 : 0;

        UserRamadanAnswer::create([
            'user_id' => $userId,
            'question_id' => $question->id,
            'selected_option_id' => $request->selected_option_id,
            'is_correct' => $isCorrect,
            'points_earned' => $points,
            'answered_at' => now()
        ]);

        $progress = UserRamadanQuizProgress::firstOrCreate(
            [
                'user_id' => $userId,
                'topic_id' => $question->topic_id
            ],
            [
                'points_earned' => 0,
                'correct_answers' => 0,
                'is_completed' => false
            ]
        );

        $progress->points_earned += $points;
        if ($isCorrect) {
            $progress->correct_answers += 1;
        }

        $answeredCount = UserRamadanAnswer::where('user_id', $userId)
            ->whereHas('question', fn($q) =>
            $q->where('topic_id', $question->topic_id))
            ->count();

        if ($answeredCount >= $question->topic->total_questions) {
            $progress->is_completed = true;
            $progress->completed_at = now();
        }

        $progress->save();

        $stats = UserRamadanStat::firstOrCreate(
            ['user_id' => $userId],
            ['total_points' => 0]
        );

        $stats->total_points += $points;
        $stats->level = $this->calculateLevel($stats->total_points);
        $stats->save();

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_option_id' => $correctOption->id,
            'points_earned' => $points,
            'total_topic_points' => $progress->points_earned,
            'total_user_points' => $stats->total_points,
            'level' => $stats->level
        ]);
    }
}
