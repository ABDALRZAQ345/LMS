<?php

namespace Database\Seeders;

use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students
        $students = User::where('role', 'student')->get();
        // Get all tests
        $tests = Test::all();

        // For each student, create 2-4 test attempts
        foreach ($students as $student) {
            $numTests = rand(2, 4);
            $randomTests = $tests->random($numTests);

            foreach ($randomTests as $test) {
                $startTime = Carbon::now()->subHours(rand(1, 24));
                $endTime = $startTime->copy()->addMinutes(rand(15, 60));

                $student->tests()->attach($test->id, [
                    'correct_answers' => rand(0, $test->questions()->count()),
                    'start_time' => $startTime,
                    'end_time' => $endTime
                ]);
            }
        }
    }
}
