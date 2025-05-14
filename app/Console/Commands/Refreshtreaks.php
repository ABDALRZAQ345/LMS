<?php

namespace App\Console\Commands;

use App\Models\Streak;
use App\Models\User;
use Illuminate\Console\Command;

class Refreshtreaks extends Command
{
    protected $signature = 'streaks:refresh';

    protected $description = 'Reset and regenerate streaks at the beginning of the year';

    public function handle()
    {
        $this->info('Deleting old streaks...');
        Streak::truncate();

        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();
        $now = now();

        $this->info('Generating streaks...');
        User::chunk(100, function ($users) use ($startOfYear, $endOfYear, $now) {
            foreach ($users as $user) {
                $date = $startOfYear->copy();
                $streaks = [];

                while ($date <= $endOfYear) {

                    $streaks[] = [
                        'user_id' => $user->id,
                        'date' => $date->toDateString(),
                        'status' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];

                    $date->addDay();
                }

                Streak::insert($streaks);
            }

        });

        $this->info('Streaks refreshed successfully.');
    }
}
