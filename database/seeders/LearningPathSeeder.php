<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\LearningPath;
use Illuminate\Database\Seeder;

class LearningPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 learning paths
        $learningPaths = LearningPath::factory(4)->create();

        // For each learning path, create 5 courses and attach them
        foreach ($learningPaths as $index => $learningPath) {
            $courses = Course::factory(5)->create();

            // Attach courses to learning path with order
            foreach ($courses as $order => $course) {
                $learningPath->courses()->attach($course->id, ['order' => $order + 1]);
            }
        }
    }
}
