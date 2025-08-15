<?php

namespace App\Http\Controllers\Payment;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\ChargeRequest;
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

    public function charge(ChargeRequest $request)
    {
        $validated = $request->validated();
        return $this->paymentService->charge($validated);
    }


}
