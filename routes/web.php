<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'common nigga',
    ]);
})->middleware(['locale', 'throttle:api', 'xss']);
