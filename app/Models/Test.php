<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Test extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function questions(): MorphMany
    {
        return $this->morphMany(Question::class, 'questionable');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'test_user')
            ->withPivot('start_time', 'end_time', 'correct_answers');
    }

    public function studentProgress()
    {
        return $this->hasMany(UserTestProgress::class);
    }
}
