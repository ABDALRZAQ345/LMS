<?php

use App\Http\Controllers\FriendController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/{project}', [ProjectController::class, 'show']);
    Route::post('/projects', [ProjectController::class, 'store'])->middleware('role:student');
    Route::get('/tags',[ProjectController::class, 'getTags']);
    Route::get('/admin/requests/projects',[ProjectController::class, 'requests'])->middleware('role:admin');
    Route::put('/admin/requests/projects/{project}', [ProjectController::class, 'updateStatus'])->middleware('role:admin');
});
