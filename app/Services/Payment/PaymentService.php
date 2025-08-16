<?php

namespace App\Services\Payment;

use App\Helpers\ResponseHelper;
use App\Jobs\SendFirebaseNotification;
use App\Models\User;
use App\Repositories\Payment\PaymentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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

    public function payment(array $validated)
    {
        $teacher = User::findOrFail($validated['teacher_id']);
        $amount  = (float) $validated['amount'];

        DB::transaction(function () use ($teacher, $amount) {
            if ($amount >= 0) {
                $teacher->increment('balance', $amount);
            } else {
                $abs = abs($amount);
                if ($teacher->balance < $abs) {
                    throw ValidationException::withMessages([
                        'amount' => 'Insufficient balance to perform this debit.',
                    ]);
                }
                $teacher->decrement('balance', $abs);
            }
        });

        $title = 'New Payment';
        $body  = $amount >= 0
            ? 'Admin added ' . $amount . ' to your account'
            : 'Admin deducted ' . abs($amount) . ' from your account';

        SendFirebaseNotification::dispatch($teacher, $title, $body)->afterCommit();

        return ResponseHelper::jsonResponse([], 'Payment Done Successfully.');
    }



}
