<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
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
            // تحقق توقيع الويب هوك
            $event = Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // فشل في قراءة بيانات JSON
            Log::error('Invalid payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // فشل في التحقق من التوقيع
            Log::error('Invalid signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;

            // ⚠️ لاحظ هنا فرق: بعد تفعيل Webhook::constructEvent يصبح الوصول للكائنات بدون Array
            $userId = $intent->metadata->user_id;
            $courseId = $intent->metadata->course_id;
            $amount = $intent->amount; // in cents

            $user = User::find($userId);
            $course = Course::find($courseId);

            if ($user && $course) {
                if (!$user->verifiedCourses()->where('course_id', $courseId)->exists()) {
                    $user->verifiedCourses()->syncWithoutDetaching([
                        $courseId => [
                            'paid' => $amount / 100,
                            'status' => 'enrolled',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Webhook received.'], 200);
    }
}
