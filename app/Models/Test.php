<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function course(){
        return $this->belongsTo(Course::class);
    }
    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function students(){
        return $this->belongsToMany(Student::class);
    }
}
