<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Contest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function questions(): MorphMany
    {
        return $this->morphmany(Question::class, 'questionable');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_contest')
            ->using(StudentContest::class)
            ->withPivot('end_time', 'correct_answers', 'gained_points', 'rank')
            ->withTimestamps();
    }

}
