<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LearningPathSeeder::class,
            VideoSeeder::class,
            TestSeeder::class,
            QuestionSeeder::class,
            OptionSeeder::class,
            ReviewSeeder::class,
            CommentSeeder::class,
            ContestSeeder::class,
            AchievementSeeder::class,
            FriendSeeder::class,
            CourseUserSeeder::class,
            TestUserSeeder::class,
            ContestUserSeeder::class,
        ]);
    }
}
