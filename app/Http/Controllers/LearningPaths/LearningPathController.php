<?php

namespace App\Http\Controllers\LearningPaths;

use App\Http\Controllers\Controller;
use App\Http\Requests\LearningPath\getAllLearningPathRequest;
use App\Http\Requests\LearningPath\updateStatusLearningPathRequest;
use App\Models\LearningPath;
use App\Services\LearningPaths\LearningPathService;
use Illuminate\Http\Request;

class LearningPathController extends Controller
{
    public $learningPathServices;
    public function __construct(LearningPathService $learningPathServices)
    {
        return  $this->learningPathServices = $learningPathServices;
    }

    public function getAllLearningPaths(getAllLearningPathRequest $request){
        $validated = $request->validated();
        return $this->learningPathServices->getAllLearningPaths($validated);
    }

    public function showLearningPath(LearningPath $learningPath){
        return $this->learningPathServices->showLearningPath($learningPath->id);
    }

    public function updateStatusLearningPath(LearningPath $learningPath, updateStatusLearningPathRequest $request){
        $validated =  $request->validated();

        return $this->learningPathServices->updateStatusLearningPath($validated,$learningPath->id);
    }

    public function removeStatusLearningPath(LearningPath $learningPath)
    {
        return $this->learningPathServices->removeStatusLearningPath($learningPath->id);
    }
}
