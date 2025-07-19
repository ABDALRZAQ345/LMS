<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
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
            'description' => $this->faker->paragraph(2),
            'order' => 1, // This will be set in the seeder
            'url' => 'https://youtu.be/lCMlS4jOaEk',
            'free' => $this->faker->boolean(30),
            'course_id' => Course::factory(),
        ];
    }
}
