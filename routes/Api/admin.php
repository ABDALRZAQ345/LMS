<?php

use App\Http\Controllers\Contest\ContestsRequestController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\User\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api', 'role:admin'])
    ->prefix('/admin')->group(function () {

        Route::group(['prefix' => '/requests/projects'], function () {
            Route::get('/', [ProjectController::class, 'requests']);
            Route::post('/{project}', [ProjectController::class, 'accept']);
            Route::delete('/{project}', [ProjectController::class, 'reject']);
        });

        Route::group(['prefix' => '/requests/contests'], function () {
            Route::get('/', [ContestsRequestController::class, 'requests']);
            Route::post('/{contest}', [ContestsRequestController::class, 'accept']);
            Route::delete('/{contest}', [ContestsRequestController::class, 'reject']);
        });

        Route::post('/teachers', [AdminController::class, 'addTeacher']);
        Route::delete('/projects/{project}', [ProjectController::class, 'delete']);

    });
