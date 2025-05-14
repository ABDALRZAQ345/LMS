<?php

use App\Http\Controllers\LearningPaths\LearningPathController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {

    Route::get('/learningPath', [LearningPathController::class, 'getAllLearningPaths']);
    Route::get('/learningPath/{id}', [LearningPathController::class, 'showLearningPath']);

});
