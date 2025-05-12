<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VerificationCode>
 */
class VerificationCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'code' => $this->faker->unique()->numerify('######'),
            'registration' => $this->faker->boolean(70),
            'expires_at' => now()->addHours(24),
            'verified_at' => null,
        ];
    }
}
