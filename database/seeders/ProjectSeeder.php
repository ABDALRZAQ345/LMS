<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags=Tag::all();
        Project::factory(10)->create([
            'tag_id' => $tags->random()->id,
            'user_id' => rand(1,20),
        ]);
    }
}
