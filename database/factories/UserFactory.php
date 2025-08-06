<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'github_id' => $this->faker->unique()->userName(),
            'github_token' => $this->faker->uuid(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => \Hash::make($this->faker->password()),
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTkoyUQaux4PEUmEPGc7PodeN8XbgC4aOBsug&s',
            'gitHub_account' => $this->faker->url(),
            'bio' => $this->faker->text(),
            'points' => $this->faker->numberBetween(0, 10000),
            'last_online' => $this->faker->dateTime(),
            'email_verified' => $this->faker->boolean(),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced', 'expert']),
            'google_id' => $this->faker->unique()->uuid(),
            'role' => $this->faker->randomElement(['admin', 'student', 'teacher']),
            'age' => $this->faker->numberBetween(16, 60),
        ];
    }
}
