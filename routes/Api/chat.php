<?php

use App\Http\Controllers\AiAgent;

use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:api', 'locale', 'auth:api'])->group(function () {

    Route::post('/agent/send',[AiAgent::class,'SendToAgent'])->middleware(['throttle:AiChat']);

    Route::get('/agent',[AiAgent::class,'getChatHistory']);

    Route::delete('/agent',[AiAgent::class,'clearChatHistory']);

});
