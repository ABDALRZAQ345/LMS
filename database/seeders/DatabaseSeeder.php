<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // creating the admin for example
        User::create([
            'name' => 'name',
            'email' => 'jmalj@gmail.com',
            'password' => \Hash::make('123B4e$$'),
        ]);
    }
}
