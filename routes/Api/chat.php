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
    //Route::post('/message/receive',[AiAgent::class,'receive'])->middleware('throttle:AiChat');



    Route::get('/chat',function (){
        //todo add real time here
        $user = Auth::user();
        $chat=$user->chat;
        $messages=$chat->messages()->orderBy('created_at', 'desc')->paginate();
        return response()->json([
            'status' => true,
            'messages' => $messages
        ]);

    });
    Route::post('/chat',function (Request $request){
       //todo send  new message to ai
    });
    Route::delete('/chat',function (Request $request){
        $user = Auth::user();
        $chat=$user->chat;
        $chat->messages()->delete();
        return response()->json([
            'status' => true,
            'message' => 'chat history deleted successfully'
        ]);
    });

});
