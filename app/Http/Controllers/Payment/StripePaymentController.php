<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\EnrollCourseRequest;
use App\Jobs\SendFirebaseNotification;
use App\Models\Course;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    public $paymentService;
    public function __construct(PaymentService $paymentService){
        $this->paymentService = $paymentService;
    }

    public function enrollCourse(EnrollCourseRequest $request, Course $course)
    {
        $user = auth()->user();
        if (!$course->verified) {
            return ResponseHelper::jsonResponse([], 'This course is not verified yet.',404,false);
        }

        if (
            $user->studentCourses()
            ->where('course_id', $course->id)
            ->wherePivotIn('status', ['enrolled', 'finished'])
            ->exists()
        ) {
            return ResponseHelper::jsonResponse([], 'You are already enrolled in this course.');
        }

        if ($course->price == 0 || $user->id == $course->user_id || $user->role == 'admin') {
            $user->studentCourses()->syncWithoutDetaching([
                $course->id => [
                    'paid' => 0,
                    'status' => 'enrolled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            $title = 'Enroll Course '.$course->title;
            $body ="Have a nice trip.";

            SendFirebaseNotification::dispatch($user, $title, $body);
            return ResponseHelper::jsonResponse([], 'You have been enrolled for free.');
        }

        $validated = $request->validated();
        return $this->paymentService->enrollCourse($validated, $course);
    }


}
