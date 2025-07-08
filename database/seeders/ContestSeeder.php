<?php

namespace Database\Seeders;

use App\Models\Contest;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all teachers
        $teachers = User::where('role', 'teacher')->get();

        // Create 5-10 contests
        $numContests = rand(15, 20);
        for ($i = 0; $i < $numContests; $i++) {
            Contest::factory()->create([
                'user_id' => $teachers->random()->id,
                'type' => $i % 2 ? 'quiz' : 'programming',
                'request_status' => 'accepted'
            ]);
        }
    }
}
