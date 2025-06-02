<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            ['name' => 'First Course', 'description' => 'Completed your first course.'],
            ['name' => 'Winner Winner', 'description' => 'get one of the first 3th places in contest'],
            ['name' => 'Looser Looser', 'description' => 'get the last place in contest with more than 3 participants'],
            ['name' => 'Persistent Learner', 'description' => 'Completed 5 courses.'],
            ['name' => 'Code Beast', 'description' => 'Achieved 100% in a final quiz. '],
            ['name' => 'Streak Master', 'description' => 'Studied for 7 days in a row. '],
            ['name' => 'Certification Hunter', 'description' => 'Earned 3 course certificates. '],
            ['name' => 'Point Collector', 'description' => 'Earned 500 points. '],
            ['name' => 'Contest Rookie', 'description' => 'Joined your first contest. '],
            ['name' => 'Night Coder', 'description' => 'Studied after midnight.'],

            ['name' => 'Retry Addict', 'description' => 'Failed the same quiz 5 times. '],
            ['name' => 'Donkey Award', 'description' => 'Failed miserably and tried again.'],
            ['name' => 'Procrastination Pro', 'description' => 'Logged in but didn’t study. '],
            ['name' => 'Almost Genius', 'description' => 'Got 59% in a quiz. So close! '],
            ['name' => 'Oops Again', 'description' => 'Retook a test 3 times. '],

            ['name' => 'Track Starter', 'description' => 'Enrolled in a learning path.'],
            ['name' => 'Quiz Champ', 'description' => 'Passed 10 quizzes. '],
            ['name' => 'Fast Finisher', 'description' => 'Finished a course in one day. '],
            ['name' => 'Late Bloomer', 'description' => 'Completed a course after 30 days.'],
            ['name' => 'Video Marathon', 'description' => 'Watched 20 videos in a row.'],
            ['name' => 'Explorer', 'description' => 'Tried 3 different tracks. '],

            ['name' => 'Back Again', 'description' => 'Returned after a long break. '],
            ['name' => 'Legendary Learner', 'description' => 'Completed all available courses. '],
            ['name' => 'No Pain, No Gain', 'description' => 'Failed more than 10 times overall in same quiz '],
            ['name' => 'Friendly' ,'description' => 'Have +10 Friend'],//done
            ['name'=> 'Hater' ,'description' => 'rate +10 courses of 1'],
            ['name'=>'Voyeur','description' => 'Have +100 Friend to Voyeur on their performance  '],//done
            ['name'=> 'Project Creator', 'description' => 'get Your first Project Accepted '],//done
            ['name'=> 'Projects Master', 'description' => 'get 3 Projects Accepted '],//done
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
        $achievementIds = Achievement::pluck('id')->toArray();
        for ($i = 1; $i <= 100; $i++) {
            $student = User::find($i);
            if (! $student) {
                continue;
            }

            $randomCount = rand(1, 10);
            $randomAchievements = collect($achievementIds)->random($randomCount)->toArray();

            $student->achievements()->syncWithoutDetaching($randomAchievements);
        }
    }
}
