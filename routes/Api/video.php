<?php

use App\Http\Controllers\Videos\VideoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {
    Route::get('courses/{course}/video/{video}',[VideoController::class,'show']);
    Route::post('courses/{course}/video/{video}/updateProgress',[VideoController::class,'updateProgress']);
    Route::put('courses/{course}/video/{video}/completed',[VideoController::class,'finishedVideo']);


});
