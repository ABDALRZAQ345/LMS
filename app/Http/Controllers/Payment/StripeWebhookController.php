<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Jobs\SendFirebaseNotification;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;

            $userId = $intent->metadata->user_id;
            $courseId = $intent->metadata->course_id;
            $amount = $intent->amount / 100;

            $user = User::find($userId);
            $course = Course::find($courseId);

            if ($user && $course) {
                $user->studentCourses()->syncWithoutDetaching([
                    $courseId => [
                        'paid' => $amount,
                        'status' => 'enrolled',
                    ]
                ]);
            }

        }

        $title = 'Enroll Course Successfully';
        $body ="Have a nice trip.";

        SendFirebaseNotification::dispatch($user, $title, $body);
        return response()->json(['message' => 'Webhook received.'], 200);
    }

}
