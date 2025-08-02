<?php

namespace App\Repositories\Courses;

use App\Models\Course;

class TeacherCoursesRepository
{

    public function getTeacherCourses($userId,$items){
        return Course::where('user_id', $userId)
            ->paginate($items);
    }

    public function getAllVerifiedCourses($validated){

        $query = Course::select('id', 'title', 'description', 'image', 'user_id')
            ->where('verified', 1)
            ->with('teacher:id,name,image');

       return $query->paginate($validated['items']);
    }

    public function getTeacherVerifiedCourses($teacherId){
        return Course::select('id','title')
            ->where('user_id',$teacherId)
            ->where('verified',1)
            ->get();
    }

    public function createCourse($validated){
        if(isset($validated['image']) && !empty($validated['image'])){
            $validated['image'] = NewPublicPhoto($validated['image'] , 'Courses');
        }
        $validated['user_id'] = auth()->id();

        return Course::create($validated);
    }


}
