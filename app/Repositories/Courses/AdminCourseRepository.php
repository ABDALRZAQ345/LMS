<?php

namespace App\Repositories\Courses;

use App\Models\Course;

class AdminCourseRepository
{
    public function requestsCourses($validated){
        $query =  Course::orderBy($validated['orderBy'], $validated['direction'])
            ->with('teacher');

            if($validated['search']){
                $query->where('title', 'like', '%'.$validated['search'].'%');
            }
            if($validated['status'] !== 'all'){
                $query->where('request_status', $validated['status']);
            }

        return $query->paginate($validated['items']);
    }
}
