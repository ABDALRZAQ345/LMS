<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Test;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all courses
        $courses = Course::all();

        // For each course, create a test at position 4
        foreach ($courses as $course) {
            Test::factory()->create([
                'course_id' => $course->id,
                'order' => 4,
            ]);
        }
    }
}
