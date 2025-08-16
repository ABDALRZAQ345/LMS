<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->limit(5)->get();
        $course=Course::limit(4)->get();
        foreach ($students as $student) {
            Certificate::factory(1)->create(['user_id' => $student->id,
            'course_id' => $course->random()->id
            ]);
        }
    }
}
