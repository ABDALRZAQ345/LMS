<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function achivments(){
        return $this->belongsToMany(Achievement::class)->using(StudentAchivment::class);

    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function review(){
        return $this->hasOne(Review::class);
    }

    public function courses(){
        return $this->belongsToMany(Course::class)->using(StudentCourse::class);
    }

    public function tests(){
        return $this->belongsToMany(Test::class)->using(StudentTest::class);
    }

    public function contests(){
        return $this->belongsToMany(Contest::class)->using(StudentContest::class);
    }

   public function friends(){
        return $this->belongsToMany(Friends::class);
   }



}
