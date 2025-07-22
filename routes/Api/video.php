<?php

use App\Http\Controllers\Videos\VideoController;
use App\Http\Controllers\Videos\TeacherVideoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {
    Route::get('courses/{course}/video/{video}',[VideoController::class,'show']);
    Route::post('courses/{course}/video/{video}/updateProgress',[VideoController::class,'updateProgress']);
    Route::put('courses/{course}/video/{video}/completed',[VideoController::class,'finishedVideo']);

});


Route::middleware(['throttle:api', 'locale', 'auth:api', 'role:teacher'])
    ->prefix('teacher')->group(function () {
        Route::get('courses/{course}/video',[TeacherVideoController::class,'index']);
        Route::get('courses/{course}/video/{video}',[TeacherVideoController::class,'show']);
        Route::post('video_url',[TeacherVideoController::class,'createUrl']);
        Route::put('video_url/{video}',[TeacherVideoController::class,'updateUrl']);
        Route::delete('video_url/{video}',[TeacherVideoController::class,'deleteUrl']);

        Route::post('upload_video',[TeacherVideoController::class,'uploadVideo']);
        Route::post('upload_video/{video}',[TeacherVideoController::class,'updateUploadVideo']);
        Route::delete('upload_video/{video}',[TeacherVideoController::class,'deleteUploadVideo']);


});
