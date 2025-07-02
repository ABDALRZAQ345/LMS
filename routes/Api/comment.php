<?php

use App\Http\Controllers\Comments\CommentController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:api'])->group(function () {
    Route::get('video/{video}/comments',[CommentController::class,'commentsOfVideo']);
    Route::post('video/{video}/comments',[CommentController::class,'create']);
    Route::put('comments/{comment}',[CommentController::class,'update']);
    Route::delete('comments/{comment}',[CommentController::class,'delete']);
    Route::post('comment/{comment}/likes',[CommentController::class,'like']);

});
