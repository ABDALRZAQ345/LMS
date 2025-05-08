<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LearningPathCourse extends Pivot
{
    use HasFactory;

    protected $guarded = [];

    public function learningPath(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LearningPath::class);
    }

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
