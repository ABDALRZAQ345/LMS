<?php

use App\Http\Controllers\Project\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::get('/tags', [ProjectController::class, 'getTags']);

    Route::middleware(['auth:api'])->group(function () {
        Route::post('/projects', [ProjectController::class, 'store'])->middleware('role:student');
    });

});
