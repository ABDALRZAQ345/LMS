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
    protected $guarded=[
        'id'
    ];
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function learningPaths(): BelongsToMany
    {
        return $this->BelongsToMany(LearningPath::class, 'course_learning_path');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withPivot('paid', 'status')
            ->withTimestamps();
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('order');
    }
    public function freeVideos(): HasMany
    {
        if($this->price ==0 || $this->price==null){
            return $this->hasMany(Video::class)->orderBy('order');
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
