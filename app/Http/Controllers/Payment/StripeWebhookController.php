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

            $userId = data_get($intent, 'metadata.user_id');
            $amount = (int)data_get($intent, 'amount', 0) / 100;

            if ($userId) {
                $user = User::find($userId);

                if ($user) {
                    $user->increment('balance', $amount);
                    $title = 'Charge Balance';
                    $body = "You charge balance {$amount} USD.";
                    SendFirebaseNotification::dispatch($user, $title, $body);
                } else {

                }
            } else {

            }
        }
        return response()->json(['message' => 'Webhook received.'], 200);
    }

}
