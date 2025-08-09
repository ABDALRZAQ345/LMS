<?php

use App\Http\Controllers\CloudinaryController;
use App\Http\Controllers\Contest\ContestController;
use App\Http\Controllers\Project\ProjectsRequestController;
use App\Http\Controllers\User\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'auth:api', 'role:teacher'])
    ->prefix('teacher')->group(function () {
        //todo make it in frontend side
        Route::get('/cloudinary-signature', [CloudinaryController::class, 'getSignature']);

        Route::group(['prefix' => '/requests/projects'], function () {
            Route::get('/', [ProjectsRequestController::class, 'requests']);
            Route::post('/{project}', [ProjectsRequestController::class, 'accept']);
            Route::delete('/{project}', [ProjectsRequestController::class, 'reject']);
        });

        Route::post('/contests/quiz', [ContestController::class, 'CreateQuizContest']);
        Route::post('/contests/programming',[ContestController::class, 'CreateProgrammingContest']);
        Route::get('/my_contests', [TeacherController::class, 'myContests']);

        Route::post('/courses/{course}/tests',[TeacherController::class, 'createTest']);
        Route::put('/courses/{course}/tests/{test}',[TeacherController::class, 'updateTest']);
        Route::delete('/courses/{course}/tests/{test}',[TeacherController::class, 'deleteTest']);
    });

