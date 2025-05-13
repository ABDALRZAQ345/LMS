<?php

namespace App\Repositories\Courses;

use App\Http\Resources\Courses\CourseWithContentResource;
use App\Models\Course;
use App\Models\LearningPath;

class CoursesRepository
{

    public function getAllCourses($validated)
    {
        if($validated['status']=='all')
            return Course::orderBy($validated['orderBy'],$validated['direction'])
                ->with('teacher')
                ->where('verified',true)->paginate($validated['items']);
        else{
            $user=\Auth::user();
           return $user->allCourses()->orderBy($validated['orderBy'],$validated['direction'])
                ->where('status',$validated['status'])
               ->with('teacher')
                ->paginate($validated['items']);
        }

    }

    public function showCourse($id){
        return Course::with('teacher','learningPaths')->where('id',$id)->first();
    }

    public function getAllCoursesInLearningPath($id) {
        $learningPath = LearningPath::findOrFail($id);
        return $learningPath->courses()->with('teacher')->get();
    }


    public function showCourseInLearningPath($courseId){
        $course = Course::with('teacher')->findOrFail($courseId);
        $content = $course->content();

        return new CourseWithContentResource($course, $content);
    }
}
