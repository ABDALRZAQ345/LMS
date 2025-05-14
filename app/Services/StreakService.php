<?php

namespace App\Services;

use App\Models\Streak;
use App\Models\User;
use Carbon\Carbon;

class StreakService
{
    public static function CreateStreakLogs(User $user): void
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        $date = $startOfYear;
        $streaks = [];
        while ($date <= $endOfYear) {
            $streaks[] = [
                'user_id' => $user->id,
                'date' => $date->toDateString(),
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $date->addDay();
        }

        Streak::insert($streaks);

    }

    public function getUserStreaks(User $user)
    {
        return $user->streaks()->get()->map(function ($streak) {
            if ($streak->date > now()->toDateString()) {
                $streak->status = null;
            }
            $streak->dayOfWeek = Carbon::parse($streak->date)->format('l');

            return $streak;
        });
    }

    public function increaseStreak(User $user): void
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $todayStreak = $user->streaks()->where('date', $today)->first();
        $yesterdayStreak = $user->streaks()->where('date', $yesterday)->first();

        if ($todayStreak) {
            $newStatus = min($todayStreak->status + 1, 3);
            $newStreakCount = $yesterdayStreak ? $yesterdayStreak->current_streak + 1 : 1;

            $todayStreak->update([
                'status' => $newStatus,
                'current_streak' => $newStreakCount,
            ]);
        }
    }
}
