<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            ['name' => 'First Course', 'description' => 'Completed your first course.'],
            ['name' => 'Winner Winner', 'description' => 'Won your first contest!'],
            ['name' => 'Persistent Learner', 'description' => 'Completed 5 courses.'],
            ['name' => 'Code Beast', 'description' => 'Achieved 100% in a final quiz. '],
            ['name' => 'Streak Master', 'description' => 'Studied for 7 days in a row. '],
            ['name' => 'Certification Hunter', 'description' => 'Earned 3 course certificates. '],
            ['name' => 'Point Collector', 'description' => 'Earned 500 points. '],
            ['name' => 'Contest Rookie', 'description' => 'Joined your first contest. '],
            ['name' => 'Night Coder', 'description' => 'Studied after midnight.'],
            ['name' => 'Bug Whisperer', 'description' => 'Fixed a tricky bug. '],


            ['name' => 'Retry Addict', 'description' => 'Failed the same quiz 5 times. '],
            ['name' => 'Donkey Award', 'description' => 'Failed miserably and tried again.'],
            ['name' => 'Procrastination Pro', 'description' => 'Logged in but didn’t study. '],
            ['name' => 'Almost Genius', 'description' => 'Got 59% in a quiz. So close! '],
            ['name' => 'Oops Again', 'description' => 'Retook a test 3 times. '],
            ['name' => 'Silent Observer', 'description' => 'Watched videos without answering any questions.'],


            ['name' => 'Track Starter', 'description' => 'Enrolled in a learning track.'],
            ['name' => 'Quiz Champ', 'description' => 'Passed 10 quizzes. '],
            ['name' => 'Fast Finisher', 'description' => 'Finished a course in one day. '],
            ['name' => 'Late Bloomer', 'description' => 'Completed a course after 30 days.'],
            ['name' => 'Social Coder', 'description' => 'Commented on 5 lessons. '],
            ['name' => 'Helpful Human', 'description' => 'Answered a peer’s question. '],
            ['name' => 'Video Marathon', 'description' => 'Watched 20 videos in a row.'],
            ['name' => 'Explorer', 'description' => 'Tried 3 different tracks. '],
            ['name' => 'Challenge Accepted', 'description' => 'Solved your first coding challenge. '],
            ['name' => 'Debugging Jedi', 'description' => 'Passed a test with 1 wrong answer. '],

            ['name' => 'Back Again', 'description' => 'Returned after a long break. '],
            ['name' => 'Legendary Learner', 'description' => 'Completed all available courses. '],
            ['name' => 'No Pain, No Gain', 'description' => 'Failed more than 10 times overall. '],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
        $achievementIds = Achievement::pluck('id')->toArray();
        for($i=1;$i<=100;$i++){
            $student=User::find($i);
            if (!$student) continue;

            $randomCount = rand(1, 10);
            $randomAchievements = collect($achievementIds)->random($randomCount)->toArray();

            $student->achievements()->syncWithoutDetaching($randomAchievements);
        }
    }
}
