<?php

use App\Http\Controllers\Courses\CourseController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {

    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{course}', [CourseController::class, 'showCourse']);
    Route::post('courses/{course}/enroll', [CourseController::class, 'enrollCourse']);

    Route::get('/learningPath/{learningPath}/courses', [CourseController::class, 'getAllCoursesInLearningPath']);
    Route::get('/learningPath/{learningPath}/courses/{course}', [CourseController::class, 'showCourseInLearningPath']);
});
