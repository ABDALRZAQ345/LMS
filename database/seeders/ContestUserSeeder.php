<?php

namespace Database\Seeders;

use App\Models\Contest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students
        $students = User::where('role', 'student')->get();
        // Get all contests
        $contests = Contest::all();

        // For each student, create 1-3 contest participations
        foreach ($students as $student) {
            $numContests = rand(1, 3);
            $randomContests = $contests->random($numContests);

            foreach ($randomContests as $contest) {
                $startTime = Carbon::parse($contest->start_at);
                $endTime = $startTime->copy()->addHours(rand(1, 3));
                $student->contests()->attach($contest->id, [
                    'end_time' => $endTime,
                    'correct_answers' => rand(0, 20),
                    'gained_points' => rand(0, 100),
                    'is_official' => 1,
                    'rank' => rand(1, 50),
                ]);
            }
        }
    }
}
