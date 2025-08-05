<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => \Hash::make('1234/*-+Asa'),
            'email_verified' => true,
            'role' => 'admin',
        ]);
        User::firstOrCreate([
            'email' => 'student@student.com',
        ], [
            'name' => 'student',
            'email' => 'student@student.com',
            'password' => \Hash::make('1234/*-+Asa'),
            'email_verified' => true,
            'role' => 'student',
        ]);
        User::firstOrCreate([
            'email' => 'teacher@teacher.com',
        ], [
            'name' => 'teacher',
            'email' => 'teacher@teacher.com',
            'password' => \Hash::make('1234/*-+Asa'),
            'email_verified' => true,
            'role' => 'teacher',
        ]);

\
        User::factory()->count(20)->create([
            'role' => 'student',
            'email_verified' => true,
        ]);
        User::factory()->count(10)->create([
            'role' => 'teacher',
            'email_verified' => true,
        ]);

    }
}
