<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all courses
        $courses = Course::all();

        // For each course, create videos with specific ordering
        foreach ($courses as $course) {
            // Create first set of videos (1-3)
            for ($i = 1; $i <= 3; $i++) {
                Video::factory()->create([
                    'course_id' => $course->id,
                    'order' => $i,
                ]);
            }

            // Create second set of videos (5-7)
            for ($i = 5; $i <= 7; $i++) {
                Video::factory()->create([
                    'course_id' => $course->id,
                    'order' => $i,
                ]);
            }
        }
    }
}
