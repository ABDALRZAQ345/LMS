<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 achievements
        $achievements = Achievement::factory(10)->create();

        // Get all users
        $users = User::all();

        // Randomly assign achievements to users
        foreach ($users as $user) {
            $numAchievements = rand(0, 5);
            $randomAchievements = $achievements->random($numAchievements);
            $user->achievements()->attach($randomAchievements);
        }
    }
} 