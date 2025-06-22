<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\EnrollCourseRequest;
use App\Models\Course;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    public $paymentService;
    public function __construct(PaymentService $paymentService){
        $this->paymentService = $paymentService;
    }

    public function enrollCourse(EnrollCourseRequest $request, Course $course){

        $user = auth()->user();

        if ($user->verifiedCourses()->where('course_id', $course->id)->exists()) {
            return ResponseHelper::jsonResponse([], 'You are already enrolled in this course.');
        }

        if ($course->price == 0) {
            $user->verifiedCourses()->syncWithoutDetaching([
                $course->id => [
                    'paid' => 0,
                    'status' => 'enrolled',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
            return ResponseHelper::jsonResponse([], 'You have been enrolled for free.');
        }

        $validated = $request->validated();
        return $this->paymentService->enrollCourse($validated,$course);
    }
}
