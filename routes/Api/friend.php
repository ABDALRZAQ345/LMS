<?php

use App\Http\Controllers\User\FriendController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss'])->group(function () {

    Route::get('/users/{user}/friends', [FriendController::class, 'index'])->name('user.friends');
    Route::middleware(['auth:api'])->group(function () {
        Route::post('/friends/{friend}', [FriendController::class, 'store'])->name('user.friends.add');
        Route::delete('/friends/{friend}', [FriendController::class, 'destroy'])->name('user.friends.remove');

    });

});
