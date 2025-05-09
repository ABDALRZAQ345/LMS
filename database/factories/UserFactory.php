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
            'email' => $this->faker->unique()->safeEmail(),
            'password' => \Hash::make($this->faker->password()),
            'fcm_token' => $this->faker->uuid(),
            'image' => $this->faker->imageUrl(),
            'gitHub_account' => $this->faker->url(),
            'bio' => $this->faker->text(),
            'points' => $this->faker->randomNumber(),
            'last_online' => $this->faker->dateTime(),
            'role' => $this->faker->randomElement(['admin', 'student','teacher']),

        ];
    }
}
