<?php

use App\Http\Controllers\Contest\ContestController;
use App\Http\Controllers\Contest\ProblemContainer;
use App\Http\Controllers\Contest\SubmissionController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'auth:api'])->group(function () {

    Route::get('/courses/{course}/tests/{test}', [QuizController::class, 'showTest']);

    Route::post('/courses/{course}/tests/{test}', [QuizController::class, 'SubmitTest']);

});
