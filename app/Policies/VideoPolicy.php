<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\Response;

class VideoPolicy
{

    public function update(User $user, Video $video): bool
    {
        return $user->id === $video->course->user_id;
    }

}
