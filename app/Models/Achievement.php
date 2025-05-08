<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $table = 'achievements';
    use HasFactory;

    protected $guarded = [];

    public function students()
    {
        return $this->belongsToMany(Student::class,'student_achievement')
            ->using(StudentAchievement::class);

    }
}
