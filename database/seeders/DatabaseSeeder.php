<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,           // First create users
            LearningPathSeeder::class,   // Then create learning paths and courses
            VideoSeeder::class,          // Then create videos for courses
            TestSeeder::class,           // Then create tests for courses
            QuestionSeeder::class,       // Then create questions for tests
            OptionSeeder::class,         // Then create options for questions
            ReviewSeeder::class,         // Then create reviews for courses
            CommentSeeder::class,        // Then create comments for videos
            ContestSeeder::class,        // Then create contests
            AchievementSeeder::class,    // Then create achievements
            FriendSeeder::class,         // Then create friend relationships
            CourseUserSeeder::class,     // Then create course enrollments
            TestUserSeeder::class,       // Then create test attempts
            ContestUserSeeder::class,    // Finally create contest participations
        ]);
    }
}
