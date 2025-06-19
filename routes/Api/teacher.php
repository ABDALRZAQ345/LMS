<?php

use App\Http\Controllers\Contest\ContestController;
use App\Http\Controllers\User\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'auth:api', 'role:teacher'])
    ->prefix('teacher')->group(function () {

        Route::post('/contests/quiz', [ContestController::class, 'CreateQuizContest']);
        Route::post('/contests/programming',[ContestController::class, 'CreateProgrammingContest']);
        Route::get('/my_contests', [TeacherController::class, 'myContests']);
    });
Route::post('/contests/programming',[ContestController::class, 'CreateProgrammingContest']);
