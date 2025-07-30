<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
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
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'refused']),
            'image' => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRnmLs4t7zqejredfdRV6Magj8ZxaUGlhW_AQ&s"
        ];
    }
}
