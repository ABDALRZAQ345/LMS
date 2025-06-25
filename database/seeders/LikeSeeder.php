<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Like;

class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed likes for courses
        Like::factory(30)->create([
            'likeable_type' => 'App\\Models\\Course',
        ]);
        // You can add more likeable types here if needed
    }
} 