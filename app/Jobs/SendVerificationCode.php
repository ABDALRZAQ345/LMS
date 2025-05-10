<?php

namespace App\Jobs;

use App\Exceptions\VerificationCodeException;
use App\Services\VerificationCodeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendVerificationCode implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    protected $email;

    protected $registration;

    protected $verification_code_service;

    public function __construct($email, $registration)
    {
        $this->email = $email;
        $this->verification_code_service = new VerificationCodeService;
        $this->registration = $registration;
    }

    /**
     * Execute the job.
     *
     * @throws VerificationCodeException
     */
    public function handle(): void
    {
        $this->verification_code_service->delete($this->email, $this->registration);
        $this->verification_code_service->Send($this->email, $this->registration);
    }
}
