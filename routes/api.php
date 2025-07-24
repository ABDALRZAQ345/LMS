<?php

use App\Http\Controllers\Contest\SubmissionController;
use App\Http\Controllers\GeminiAgent;
use App\Http\Controllers\HomePageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

    Route::post('/ai_chat',[GeminiAgent::class,'message'])->middleware('throttle:AiChat');
    Route::get('/homepage', HomepageController::class);
});
Route::post('/submit', [SubmissionController::class, 'submit']);

