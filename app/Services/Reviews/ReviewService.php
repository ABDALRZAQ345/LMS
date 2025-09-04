<?php

namespace App\Services\Reviews;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Reviews\ReviewResource;
use App\Jobs\SendFirebaseNotification;
use App\Repositories\Reviews\ReviewsRepository;
use App\Services\Videos\VideoService;

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
        $student = auth()->user();
        $isEnroll = VideoService::isEnroll($student->id, $course->id);
        if(!$isEnroll) {
            return ResponseHelper::jsonResponse([],'If you are not Enrolled you cannot add a Review.',403,false);
        }
        $review = $this->reviewRepository->addNewReviewInCourse($course->id, $validated);
        if ($review['is_new']) {
            $teacher = $course->teacher()->first();
            $title = 'New Review on '. $course->title;
            $body  = $student->name .'has added a new review '.$review['review'];

            SendFirebaseNotification::dispatch($teacher, $title, $body);
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
