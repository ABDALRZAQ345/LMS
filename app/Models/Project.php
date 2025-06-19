<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
    ];
    protected $hidden=['gitHub_url'];
    protected $casts = [
        'technologies' => 'array',
        'links' => 'array'
    ];

    public function tag(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function likes(): MorphMany
    {
        return $this->morphmany(Like::class, 'likeable');
    }

    public function likers(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\User::class,
            \App\Models\Like::class,
            'likeable_id',
            'id',
            'id',
            'user_id'
        )->where('likeable_type', self::class);
    }
}

