<?php

namespace App\Services\Reviews;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Reviews\ReviewResource;
use App\Repositories\Reviews\ReviewsRepository;

class ReviewService
{
    public $reviewRepository;

    public function __construct(ReviewsRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function getAllReviewsInCourse($course, $items)
    {
        $reviews = $this->reviewRepository->getAllReviewsInCourse($course->id, $items);
        $data = [
            'reviews' => ReviewResource::collection($reviews),
            'total_pages' => $reviews->lastPage(),
            'current_page' => $reviews->currentPage(),
            'hasMorePages' => $reviews->hasMorePages(),
        ];
        return ResponseHelper::jsonResponse($data, 'Get All Reviews In '. $course->title.' Course Successfully');
    }

    public function addNewReviewInCourse($course, $validated)
    {
        $review = $this->reviewRepository->addNewReviewInCourse($course->id, $validated);

        if ($review['is_new']) {
            return ResponseHelper::jsonResponse(ReviewResource::make($review['review']), 'Review Added Successfully');
        } else {
            return ResponseHelper::jsonResponse(ReviewResource::make($review['review']),
                'You already reviewed this course. You can update your review.');
        }
    }

    public function updateReviewInCourse($course, $validated)
    {
        $review = $this->reviewRepository->updateReviewInCourse($course->id, $validated);

        if (! $review) {
            return ResponseHelper::jsonResponse([], 'You don\'t have a review to update', 404, false);
        }

        return ResponseHelper::jsonResponse(ReviewResource::make($review), 'Your Review Updated Successfully');
    }

    public function deleteReviewInCourse($course)
    {
        $remove = $this->reviewRepository->deleteReviewInCourse($course->id);
        if ($remove) {
            return ResponseHelper::jsonResponse([], 'Your Review deleted successfully');
        } else {
            return ResponseHelper::jsonResponse([], 'No review found to delete.', 404, false);
        }
    }
}
