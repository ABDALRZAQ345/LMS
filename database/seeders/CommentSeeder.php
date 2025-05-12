<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all videos
        $videos = Video::all();
        // Get all users
        $users = User::all();

        // For each video, create 2-5 comments
        foreach ($videos as $video) {
            $numComments = rand(2, 5);
            for ($i = 0; $i < $numComments; $i++) {
                Comment::factory()->create([
                    'video_id' => $video->id,
                    'user_id' => $users->random()->id,
                ]);
            }
        }
    }
}
