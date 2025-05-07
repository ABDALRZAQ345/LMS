<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }

    public function learningPaths(){
        return $this->hasMany(LearningPath::class);
    }

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function videos(){
        return $this->hasMany(Video::class);
    }

    public function tests(){
        return $this->hasMany(Test::class);
    }
}
