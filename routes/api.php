<?php

use App\Http\Controllers\HomePageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

    Route::get('/homepage', HomepageController::class);
});
Route::post('/submit', [\App\Http\Controllers\Contest\SubmissionController::class, 'submit']);
Route::post('/ai_chat',[\App\Http\Controllers\GeminiAgent::class,'message']);
