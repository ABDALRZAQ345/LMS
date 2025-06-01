<?php

use App\Http\Controllers\Contest\ContestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api', 'role:teacher'])
    ->prefix('teacher')->group(function () {

        Route::post('/contests', [ContestController::class, 'store']);

    });
