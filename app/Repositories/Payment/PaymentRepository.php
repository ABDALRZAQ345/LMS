<?php

namespace App\Repositories\Payment;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentIntent;
use Stripe\Stripe;
class PaymentRepository
{
    public function enrollCourse($paymentMethod, $course)
    {
        $user = auth()->user();

        Stripe::setApiKey(config('services.stripe.secret'));

        $amount = $course->price * 100; // in cents

        $intent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'payment_method' => $paymentMethod['payment_method_id'],
            'confirmation_method' => 'manual',
            'confirm' => true,
            'return_url' => route('courses.show', $course->id),
            'use_stripe_sdk' => true,
            'metadata' => [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
        ]);

        return $intent;
    }


    public function attachCourse(Course $course, int $amountInCents)
    {
        $user = auth()->user();

        $user->verifiedCourses()->syncWithoutDetaching([
            $course->id => [
                'paid' => $amountInCents / 100,
                'status' => 'enrolled',
            ]
        ]);
    }

}
