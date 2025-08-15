<?php

namespace App\Http\Controllers;

use App\Exceptions\ServerErrorException;
use App\Http\Requests\HomePageRequest;
use App\Models\Course;
use App\Models\LearningPath;
use App\Models\Project;

class HomePageController extends Controller
{
    /**
     * @throws ServerErrorException
     */
    public function __invoke(HomePageRequest $request): \Illuminate\Http\JsonResponse
    {

        $topCourses=Course::where('verified',true)->where('rate','>=',2)->limit(10)->get();
        $topLearningPaths=LearningPath::where('verified',true)->limit(10)->get();
        $topProjects=Project::where('status','accepted')->orderBy('likes','desc')->limit(10)->get();
        $recommendedCourses=Course::where('verified',true)->orderBy('rate','desc')->limit(10)->get();
        return response()->json([
            'success' => true,
            'topCourses' => $topCourses,
            'topLearningPaths' => $topLearningPaths,
            'topProjects' => $topProjects,
            'recommendedCourses' => $recommendedCourses,
            'message' => 'Home page requested',
        ]);

    }
}
