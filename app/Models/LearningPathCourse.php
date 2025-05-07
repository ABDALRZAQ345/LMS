<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LearningPathCourse extends Pivot
{
    use HasFactory;
    protected $guarded = [];

    public function learningPath(){
        return $this->belongsTo(LearningPath::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
