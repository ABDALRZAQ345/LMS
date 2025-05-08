<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StudentCourse extends Pivot
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'student_course';
}
