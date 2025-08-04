<?php

namespace App\Http\Controllers\LearningPaths;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LearningPath\AdminGetAllLearningPathsRequest;
use App\Models\LearningPath;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use App\Services\LearningPaths\AdminLearningPathService;
use Illuminate\Http\Request;

class AdminLearningPathController extends Controller
{
    protected $adminLearningPathService;
    protected $firebaseNotificationService;

    public function __construct(AdminLearningPathService $adminLearningPathService, FirebaseNotificationService $firebaseNotificationSer)
    {
        $this->adminLearningPathService = $adminLearningPathService;
        $this->firebaseNotificationService = $firebaseNotificationSer;
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
        $teacher = User::findOrFail($learningPath->user_id);

        $title = 'Your learning path has been accepted';
        $body ="Congratulations! Your learning path titled \"{$learningPath->title}\" has been accepted.";

        $this->firebaseNotificationService->sendAndStore($teacher, $title, $body);
        return ResponseHelper::jsonResponse([],'Learning Path accepted successfully');
    }

    public function reject(LearningPath $learningPath , Request $request){
        $request->validate([
            'reason' => 'required|string|max:100'
        ]);
        if($learningPath->request_status !== 'pending'){
            if($learningPath->students()->exists()){
                return ResponseHelper::jsonResponse([],'You cannot reject this Learning Path. It was accepted and has active students',
                    403,false);
            }
        }
        //todo send notification to teacher
        $this->adminLearningPathService->updateLearningPathRequestStatus($learningPath,'rejected');

        $teacher = User::findOrFail($learningPath->user_id);

        $title = 'Your learning path has been rejected';
        $body = "Unfortunately, your learning path titled \"{$learningPath->title}\" has been rejected. Reason: " . $request['reason'];

        $this->firebaseNotificationService->sendAndStore($teacher, $title, $body);

        return ResponseHelper::jsonResponse([],'Learning Path rejected successfully');
    }
}
