<?php

namespace Database\Seeders;

use App\Models\Streak;
use App\Models\User;
use Illuminate\Database\Seeder;

class StreakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        foreach ($users as $user) {
            $date = $startOfYear->copy();
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
    }
}
