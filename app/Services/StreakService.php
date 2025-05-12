<?php

namespace App\Services;

use App\Models\Streak;
use App\Models\User;

class StreakService
{
    public static function CreateStreakLogs(User $user): void
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        $date = $startOfYear->copy();

        while ($date <= $endOfYear) {
            Streak::create([
                'user_id' => $user->id,
                'date' => $date->toDateString(),
                'status' => 0,
            ]);

            $date->addDay();
        }

    }
}
