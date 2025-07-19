<?php

namespace App\Services\LearningPaths;

use App\Helpers\ResponseHelper;
use App\Http\Resources\LearningPaths\TeacherLearningPathResource;
use App\Repositories\LearningPaths\AdminLearningPathRepo;

class AdminLearningPathService
{
    protected $adminLearningPathRepo;

    public function __construct(AdminLearningPathRepo $adminLearningPathRepo)
    {
        $this->adminLearningPathRepo = $adminLearningPathRepo;
    }

    public function requestsLearningPaths($validated){
        $learningPaths = $this->adminLearningPathRepo->requestsLearningPaths($validated);
        $data = [
            'requests_learning_paths' =>TeacherLearningPathResource::collection( $learningPaths),
            'meta' =>getMeta($learningPaths),
        ];

        return ResponseHelper::jsonResponse($data, 'Get All Learning Paths Successfully');
    }

    public function updateLearningPathRequestStatus($learningPath, String $status){
        if ($status == 'accepted') {
            return $learningPath->update([
                'request_status' => $status,
                'verified' => 1,
            ]);
        }else{
            return $learningPath->update([
                'request_status' => $status,
                'verified' => 0,
            ]);
        }
    }
}
