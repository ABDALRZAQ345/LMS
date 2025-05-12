<?php

use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::post('/profile/update', [UserController::class, 'update'])->name('profile.update');
    Route::get('/users/{user}/achievements', [UserController::class, 'achievements'])->name('user.achievements');
    Route::get('/users/{user}/certificates', [UserController::class, 'certificates'])->name('user.certificates');
    Route::get('/users/{user}/contests', [UserController::class, 'contests'])->name('user.contests');
});
