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

    public function charge($paymentMethod)
    {
        try {
            $intent = $this->paymentRepository->charge($paymentMethod);

            return ResponseHelper::jsonResponse([
                'client_secret' => $intent->client_secret,
                'requires_action' => $intent->status === 'requires_action',
            ], 'Payment intent created successfully.');

        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse([], $e->getMessage(), 422, false);
        }
    }





}
