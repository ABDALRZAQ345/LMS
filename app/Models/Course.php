<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function learningPaths(): BelongsToMany
    {
        return $this->BelongsToMany(LearningPath::class, 'learning_path_course')
            ->using(LearningPathCourse::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_course')
            ->withPivot('paid', 'status')
            ->withTimestamps()
            ->using(StudentCourse::class);
    }


    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('order');
    }
    public function freeVideos()
    {
        if($this->price ==0 || $this->price==null){
            return $this->videos()->orderBy('order');
        }
        else {
            return $this->hasMany(Video::class)->where('free', true)->orderBy('order');
        }
    }
    public function unFreeVideos(): HasMany
    {
        return  $this->hasMany(Video::class)->where('free', false)->orderBy('order');
    }
    public function tests(): HasMany
    {
        return $this->hasMany(Test::class)->orderBy('order');
    }


    public function FinalTest()
    {
        return $this->tests()->where('is_final', true)->first();
    }

    public function content()
    {

        $videos = $this->videos()->orderBy('order')->get();


        $tests = $this->tests()->orderBy('order')->get();

        $all = $videos->merge($tests)->sortBy('order');

        return $all;
    }
}
