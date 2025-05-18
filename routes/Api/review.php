<?php

use App\Http\Controllers\Reviews\ReviewController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {

    Route::get('courses/{course}/reviews', [ReviewController::class, 'getAllReviewsInCourse']);
    Route::post('courses/{course}/reviews',[ReviewController::class, 'addNewReviewInCourse']);
    Route::put('courses/{course}/reviews',[ReviewController::class,'updateReviewInCourse']);
    Route::delete('courses/{course}/reviews',[ReviewController::class ,'deleteReviewInCourse']);

});
