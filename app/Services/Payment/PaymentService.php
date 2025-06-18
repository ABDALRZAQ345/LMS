<?php

namespace App\Services\Payment;

use App\Helpers\ResponseHelper;
use App\Models\Course;
use App\Repositories\Payment\PaymentRepository;

class PaymentService
{
    public $paymentRepository;
    public function __construct(PaymentRepository $paymentRepository){
        $this->paymentRepository = $paymentRepository;
    }

    public function enrollCourse($paymentMethod, Course $course)
    {
        try {
            $intent = $this->paymentRepository->enrollCourse($paymentMethod, $course);

            if ($intent->status === 'requires_action') {
                return ResponseHelper::jsonResponse([
                    'requires_action' => true,
                    'payment_intent_client_secret' => $intent->client_secret,
                ], 'Payment requires additional authentication.');
            }

            return ResponseHelper::jsonResponse([], 'Payment initiated successfully');
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse([], $e->getMessage(), 422, false);
        }
    }




}
