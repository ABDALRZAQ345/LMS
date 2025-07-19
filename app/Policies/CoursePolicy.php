<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    public function editCourse(User $user, Course $course): bool
    {
        return $course->user_id==$user->id;
    }

    public function view(user $user, Course $course): bool
    {
        return $course->user_id == $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'teacher' ;
    }
}
