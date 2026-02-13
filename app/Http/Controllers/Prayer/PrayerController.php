<?php

namespace App\Http\Controllers\Prayer;

use App\Http\Controllers\Controller;
use App\Models\Prayer;
use App\Models\User;
use App\Models\UserPrayerLog;
use App\Models\UserPrayerStat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PrayerController extends Controller
{
    public function log(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'prayer_id' => 'required|exists:prayers,id',
            'date' => 'required|date',
            'status' => 'required|in:offered,missed',
        ]);

        if ($request->date !== now()->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'You can only log prayers for today.'
            ], 422);
        }

        $user = User::findOrFail($request->user_id);

        DB::transaction(function () use ($request, $user) {
            $points = 10;

            UserPrayerLog::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'prayer_id' => $request->prayer_id,
                    'prayer_date' => $request->date,
                ],
                [
                    'status' => $request->status,
                    'points_earned' => $request->status === 'offered' ? $points : 0,
                ]
            );
            $this->updateStats($user, $request->date);
        });

        return response()->json([
            'success' => true,
            'message' => 'Prayer logged successfully'
        ]);
    }

    private function updateStats($user, $date)
    {
        $date = \Carbon\Carbon::parse($date)->toDateString();

        $offeredCount = UserPrayerLog::where('user_id', $user->id)
            ->whereDate('prayer_date', $date)
            ->where('status', 'offered')
            ->count();

        $stat = UserPrayerStat::firstOrCreate(
            ['user_id' => $user->id],
            []
        );

        // POINTS (temporary rule)
        // 10 points per prayer
        $pointsForToday = $offeredCount * 10;

        // Update total points
        $stat->total_points = UserPrayerLog::where('user_id', $user->id)
            ->sum('points_earned');

        // STREAK LOGIC
        if ($offeredCount === 5 && $stat->last_completed_date !== $date) {

            $yesterday = \Carbon\Carbon::parse($date)->subDay()->toDateString();

            if ($stat->last_completed_date === $yesterday) {
                $stat->current_streak += 1;
            } else {
                $stat->current_streak = 1;
            }

            if ($stat->current_streak > $stat->longest_streak) {
                $stat->longest_streak = $stat->current_streak;
            }

            $stat->last_completed_date = $date;
        }

        $stat->total_prayers_offered = UserPrayerLog::where('user_id', $user->id)
            ->where('status', 'offered')
            ->count();

        $stat->save();
    }

    public function today(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $today = Carbon::today();

        $prayers = Prayer::with(['logs' => function ($query) use ($user, $today) {
            $query->where('user_id', $user->id)
                ->whereDate('prayer_date', $today);
        }])->get();

        $response = $prayers->map(function ($prayer) {
            return [
                'id' => $prayer->id,
                'name' => $prayer->name,
                'is_offered' => $prayer->logs->isNotEmpty(),
            ];
        });

        $stats = $user->prayerStat;

        return response()->json([
            'date' => $today->toDateString(),
            'prayers' => $response,
            'stats' => [
                'current_streak' => $stats?->current_streak ?? 0,
                'longest_streak' => $stats?->longest_streak ?? 0,
                'total_points' => $stats?->total_points ?? 0,
                'total_prayers_offered' => $stats?->total_prayers_offered ?? 0,
            ]
        ]);
    }
}
