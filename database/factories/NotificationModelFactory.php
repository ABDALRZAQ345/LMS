<?php

namespace Database\Factories;

use App\Models\NotificationModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationModelFactory extends Factory
{
    protected $model = NotificationModel::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'message' => $this->faker->paragraph(),
            'user_id' => 2,
        ];
    }
}
