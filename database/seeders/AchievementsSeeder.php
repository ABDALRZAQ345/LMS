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
            ['name' => 'First Course', 'description' => 'Completed your first course.'],//done
            ['name' => 'Winner Winner', 'description' => 'get one of the first 3th places in contest','image'=>'https://img.icons8.com/color/96/medal2.png'], //done
            ['name' => 'Looser Looser', 'description' => 'get the last place in contest with more than 3 participants','image'=>'https://img.icons8.com/color/96/sad.png'], // done
            ['name' => 'Quiz Beast', 'description' => 'Achieved 100% in a final quiz. '],//done
            ['name' => 'Certification Hunter', 'description' => 'Earned 3 course certificates. '],
            ['name' => 'Not beginner yet', 'description' => 'reach level intermediate ','image'=>'https://img.icons8.com/color/96/level-up.png'],//done
            ['name' => 'Contest Rookie', 'description' => 'Joined your first contest. ','image'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1DNNFQ6hXWIlRRbw-PasghEnRmhOd25r93Q&s'],//done


            ['name' => 'Track Starter', 'description' => 'Enrolled in a learning path.'],//done
            ['name' => 'Quiz Champ', 'description' => 'Passed 10 quizzes. '],
            ['name' => 'Video Marathon', 'description' => 'Watched 20 videos in a row.'],
            ['name' => 'Explorer', 'description' => 'Tried 3 different tracks. '],//done

            ['name' => 'Back Again', 'description' => 'Returned after a year. ','image'=>'https://img.icons8.com/color/96/return.png'],//done
            ['name' => 'Friendly' ,'description' => 'Have +10 Friend','image'=>'https://img.icons8.com/color/96/friends.png'],//done
            ['name'=> 'Hater' ,'description' => 'rate +10 courses of 1'],//done
            ['name'=>'Famous','description' => 'Have +100 Friend to observe  on their performance  '],//done
            ['name'=> 'Project Creator', 'description' => 'get Your first Project Accepted ','image'=> 'https://img.icons8.com/color/96/project.png'],//done
            ['name'=> 'Projects Master', 'description' => 'get 3 Projects Accepted.','image'=>'https://img.icons8.com/color/96/project-management.png'],//done
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
