<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LikeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'likeable_id' => function () {
                // You can adjust this to point to any model you want to test likes for
                return \App\Models\Course::factory();
            },
            'likeable_type' => 'App\\Models\\Course', // Default to Course, can be changed in seeder
        ];
    }
} 