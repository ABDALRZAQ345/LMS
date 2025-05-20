<?php

use App\Http\Controllers\LearningPaths\LearningPathController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {

    Route::get('/learningPath', [LearningPathController::class, 'getAllLearningPaths']);
    Route::get('/learningPath/{learningPath}', [LearningPathController::class, 'showLearningPath']);

    Route::put('/learningPath/{learningPath}', [LearningPathController::class, 'updateStatusLearningPath']);
    Route::delete('learningPath/{learningPath}', [LearningPathController::class, 'removeStatusLearningPath']);

});
