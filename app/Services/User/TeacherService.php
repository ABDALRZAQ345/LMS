<?php

namespace App\Services\User;

use App\Http\Resources\ContestResource;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\LearningPaths\LearningPathResource;
use App\Models\User;
use App\Repositories\Courses\CoursesRepository;
use App\Repositories\LearningPaths\LearningPathRepository;

class TeacherService
{
    protected CoursesRepository $coursesRepository;

    protected LearningPathRepository $learningPathRepository;

    public function __construct(CoursesRepository $coursesRepository, LearningPathRepository $learningPathRepository)
    {
        $this->coursesRepository = $coursesRepository;
        $this->learningPathRepository = $learningPathRepository;
    }

    public function getTeacherCourses(User $user): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return CourseResource::collection($this->coursesRepository->getAllCoursesForUser($user));
    }

    public function getTeacherLearningPaths(User $user): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return LearningPathResource::collection($this->learningPathRepository->TeacherLearningPaths($user));
    }

    public function getCreatedContests(User $user): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
       return ContestResource::collection($user->AcceptedCreatedContests()->paginate(20));
    }
}
