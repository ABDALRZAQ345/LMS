<?php

namespace App\Services;

use App\Models\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\NotificationModel;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials'));
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotification(string $fcmToken, string $title, string $body, array $data = []): void
    {
        $notification = Notification::create($title, $body);
        $message = CloudMessage::withTarget('token', $fcmToken)
            ->withNotification($notification)
            ->withData($data);

        $this->messaging->send($message);
    }

    public function sendAndStore(User $user, string $title, string $message, array $data = []): void
    {
        if ($user->fcm_token) {
            $this->sendNotification($user->fcm_token, $title, $message, $data);
        }

        NotificationModel::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
        ]);
    }

}
