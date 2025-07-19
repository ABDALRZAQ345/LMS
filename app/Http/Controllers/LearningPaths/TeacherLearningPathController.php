<?php

namespace App\Http\Controllers\LearningPaths;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LearningPath\CreateLearningPathRequest;
use App\Http\Requests\LearningPath\UpdateLearningPathRequest;
use App\Models\LearningPath;
use App\Services\LearningPaths\TeacherLearningPathService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TeacherLearningPathController extends Controller
{
    use AuthorizesRequests;
    protected $teacherLearningPathService;
    public function __construct(TeacherLearningPathService $teacherLearningPathService)
    {
        $this->teacherLearningPathService = $teacherLearningPathService;
    }

    public function myLearningPaths(Request $request){
        $items = $request->input('items',10);

        return $this->teacherLearningPathService->getTeacherLearningPaths($items);
    }

    public function show(LearningPath $learningPath){
        $this->authorize('view', $learningPath);
        return $this->teacherLearningPathService->show($learningPath);
    }

    public function create(CreateLearningPathRequest $request){
        $validated = $request->validated();
        return $this->teacherLearningPathService->createLearningPath($validated);
    }

    public function update(LearningPath $learningPath, UpdateLearningPathRequest $request){
        $validated = $request->validated();
        return $this->teacherLearningPathService->updateLearningPath($learningPath, $validated);
    }

    public function delete(LearningPath $learningPath){
        $this->authorize('delete', $learningPath);
        if($learningPath->image){
            $this->teacherLearningPathService->deleteOldImage($learningPath->image);
        }
        $learningPath->delete();

        return ResponseHelper::jsonResponse([],'Deleted Learning path successfully ');
    }
}
