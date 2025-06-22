<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function directComments(): HasMany
    {
        return $this->HasMany(Comment::class)->whereNull('comment_id');
    }

    public function students(){
        return $this->belongsToMany(User::class,'user_video_progress')
            ->withPivot('progress', 'is_completed','last_watched_at');
    }
}
