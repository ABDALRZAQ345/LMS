<?php

namespace App\Http\Controllers\Comments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comments\CreateCommentRequest;
use App\Http\Requests\Comments\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Course;
use App\Models\Video;
use App\Services\Comments\CommentService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $commentService;
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function commentsOfVideo(Video $video){
        return $this->commentService->commentsOfVideo($video);
    }

    public function create(Video $video,CreateCommentRequest $request){
        $validated = $request->validated();
        return $this->commentService->addComment($video,$validated);
    }

    public function update(Comment $comment, UpdateCommentRequest $request){
        $validated = $request->validated();
        return $this->commentService->updateComment($comment,$validated);
    }

    public function delete(Comment $comment){
        return $this->commentService->deleteComment($comment);
    }

    public function like(Comment $comment){
        return $this->commentService->like($comment);
    }
}
