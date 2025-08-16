<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\LearningPath;
use App\Models\User;
use Illuminate\Database\Seeder;

class LearningPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 3 learning paths
        $teachers = User::where('role', 'teacher')->pluck('id');
        $students = User::where('role', 'student')->pluck('id');

         LearningPath::factory(3)->create()->each(function ($learningPath) use ($teachers) {
            $learningPath->user_id = $teachers->random();
            $learningPath->save();
        });
        LearningPath::factory(2)->create()->each(function ($learningPath) use ($teachers) {
            $learningPath->user_id = 3;
            $learningPath->save();
        });
        $learningPaths = LearningPath::all();


        // For each learning path, create 5 courses and attach them
        foreach ($learningPaths as $index => $learningPath) {
            $courses = Course::factory(rand(1,3))->create([
                'user_id' => $learningPath->user_id,
            ]);

            // Attach courses to learning path with order
            foreach ($courses as $order => $course) {
                $learningPath->courses()->attach($course->id, ['order' => $order + 1]);
            }
        }

        // Seed learning_path_user table
//        foreach ($learningPaths as $learningPath) {
//            // Attach 5 random students to each learning path with random status
//            $randomStudents = $students->random(min(5, $students->count()));
//            foreach ($randomStudents as $studentId) {
//                $status = collect(['enroll', 'watch_later'])->random();
//                \DB::table('learning_path_user')->insert([
//                    'user_id' => $studentId,
//                    'learning_path_id' => $learningPath->id,
//                    'status' => $status,
//                    'created_at' => now(),
//                    'updated_at' => now(),
//                ]);
//            }
//        }
    }
}
