<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = Tag::all();

        for ($i = 0; $i < 15; $i++) {
            Project::factory()->create([
                'tag_id' => $tags->random()->id,
                'user_id' => rand(1, 20),

            ]);
        }
    }
}
