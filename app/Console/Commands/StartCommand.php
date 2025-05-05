<?php

namespace App\Console\Commands;

use App\Models\Habit;
use App\Services\HabitLogService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StartCommand extends Command
{
    protected $signature = 'app:start';

    protected $description = 'starting the application';

    /**
     * @throws \Throwable
     */
    public function handle(HabitLogService $habitLogService): void
    {


    }
}
