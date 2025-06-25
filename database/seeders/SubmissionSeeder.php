<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Submission::factory(30)->create();
    }
}
