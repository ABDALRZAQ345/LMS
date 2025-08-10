<?php

use App\Http\Controllers\LikeController;
use App\Http\Controllers\Project\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale'])->group(function () {

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::get('/tags', [ProjectController::class, 'getTags']);

    Route::middleware(['auth:api'])->group(function () {
        Route::post('/projects', [ProjectController::class, 'store'])->middleware('role:student');
        Route::post('/projects/{project}/like', [LikeController::class, 'LikeProject']);
        Route::delete('/projects/{project}/like', [LikeController::class, 'DeleteProjectLike']);

    });

});
