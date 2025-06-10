<?php

use App\Http\Controllers\Contest\ContestController;
use App\Http\Controllers\Contest\SubmissionController;
use App\Http\Controllers\ProblemContainer;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {


    Route::middleware(['auth:api'])->group(function () {

        Route::post('/contests/{contest}/questions', [SubmissionController::class, 'submitContest'])->middleware(['contest:quiz', 'role:student'])->name('submissions.store');
        Route::get('/contests/{contest}/questions', [ContestController::class, 'questions'])->middleware('contest:quiz')->name('submissions.index');
        Route::get('/contests/{contest}/standing', [ContestController::class, 'standing']);

        // todo test and refactor that requests
        Route::get('/contests/{contest}/problems', [ContestController::class, 'problems'])->middleware('contest:programming')->name('submissions.index');
        Route::get('/contests/{contest}/problems/{problem}', [ProblemContainer::class, 'show'])->middleware('contest:programming')->name('submissions.index');
        Route::post('/contests/{contest}/problems/{problem}/submit', [SubmissionController::class, 'submitProblem'])->middleware('contest:programming');
    });
    Route::get('/contests', [ContestController::class, 'index'])->name('contests.index');
    Route::get('/contests/{contest}', [ContestController::class, 'show'])->name('contests.show');



});
