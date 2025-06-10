<?php

use App\Http\Controllers\Contest\ContestController;
use App\Http\Controllers\User\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api', 'role:teacher'])
    ->prefix('teacher')->group(function () {

        Route::post('/contests', [ContestController::class, 'store']);
        Route::get('/my_contests', [TeacherController::class, 'myContests']);
    });
