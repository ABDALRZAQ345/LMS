<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contest>
 */
class ContestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = $this->faker->dateTimeBetween('now', '+2 months');

        return [
            'name' => $this->faker->sentence(3),
            'time' => $this->faker->numberBetween(30, 180),
            'description' => $this->faker->paragraph(2),
            'status' => $this->faker->randomElement(['active', 'ended', 'coming']),
            'start_at' => $startAt,
            'user_id' => User::factory()->state(['role' => 'teacher']),
        ];
    }
}
