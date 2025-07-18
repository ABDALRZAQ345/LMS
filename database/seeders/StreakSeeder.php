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
            \Log::channel('verification_code')->info('Starting Streak: ' . $user->id);
            $date = $startOfYear->copy();
            $streaks = [];

            while ($date <= $endOfYear) {
                $status = 0;
                if ($date < now()) {
                    $status = rand(0, 3);
                }
                $streaks[] = [
                    'user_id' => $user->id,
                    'date' => $date->toDateString(),
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $date->addDay();
            }
            foreach (array_chunk($streaks, 500) as $chunk) {
                Streak::insert($chunk);
            }
        }
    }
}
