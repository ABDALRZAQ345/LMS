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
            'liked_by_me' => $this->relationLoaded('like') && $this->like instanceof \Illuminate\Support\Collection
                ? $this->like->contains('user_id', auth()->id())
                : false,
            'user_id' => optional($this->user)->id,
            'user_name' => optional($this->user)->name,
            'user_image' => getPhoto(optional($this->user)->image),
            'user_role' => optional($this->user)->role,
            'is_mine' => optional($this->user)->id == auth()->id() ? true : false ,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'replies_count' => $this->replies->count(),
            'replies' => CommentsVideoResource::collection($this->whenLoaded('replies')),
        ];
    }
}
