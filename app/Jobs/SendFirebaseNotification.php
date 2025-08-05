<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendFirebaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public string $title,
        public string $body
    ) {}

    public function handle()
    {
        app(FirebaseNotificationService::class)->sendAndStore($this->user, $this->title, $this->body);
    }

}
