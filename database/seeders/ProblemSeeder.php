<?php

namespace Database\Seeders;

use App\Models\Problem;
use Illuminate\Database\Seeder;

class ProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Problem::create([
            'contest_id' => 1,
            'title' => 'even or odd ',
            'description' => 'you are given t test case /n and in each test case an integer x print event if its even otherwise print odd',
            'input' => '2 5 4',
            'output' => 'odd even',
            'test_input' => '5 1 2 3 4 5',
            'expected_output' => 'odd even odd even odd',
        ]);
        Problem::create([
            'contest_id' => 1,
            'title' => 'girl or boy ',
            'description' => 'you are given t test case and in each string s decide if the string is name of boy of girl
           names are ali , maya , abd , susan
           ',
            'input' => '2 ali maya',
            'output' => 'boy girl',
            'test_input' => '5 ali maya abd susan ali',
            'expected_output' => 'boy girl boy girl boy',
        ]);
    }
}
