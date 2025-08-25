<?php

namespace App\Repositories\Payment;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentIntent;
use Stripe\Stripe;
class PaymentRepository
{
    public function charge($paymentMethod)
    {
        $user = auth()->user();

        Stripe::setApiKey(config('services.stripe.secret'));

        $amount = $paymentMethod['amount'] * 100; // in cents

        $intent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'automatic_payment_methods' => ['enabled' => true], // يخلي Stripe يقرر طريقة الدفع
            'metadata' => ['user_id' => $user->id],
        ]);

        return $intent;
    }


}
