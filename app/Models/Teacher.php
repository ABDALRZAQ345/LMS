<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function courses(){
        return $this->hasMany(Course::class);
    }

    public function learningPaths(){
        return $this->hasMany(LearningPath::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }
}
