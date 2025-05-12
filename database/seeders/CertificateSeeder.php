<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students=User::where('role','student')->get();
        foreach ($students as $student) {
            Certificate::factory(2)->create(['user_id' => $student->id]);
        }
    }
}
