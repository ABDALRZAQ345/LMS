<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Contest extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];
    protected $hidden=[
        'created_at',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function questions(): MorphMany
    {
        return $this->morphmany(Question::class, 'questionable');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'contest_user')
            ->withPivot('end_time', 'correct_answers', 'gained_points', 'rank', 'is_official')
            ->wherePivot('is_official', true)
            ->orderByPivot('correct_answers', 'desc')
            ->orderByPivot('end_time', 'asc')
            ->withTimestamps();
    }



    public function problems(): HasMany
    {
        return $this->hasMany(Problem::class);
    }

    public function submissions(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Submission::class,
            Problem::class,
            'contest_id',
            'problem_id',
            'id',
            'id'
        )->select('submissions.*');
    }
}
