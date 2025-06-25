<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'problem_id' => \App\Models\Problem::factory(),
            'language' => $this->faker->randomElement(['cpp', 'python', 'java', 'csharp']),
            'code' => $this->faker->paragraph(5),
            'user_id' => \App\Models\User::factory(),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'wrong_answer', 'error', 'runtime_error', 'memory_limit_exceeded', 'time_limit_exceeded', 'compile_error']),
            'output' => $this->faker->optional()->text(),
        ];
    }
}
