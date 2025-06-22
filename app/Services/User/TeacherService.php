<?php

namespace App\Services\User;

use App\Exceptions\BadRequestException;
use App\Exceptions\ServerErrorException;
use App\Http\Resources\ContestResource;
use App\Http\Resources\Courses\CourseResource;
use App\Http\Resources\LearningPaths\LearningPathResource;
use App\Models\Course;
use App\Models\Test;
use App\Models\User;
use App\Repositories\Courses\CoursesRepository;
use App\Repositories\LearningPaths\LearningPathRepository;
use App\Services\QuestionService;
use Doctrine\DBAL\Exception;
use Illuminate\Support\Facades\DB;

class TeacherService
{
    protected CoursesRepository $coursesRepository;

    protected LearningPathRepository $learningPathRepository;
    protected QuestionService  $questionService;
    public function __construct(CoursesRepository $coursesRepository, LearningPathRepository $learningPathRepository, QuestionService $questionService)
    {
        $this->coursesRepository = $coursesRepository;
        $this->learningPathRepository = $learningPathRepository;
        $this->questionService = $questionService;
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
