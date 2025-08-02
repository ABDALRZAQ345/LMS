<?php

use App\Http\Controllers\LearningPaths\AdminLearningPathController;
use App\Http\Controllers\LearningPaths\LearningPathController;
use App\Http\Controllers\LearningPaths\TeacherLearningPathController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale'])->group(function () {

    Route::get('/learningPath',[LearningPathController::class,'index']);
    Route::get('/learningPath/{learningPath}',[LearningPathController::class,'showLearningPath']);

    Route::middleware(['auth:api'])->group(function () {
        Route::put('/learningPath/{learningPath}', [LearningPathController::class, 'updateStatusLearningPath']);
        Route::delete('learningPath/{learningPath}', [LearningPathController::class, 'removeStatusLearningPath']);

    });

});

Route::middleware(['throttle:api', 'locale', 'auth:api', 'role:teacher'])
    ->prefix('teacher')->group(function () {
        Route::get('myLearningPaths',[TeacherLearningPathController::class,'myLearningPaths']);
        Route::get('learningPaths/{learningPath}',[TeacherLearningPathController::class,'show']);
        Route::post('learningPaths',[TeacherLearningPathController::class,'create']);
        Route::post('learningPaths/{learningPath}',[TeacherLearningPathController::class,'update']);
        Route::delete('learningPaths/{learningPath}',[TeacherLearningPathController::class,'delete']);

    });

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api', 'role:admin'])
    ->prefix('/admin')->group(function () {
        Route::get('learningPaths',[AdminLearningPathController::class,'index']);
        Route::post('learningPaths/{learningPath}/accept',[AdminLearningPathController::class,'accept']);
        Route::delete('learningPaths/{learningPath}/reject',[AdminLearningPathController::class,'reject']);

        Route::post('learningPaths',[TeacherLearningPathController::class,'create']);
        Route::post('learningPaths/{learningPath}',[TeacherLearningPathController::class,'update']);
        Route::delete('learningPaths/{learningPath}',[TeacherLearningPathController::class,'delete']);

    });
