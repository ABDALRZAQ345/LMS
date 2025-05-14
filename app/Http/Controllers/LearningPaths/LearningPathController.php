<?php

namespace App\Http\Controllers\LearningPaths;

use App\Http\Controllers\Controller;
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

    public function getAllLearningPaths(Request $request){
       return $this->learningPathServices->getAllLearningPaths($request);
    }

    public function showLearningPath(LearningPath $learningPath){
      return $this->learningPathServices->showLearningPath($learningPath->id);
    }
}
