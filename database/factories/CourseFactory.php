<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl(),
            'description' => $this->faker->paragraph(),
            'user_id' => rand(101, 110),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'verified' => rand(0, 1),
        ];
    }
}
