<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $event = json_decode($payload, true);

        if ($event['type'] === 'payment_intent.succeeded') {
            $intent = $event['data']['object'];

            $userId = $intent['metadata']['user_id'];
            $courseId = $intent['metadata']['course_id'];
            $amount = $intent['amount']; // in cents

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

        return ResponseHelper::jsonResponse([], 'Webhook received');
    }


}
