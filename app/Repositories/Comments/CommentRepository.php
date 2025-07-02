<?php

namespace App\Repositories\Comments;

use App\Models\Comment;

class CommentRepository
{
    public function commentsOfVideo($video)
    {
        return Comment::where('video_id', $video->id)
            ->whereNull('comment_id')
            ->with([
                'user',
                'like',
                'replies' => function ($query) {
                    $query->with(['user', 'like'])
                    ->orderBy('created_at', 'asc');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function addComment($video, $comment){
        $userId = auth()->id();
        return Comment::create([
           'text' => $comment['text'],
            'video_id' => $video->id,
            'user_id' => $userId,
            'likes'=>0,
            'comment_id'=>$comment['comment_id'] ?? null,
        ]);
    }

    public function updateComment($comment,$validated,$userId){
        $affected = Comment::where('id', $comment->id)
            ->where('user_id', $userId)
            ->update($validated);

        if ($affected) {
            return Comment::find($comment->id);
        }
        return null;
    }

    public function deleteComment( $comment )
    {
        $this->deleteWithReplies($comment);
        return true;
    }

    private function deleteWithReplies($comment)
    {
        foreach ($comment->replies as $reply) {
            $this->deleteWithReplies($reply);
        }
        $comment->delete();
    }
}
