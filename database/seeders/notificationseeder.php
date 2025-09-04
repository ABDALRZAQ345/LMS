<?php

namespace Database\Seeders;

use App\Models\NotificationModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class notificationseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotificationModel::factory(30)->create();
    }
}
