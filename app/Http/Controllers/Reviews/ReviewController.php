<?php

namespace App\Http\Controllers\Reviews;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reviews\storeReviewRequest;
use App\Http\Requests\Reviews\updateReviewRequest;
use App\Models\Course;
use App\Services\Reviews\ReviewService;

class ReviewController extends Controller
{
    public $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function getAllReviewsInCourse(Course $course)
    {
        return $this->reviewService->getAllReviewsInCourse($course);
    }

    public function addNewReviewInCourse(Course $course, storeReviewRequest $request)
    {
        $validated = $request->validated();

        return $this->reviewService->addNewReviewInCourse($course, $validated);
    }

    public function updateReviewInCourse(Course $course, updateReviewRequest $request)
    {
        $validated = $request->validated();

        return $this->reviewService->updateReviewInCourse($course, $validated);
    }

    public function deleteReviewInCourse(Course $course)
    {
        return $this->reviewService->deleteReviewInCourse($course);
    }
}
