<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(100)->create([
            'role' => 'student'
        ]);
        User::factory()->count(10)->create([
            'role' => 'teacher'
        ]);
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => \Hash::make('1234/*-+Asa'),
            'role' => 'admin'
        ]);


    }
}
