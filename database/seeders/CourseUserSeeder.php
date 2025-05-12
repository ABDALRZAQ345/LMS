<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students
        $students = User::where('role', 'student')->get();
        // Get all courses
        $courses = Course::all();
        $faker = \Faker\Factory::create();

        // For each student, enroll in 2-5 random courses
        foreach ($students as $student) {
            $numCourses = rand(2, 5);
            $randomCourses = $courses->random($numCourses);

            foreach ($randomCourses as $course) {
                $course->students()->attach($student->id, [
                    'paid' => $course->price,
                    'status' => $faker->randomElement(['finished', 'enrolled', 'watch_later']),
                ]);
            }
        }
    }
}
