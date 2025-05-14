<?php

namespace Database\Factories;

use App\Models\User;
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
            'title' => $this->faker->sentence(3),
            'image' => $this->faker->imageUrl(300, 200, 'education'),
            'description' => $this->faker->paragraph(3),
            'user_id' => User::factory()->state(['role' => 'teacher']),
            'price' => $this->faker->numberBetween(0, 200),
            'verified' => $this->faker->boolean(80),
            'rate'=>$this->faker->numberBetween(0, 5),
        ];
    }
}
