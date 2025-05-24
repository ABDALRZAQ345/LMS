<?php

use App\Http\Controllers\ContestController;
use App\Http\Controllers\ProblemContainer;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

    Route::get('/contests', [ContestController::class, 'index'])->name('contests.index');
    Route::get('/contests/{contest}', [ContestController::class, 'show'])->name('contests.show');
    Route::get('/contests/{contest}/questions', [ContestController::class, 'questions'])->middleware('contest:quiz')->name('submissions.index');
    Route::post('/contests/{contest}/questions', [SubmissionController::class, 'submitContest'])->middleware('contest:quiz')->name('submissions.store');
    //todo refactor those
    Route::get('/contests/{contest}/problems', [ContestController::class, 'problems'])->middleware('contest:programming')->name('submissions.index');
    Route::get('/contests/{contest}/problems/{problem}', [ProblemContainer::class, 'show'])->middleware('contest:programming')->name('submissions.index');
    Route::post('/contests/{contest}/problems/{problem}/submit', [SubmissionController::class, 'submitProblem'])->middleware('contest:programming');
    //todo in teacher request add creating contest

});
