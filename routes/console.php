<?php

use App\Jobs\DeleteExpiredCodes;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    dispatch(new DeleteExpiredCodes);
})->daily();

Schedule::call(function () {
    Artisan::call('streaks:refresh');
})->yearlyOn(1, 1, '00:00');

Schedule::command('contests:update-statuses')->everyThirtySeconds();
