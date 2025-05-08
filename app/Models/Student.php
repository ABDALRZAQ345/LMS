<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function achievements() : belongsToMany
    {
        return $this->belongsToMany(Achievement::class,'student_achievement')
            ->using(StudentAchievement::class);

    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reviews(): HasMany
    {
        return $this->HasMany(Review::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'student_course')
            ->using(StudentCourse::class)
            ->withPivot('paid', 'status');

    }

    public function finishedCourses()
    {
        return $this->belongsToMany(Course::class, 'student_course')
            ->wherePivot('status', 'finished')
            ->using(StudentCourse::class)
            ->withPivot('paid', 'status');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'student_course')
            ->wherePivot('status', 'enrolled')
            ->using(StudentCourse::class)
            ->withPivot('paid', 'status');
    }

    public function watchLaterCourses()
    {
        return $this->belongsToMany(Course::class, 'student_course')
            ->wherePivot('status', 'watch_later')
            ->using(StudentCourse::class)
            ->withPivot('paid', 'status');
    }
    public function tests()
    {
        return $this->belongsToMany(Test::class,'student_test')
            ->using(StudentTest::class)
            ->withPivot('start_time', 'end_time','correct_answers');
    }

    public function contests()
    {
        return $this->belongsToMany(Contest::class, 'student_contest')
            ->using(StudentContest::class)
            ->withPivot('end_time', 'correct_answers', 'gained_points', 'rank');
    }


    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'friends',
            'student_id',
            'friend_id'
        );
    }

}
