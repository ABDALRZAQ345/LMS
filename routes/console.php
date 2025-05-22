<?php

use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    dispatch(new \App\Jobs\DeleteExpiredCodes);
})->daily();

Schedule::call(function () {
    Artisan::call('streaks:refresh');
})->yearlyOn(1, 1, '00:00');

Schedule::call(function () {

    \App\Models\Contest::where('start_at', '<=', now())
        ->whereRaw('DATE_ADD(start_at, INTERVAL time MINUTE) > ?', [now()])
        ->update([
            'status' => 'active'
        ]);
    \App\Models\Contest::whereRaw('DATE_ADD(start_at, INTERVAL time MINUTE) <= ?', [now()])
        ->update([
            'status' => 'ended'
        ]);
})->everyMinute();
