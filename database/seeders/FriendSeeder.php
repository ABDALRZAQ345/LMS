<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all students
        $students = User::where('role', 'student')->get();

        // For each student, create 2-5 random friendships
        foreach ($students as $student) {
            $numFriends = rand(2, 5);
            $potentialFriends = $students->where('id', '!=', $student->id);

            // Get random friends that aren't already friends
            $friends = $potentialFriends->random(min($numFriends, $potentialFriends->count()));

            foreach ($friends as $friend) {

                $student->friends()->attach($friend->id);
            }
        }
    }
}
