<?php

namespace App\Observers;

use App\Models\Review;

class ReviewObserver
{
    public function created(Review $review)
    {
        $this->updateCourseRate($review);
    }

    public function updated(Review $review)
    {
        $this->updateCourseRate($review);
    }

    public function deleted(Review $review)
    {
        $this->updateCourseRate($review);
    }

    private function updateCourseRate(Review $review)
    {
        $course = $review->course;
        $average = $course->reviews()->avg('rate');
        $course->update(['rate' => $average]);
    }
}
