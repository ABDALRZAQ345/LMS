<?php

namespace App\Services;

use App\Exceptions\VerificationCodeException;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\LearningPaths\LearningPathResource;
use App\Mail\SendEmail;
use App\Models\User;
use App\Models\VerificationCode;
use App\Repositories\Courses\CoursesRepository;
use App\Repositories\LearningPaths\LearningPathRepository;
use App\Responses\LogedInResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
}
