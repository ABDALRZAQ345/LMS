<?php

namespace App\Services;

use App\Models\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\DB;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $firebaseConfig = json_decode(base64_decode(config('firebase.credentials')), true);

        $factory = (new Factory)->withServiceAccount($firebaseConfig);
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
//        $limit = 15;
//
//        $keepIds = NotificationModel::where('user_id', $user->id)
//            ->orderByDesc('created_at') // أو orderByDesc('id')
//            ->limit($limit)
//            ->pluck('id')
//            ->toArray();
//
//        if (!empty($keepIds)) {
//            NotificationModel::where('user_id', $user->id)
//                ->whereNotIn('id', $keepIds)
//                ->delete();
//        }
    }

}
