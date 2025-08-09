<?php

use App\Http\Controllers\AiAgent;
use App\Http\Controllers\Contest\ContestsRequestController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\Project\ProjectsRequestController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\User\AdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api'])->group(function () {

    Route::post('/agent/send',[AiAgent::class,'SendToAgent'])->middleware(['throttle:AiChat']);

    Route::get('/agent',[AiAgent::class,'getChatHistory']);

    Route::delete('/agent',[AiAgent::class,'clearChatHistory']);

});
