<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function verifiedCourses(): HasMany
    {
        return $this->hasMany(Course::class)->where('verified', true);
    }

    public function unVerifiedCourses(): HasMany
    {
        return $this->hasMany(Course::class)->where('verified', false);
    }

    public function paidCourses(): HasMany
    {
        return $this->hasMany(Course::class)->where('paid', true);
    }

    public function unPaidCourses(): HasMany
    {
        return $this->hasMany(Course::class)->where('paid', false);
    }


    public function learningPaths(): HasMany
    {
        return $this->hasMany(LearningPath::class);
    }

    public function verifiedLearningPaths(): HasMany
    {
        return $this->hasMany(LearningPath::class)->where('verified', true);
    }
    public function unVerifiedLearningPaths(): HasMany{
        return $this->hasMany(LearningPath::class)->where('verified', false);
    }


}
