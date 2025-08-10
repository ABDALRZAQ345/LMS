<?php

use App\Http\Controllers\Contest\SubmissionController;
use App\Http\Controllers\AiAgent;
use App\Http\Controllers\HomePageController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'auth:api'])->group(function () {

   // Route::post('/message/send',[AiAgent::class,'message'])->middleware(['throttle:AiChat']);
    //Route::post('/message/receive',[AiAgent::class,'receive'])->middleware('throttle:AiChat');
    Route::get('/homepage', HomepageController::class);
});

