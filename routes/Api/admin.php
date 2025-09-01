<?php

use App\Http\Controllers\Contest\ContestsRequestController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectsRequestController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\User\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'auth:api', 'role:admin'])
    ->prefix('/admin')->group(function () {


        Route::post('/users/{user}/toggle-block', [AdminController::class, 'blockToggle']);
        Route::group(['prefix' => '/requests/contests'], function () {
            Route::get('/', [ContestsRequestController::class, 'requests']);
            Route::post('/{contest}', [ContestsRequestController::class, 'accept']);
            Route::delete('/{contest}', [ContestsRequestController::class, 'reject']);
        });

        Route::post('/teachers', [AdminController::class, 'addTeacher']);
        Route::delete('/teachers/{user}', [AdminController::class, 'deleteTeacher']);

        Route::delete('/projects/{project}', [ProjectController::class, 'delete']);

        Route::group(['prefix' => '/statistics'], function () {
            Route::get('', [StatisticsController::class, 'overview']);
            Route::get('/students/perMonth', [StatisticsController::class, 'StudentsPerMonth']);
            Route::get('/students/lastWeek', [StatisticsController::class, 'StudentsLastWeek']);
            Route::get('/projects', [StatisticsController::class, 'overviewProjects']);


            Route::get('/budget',[StatisticsController::class,'overviewBudget']);
        });

        Route::put('payment',[AdminController::class, 'payment']);
    });
