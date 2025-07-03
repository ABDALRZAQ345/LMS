<?php

namespace App\Services\Comments;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Comments\CommentsVideoResource;
use App\Repositories\Comments\CommentRepository;
use http\Env\Request;

class CommentService
{
protected $commentRepository;
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function commentsOfVideo($video){

        $comments = $this->commentRepository->commentsOfVideo($video);
        $data = [
            'comments' => CommentsVideoResource::collection($comments),
            'total_pages' => $comments->lastPage(),
            'current_page' => $comments->currentPage(),
            'hasMorePages' => $comments->hasMorePages(),
        ];
        return ResponseHelper::jsonResponse($data,'Get comments successfully');

    }

    public function addComment($video,$comment){
        $newComment = $this->commentRepository->addComment($video,$comment);

        return ResponseHelper::jsonResponse(CommentsVideoResource::make($newComment),'Add comment successfully');
    }

    public function updateComment($comment,$validated){
        $userId = auth()->id();
        if ($comment->user_id !== $userId){
            return ResponseHelper::jsonResponse([],'You cannot update a comment that does not belong to you.',
                400,false);
        }

        $updated = $this->commentRepository->updateComment($comment,$validated,$userId);
        if (!$updated) {
            return ResponseHelper::jsonResponse([], 'Failed to update comment.', 500, false);
        }
        return ResponseHelper::jsonResponse(CommentsVideoResource::make($updated),'Comment updated successfully');
    }

    public function deleteComment($comment){
        $userId = auth()->id();
        if ($comment->user_id !== $userId ){
            return ResponseHelper::jsonResponse([],'You cannot delete a comment that does not belong to you.',
                400,false);
        }

        $deleted = $this->commentRepository->deleteComment($comment);
        return ResponseHelper::jsonResponse([],'Comment deleted successfully');
    }

    public function like($comment){
        $userId = auth()->id();
        $existingLike = $comment->like()->where('user_id',$userId)->first();
        if($existingLike){
            $existingLike->delete();
            return ResponseHelper::jsonResponse([], 'Like removal successfully');
        }
        $comment->like()->create([
            'user_id' => $userId,
        ]);
        return ResponseHelper::jsonResponse([], 'Liked Comment successfully');
    }

}
