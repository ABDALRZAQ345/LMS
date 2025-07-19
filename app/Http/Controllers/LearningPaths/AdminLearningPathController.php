<?php

namespace App\Http\Controllers\LearningPaths;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LearningPath\AdminGetAllLearningPathsRequest;
use App\Models\LearningPath;
use App\Services\LearningPaths\AdminLearningPathService;
use Illuminate\Http\Request;

class AdminLearningPathController extends Controller
{
    protected $adminLearningPathService;

    public function __construct(AdminLearningPathService $adminLearningPathService)
    {
        $this->adminLearningPathService = $adminLearningPathService;
    }

    public function index(AdminGetAllLearningPathsRequest $request){
        $validated = $request->validated();
        if($validated['orderBy'] == 'date'){
            $validated['orderBy'] = 'created_at';
        }
        return $this->adminLearningPathService->requestsLearningPaths($validated);
    }

    public function accept(LearningPath $learningPath){
        $this->adminLearningPathService->updateLearningPathRequestStatus($learningPath,'accepted');
        // todo send notification to teacher
        return ResponseHelper::jsonResponse([],'Learning Path accepted successfully');
    }

    public function reject(LearningPath $learningPath){
        if($learningPath->request_status !== 'pending'){
            if($learningPath->students()->exists()){
                return ResponseHelper::jsonResponse([],'You cannot reject this Learning Path. It was accepted and has active students',
                    403,false);
            }
        }
        $this->adminLearningPathService->updateLearningPathRequestStatus($learningPath,'rejected');
        //todo send notification to teacher
        return ResponseHelper::jsonResponse([],'Learning Path rejected successfully');
    }
}
