<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Project\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api', 'role:admin'])->prefix('/admin')->group(function () {

    Route::post('/teachers', [AdminController::class, 'addTeacher']);
    Route::get('/requests/projects', [ProjectController::class, 'requests']);
    Route::put('/requests/projects/{project}', [ProjectController::class, 'updateStatus']);
    Route::delete('/projects/{project}', [ProjectController::class, 'delete']);
});
