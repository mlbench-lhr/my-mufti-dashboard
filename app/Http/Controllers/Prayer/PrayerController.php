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

        $stat = DB::transaction(function () use ($request, $user) {
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
            return $this->updateStats($user, $request->date);
        });

        return response()->json([
            'success' => true,
            'message' => 'Prayer logged successfully',
            'stats' => [
                'current_streak' => $stat->current_streak,
                'longest_streak' => $stat->longest_streak,
                'total_points' => $stat->total_points,
                'total_prayers_offered' => $stat->total_prayers_offered,
            ]
        ]);
    }

    private function updateStats($user, $date)
    {
        $stat = UserPrayerStat::firstOrCreate(
            ['user_id' => $user->id],
            [
                'current_streak' => 0,
                'longest_streak' => 0,
                'total_points' => 0,
                'total_prayers_offered' => 0,
            ]
        );

        $logs = UserPrayerLog::where('user_id', $user->id)
            ->where('status', 'offered')
            ->orderBy('prayer_date', 'asc')
            ->orderBy('prayer_id', 'asc')
            ->get();

        $currentStreak = 0;
        $longestStreak = 0;

        $expectedPrayerId = 1;

        foreach ($logs as $log) {

            if ($log->prayer_id == $expectedPrayerId) {

                $currentStreak++;
                $longestStreak = max($longestStreak, $currentStreak);

                $expectedPrayerId++;

                // Reset cycle after 5
                if ($expectedPrayerId > 5) {
                    $expectedPrayerId = 1;
                }
            } else {

                // Sequence broken → reset streak
                $currentStreak = 1;
                $longestStreak = max($longestStreak, $currentStreak);

                // Restart expected sequence from next after current
                $expectedPrayerId = $log->prayer_id + 1;

                if ($expectedPrayerId > 5) {
                    $expectedPrayerId = 1;
                }
            }
        }

        $stat->current_streak = $currentStreak;
        $stat->longest_streak = $longestStreak;

        $stat->total_points = UserPrayerLog::where('user_id', $user->id)
            ->where('status', 'offered')
            ->sum('points_earned');

        $stat->total_prayers_offered = UserPrayerLog::where('user_id', $user->id)
            ->where('status', 'offered')
            ->count();

        $stat->save();

        return $stat;
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
