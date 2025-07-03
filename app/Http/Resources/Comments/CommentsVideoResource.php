<?php

namespace App\Http\Resources\Comments;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
class CommentsVideoResource extends JsonResource
{

    protected static ?int $cachedUserId = null;

    public function toArray(Request $request): array
    {
        if (is_null(self::$cachedUserId)) {
            self::$cachedUserId = auth()->id();
        }
        return [
            'id' => $this->id,
            'text' => $this->text,
            'likes' => $this->likes,
            'liked_by_user' => $this->relationLoaded('like') && $this->like instanceof \Illuminate\Support\Collection
                ? $this->like->contains('user_id', auth()->id())
                : false,
            'student_id' => optional($this->user)->id,
            'student_name' => optional($this->user)->name,
            'student_image' => optional($this->user)->image,
            'created_at_human' => Carbon::parse($this->created_at)->diffForHumans(),
            'replies_count' => $this->replies->count(),
            'replies' => CommentsVideoResource::collection($this->whenLoaded('replies')),
        ];
    }
}
