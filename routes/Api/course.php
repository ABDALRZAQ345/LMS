<?php

use App\Http\Controllers\Courses\AdminCourseController;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\Courses\TeacherCourseContrller;
use App\Http\Controllers\Payment\StripePaymentController;
use App\Http\Controllers\Payment\StripeWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

Route::middleware(['throttle:api', 'locale'])->group(function () {


    Route::middleware(['auth:api'])->group(function () {
        Route::post('courses/{course}/enroll', [StripePaymentController::class, 'enrollCourse']);
        Route::post('courses/{course}/watch_later',[CourseController::class, 'addToWatchLater']);
        Route::delete('courses/{course}/watch_later',[CourseController::class, 'removeFromWatchLater']);
    });
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{course}/description', [CourseController::class, 'showCourseDescription'])->name('courses.show');;
    Route::get('courses/{course}/content', [CourseController::class, 'showCourseContent']);
    Route::get('/learningPath/{learningPath}/courses', [CourseController::class, 'getAllCoursesInLearningPath']);
//    Route::get('/learningPath/{learningPath}/courses/{course}', [CourseController::class, 'showCourseInLearningPath']);
});



Route::middleware(['throttle:api', 'locale', 'auth:api', 'role:teacher'])
    ->prefix('teacher')->group(function () {
        Route::get('myCourses', [TeacherCourseContrller::class, 'myCourses']);
        Route::get('courses', [TeacherCourseContrller::class, 'getAllVerifiedCourses']);
        Route::get('courses/{course}/description', [TeacherCourseContrller::class, 'showCourseDescription']);
        Route::get('courses/{course}/content', [TeacherCourseContrller::class, 'showCourseContent']);
        Route::get('courses/myVerifiedCourses', [TeacherCourseContrller::class, 'getMyVerifiedCourses']);
        Route::post('courses',[TeacherCourseContrller::class, 'create']);
        Route::post('courses/{course}',[TeacherCourseContrller::class, 'update']);
        Route::delete('courses/{course}',[TeacherCourseContrller::class , 'delete']);

        Route::post('courses/{course}/reorder-contest',[TeacherCourseContrller::class, 'reorderContent']);

    });


Route::middleware(['throttle:api', 'locale', 'xss', 'auth:api', 'role:admin'])
    ->prefix('/admin')->group(function () {
        Route::get('courses', [AdminCourseController::class, 'index']);
        Route::post('requests/courses/{course}',[AdminCourseController::class, 'accept']);
        Route::delete('requests/courses/{course}',[AdminCourseController::class, 'reject']);
    });

