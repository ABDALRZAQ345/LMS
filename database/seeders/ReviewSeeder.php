<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all courses
        $courses = Course::all();
        // Get all students
        $students = User::where('role', 'student')->get();

        // For each course, create 3-8 reviews
        foreach ($courses as $course) {
            $numReviews = rand(3, 8);
            for ($i = 0; $i < $numReviews; $i++) {
                Review::factory()->create([
                    'course_id' => $course->id,
                    'user_id' => $students->random()->id,
                ]);
            }
        }
    }
}
