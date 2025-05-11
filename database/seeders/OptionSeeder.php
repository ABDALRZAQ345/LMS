<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all questions
        $questions = Question::all();

        // For each question, create 4 options
        foreach ($questions as $question) {
            // Create 3 incorrect options
            Option::factory(3)->create([
                'question_id' => $question->id,
                'is_correct' => false
            ]);

            // Create 1 correct option
            Option::factory()->create([
                'question_id' => $question->id,
                'is_correct' => true
            ]);
        }
    }
} 