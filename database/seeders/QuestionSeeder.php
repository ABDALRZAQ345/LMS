<?php

namespace Database\Seeders;

use App\Models\Contest;
use App\Models\Question;
use App\Models\Test;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all tests
        $tests = Test::all();

        // For each test, create 5-10 questions
        foreach ($tests as $test) {
            Question::factory(rand(5, 10))->create([
                'questionable_type' => Test::class,
                'questionable_id' => $test->id
            ]);
        }
        $contests = Contest::all();
        foreach ($contests as $contest) {
            Question::factory(rand(5, 10))->create([
                'questionable_type' => Contest::class,
                'questionable_id' => $contest->id
            ]);
        }
    }
}
