<?php

namespace App\Observers;

use App\Models\Like;
use App\Models\Comment;


class LikeObserver
{
    /**
     * Handle the Like "created" event.
     */
    public function created(Like $like)
    {
        if ($like->likeable_type === Comment::class) {
            $comment = $like->likeable;
            $comment->increment('likes');
        }
    }

    /**
     * Handle the Like "updated" event.
     */
    public function updated(Like $like): void
    {
        //
    }

    /**
     * Handle the Like "deleted" event.
     */
    public function deleted(Like $like)
    {
        if ($like->likeable_type === Comment::class) {
            $comment = $like->likeable;
            $comment->decrement('likes');
        }
    }

    /**
     * Handle the Like "restored" event.
     */
    public function restored(Like $like): void
    {
        //
    }

    /**
     * Handle the Like "force deleted" event.
     */
    public function forceDeleted(Like $like): void
    {
        //
    }
}
