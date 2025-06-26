<?php

namespace App\Repositories\Reviews;

use App\Models\Review;

class ReviewsRepository
{
    public function getAllReviewsInCourse($courseId)
    {
        $userId = auth('api')->id();
        $userReview = Review::with(['student:id,name,image'])
            ->where('course_id', $courseId)
            ->where('user_id', $userId)
            ->first();

        $otherReviews = Review::with(['student:id,name,image'])
            ->where('user_id', '!=', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $userReview
            ? collect([$userReview])->merge($otherReviews)
            : $otherReviews;
    }

    public function addNewReviewInCourse($courseId, $validated)
    {
        $userId = auth()->id();
        $existingReview = Review::with(['student:id,name,image'])
            ->where('course_id', $courseId)
            ->where('user_id', $userId)
            ->first();

        if ($existingReview) {
            return [
                'review' => $existingReview->load('student:id,name,image'),
                'is_new' => false,
            ];
        }

        $newReview = Review::create([
            'course_id' => $courseId,
            'user_id' => $userId,
            'rate' => $validated['rate'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return [
            'review' => $newReview->load('student:id,name,image'),
            'is_new' => true,
        ];

    }

    public function updateReviewInCourse($courseId, $validated)
    {
        $userId = auth()->id();
        $review = Review::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->first();

        if (! $review) {
            return null;
        }

        $review->update($validated);

        return $review->load('student:id,name,image');

    }

    public function deleteReviewInCourse($courseId)
    {
        $userId = auth()->id();

        return Review::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->delete();

    }
}
