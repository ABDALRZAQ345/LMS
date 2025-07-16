<?php

namespace App\Repositories\Courses;

use App\Models\Course;

class AdminCourseRepository
{
    public function requestsCourses($items){
        return Course::where('request_status','pending')
            ->with('teacher')
            ->paginate($items);
    }
}
