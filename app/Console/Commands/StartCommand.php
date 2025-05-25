<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

class StartCommand extends Command
{
    protected $signature = 'app:start';

    protected $description = 'starting the application';

    /**
     * @throws \Throwable
     */
    public function handle( ): void {}
}
