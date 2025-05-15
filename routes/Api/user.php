<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/me/update', [UserController::class, 'update'])->name('profile.update');

    Route::group(['middleware' => ['student.user']], function () {
        Route::get('/users/{user}/achievements', [StudentController::class, 'achievements'])->name('user.achievements');
        Route::get('/users/{user}/certificates', [StudentController::class, 'certificates'])->name('user.certificates');
        Route::get('/users/{user}/contests', [StudentController::class, 'contests'])->name('user.contests');
        Route::get('/users/{user}/streaks', [StudentController::class, 'streaks'])->name('user.streaks');
        Route::get('/users/{user}/statistics', [StudentController::class, 'statistics'])->name('user.statistics');
    });

    Route::group(['middleware' => ['teacher.user']], function () {
        Route::get('/users/{user}/created_courses', [TeacherController::class, 'courses']);
        Route::get('/users/{user}/created_learning_paths', [TeacherController::class, 'learningPaths']);
        Route::get('/users/{user}/created_contest', [TeacherController::class, 'contests']);
    });

});
