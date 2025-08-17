<?php

namespace Database\Seeders;

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
//            LearningPathSeeder::class,
//            VideoSeeder::class,
//            TestSeeder::class,
//            ReviewSeeder::class,
//            CommentSeeder::class,
//            ContestSeeder::class,
//            QuestionSeeder::class,
//            OptionSeeder::class,
           AchievementsSeeder::class,
//            FriendSeeder::class,
//            //CourseUserSeeder::class,
//            //TestUserSeeder::class,
//            //ContestUserSeeder::class,
//            TagSeeder::class,
//            ProjectSeeder::class,
//            CertificateSeeder::class,
//            //ProblemSeeder::class,
//            //LikeSeeder::class,
//            StreakSeeder::class,
        ]);
    }
}
