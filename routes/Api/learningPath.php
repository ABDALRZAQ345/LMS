<?php

use App\Http\Controllers\LearningPaths\LearningPathController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale'])->group(function () {

    Route::get('/learningPath',[LearningPathController::class,'index']);
    Route::get('/learningPath/{learningPath}',[LearningPathController::class,'showLearningPath']);

    Route::middleware(['auth:api'])->group(function () {
        Route::put('/learningPath/{learningPath}', [LearningPathController::class, 'updateStatusLearningPath']);
        Route::delete('learningPath/{learningPath}', [LearningPathController::class, 'removeStatusLearningPath']);

    });



});
