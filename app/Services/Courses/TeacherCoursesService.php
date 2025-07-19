<?php

namespace App\Services\Courses;

use App\Helpers\ResponseHelper;
use App\Http\Resources\Courses\AdminRequestCoursesResource;
use App\Http\Resources\Courses\TeacherCourseContentResource;
use App\Http\Resources\Courses\TeacherCourseDescriptionResource;
use App\Http\Resources\Courses\TeacherCourseResource;
use App\Models\Course;
use App\Repositories\Courses\TeacherCoursesRepository;
use Illuminate\Support\Facades\Storage;

class TeacherCoursesService
{
    protected $teacherCoursesRepository;
    public function __construct(TeacherCoursesRepository $teacherCoursesRepository)
    {
        $this->teacherCoursesRepository = $teacherCoursesRepository;
    }

    public function getTeacherCourses($items){

        $userId = auth()->id();
        $courses = $this->teacherCoursesRepository->getTeacherCourses($userId,$items);
        $data = [
            'courses' => TeacherCourseResource::collection($courses),
            'meta' => getMeta($courses)
        ];
        return ResponseHelper::jsonResponse($data,'Get Teacher Courses Successfully');

    }

    public function getTeacherVerifiedCourses(){
       $teacherId = auth()->id();
       $courses = $this->teacherCoursesRepository->getTeacherVerifiedCourses($teacherId);

       return ResponseHelper::jsonResponse($courses,'Get Teacher Verified Courses Successfully');
    }

    public function showCourseDescription($course){
        $course->load(['students', 'videos', 'learningPaths']);
        $data = TeacherCourseDescriptionResource::make($course);
        return ResponseHelper::jsonResponse($data,'Get Teacher Courses Description Successfully');
    }

    public function showCourseContent($course){
        $course->load(['videos', 'tests']);
        $data = TeacherCourseContentResource::make($course);

        return ResponseHelper::jsonResponse($data,'Get Teacher Courses Content Successfully');
    }
    public function createCourse($validated){
        $course = $this->teacherCoursesRepository->createCourse($validated);
        //todo send notification to admin
        return ResponseHelper::jsonResponse(TeacherCourseResource::make($course), 'Created Course Successfully');
    }

    public function updateCourse($course ,$validated){
        if (isset($validated['image']) && !empty($validated['image'])) {
            $this->deleteOldImage($course->image);

            $validated['image'] = NewPublicPhoto($validated['image'], 'Courses');
        }
        $course->update($validated);
        $course = $course->fresh();
        return ResponseHelper::jsonResponse(TeacherCourseResource::make($course), 'Updated Course Successfully');
    }

    public function deleteOldImage($imagePath)
    {
        $storagePath = ltrim(str_replace('/storage/', '', $imagePath), '/');

        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        } else {
            \Log::warning("Image not found for deletion: " . $storagePath);
        }
    }


}
