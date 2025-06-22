<?php

use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\Payment\StripePaymentController;
use App\Http\Controllers\Payment\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::middleware('auth:api')->group(function () {

    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{course}/description', [CourseController::class, 'showCourseDescription'])->name('courses.show');;
    Route::get('courses/{course}/content', [CourseController::class, 'showCourseContent']);
    Route::post('courses/{course}/enroll', [StripePaymentController::class, 'enrollCourse']);


    Route::get('/learningPath/{learningPath}/courses', [CourseController::class, 'getAllCoursesInLearningPath']);
    Route::get('/learningPath/{learningPath}/courses/{course}', [CourseController::class, 'showCourseInLearningPath']);
});
