<?php

use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    dispatch(new \App\Jobs\DeleteExpiredCodes);
})->daily();

Schedule::call(function () {
    Artisan::call('streaks:refresh');
})->yearlyOn(1, 1, '00:00');
