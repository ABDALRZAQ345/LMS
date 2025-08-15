<?php

namespace App\Services\LearningPaths;

use App\Helpers\ResponseHelper;
use App\Http\Resources\LearningPaths\LearningPathResource;
use App\Models\Achievement;
use App\Models\User;
use App\Repositories\LearningPaths\LearningPathRepository;

class LearningPathService
{
    public $learningPathRepository;

    public function __construct(LearningPathRepository $learningPathRepository)
    {
        $this->learningPathRepository = $learningPathRepository;
    }

    public function getAllLearningPaths($validated)
    {

        $learningPaths = $this->learningPathRepository->getAllLearningPaths($validated);

        $data = [
            'learningPaths' => LearningPathResource::collection($learningPaths),
            'total_pages' => $learningPaths->lastPage(),
            'current_page' => $learningPaths->currentPage(),
            'hasMorePages' => $learningPaths->hasMorePages(),
        ];

        return ResponseHelper::jsonResponse($data, 'get all learning paths successfully');
    }

    public function showLearningPath($id)
    {
        $learningPath = $this->learningPathRepository->showLearningPath($id);
        return ResponseHelper::jsonResponse(LearningPathResource::make($learningPath), 'get learning path successfully');

    }

    public function updateStatusLearningPath($validated, $id)
    {
        $learningPath = $this->learningPathRepository->updateStatusLearningPath($validated, $id);

        $status = $learningPath->students()
            ->where('user_id', auth()->id())
            ->first()
            ->pivot
            ->status;
        if($status=='enroll'){
            $this->handleEnrolledInLearningPath(auth()->user());
        }

        return ResponseHelper::jsonResponse(LearningPathResource::make($learningPath), 'Learning path Added to '.$status.' successfully');
    }

    public function removeStatusLearningPath($id)
    {
        $deleated = $this->learningPathRepository->removeStatusLearningPath($id);
        if (! $deleated) {
            return ResponseHelper::jsonResponse([], 'leaning path is not found ', 404, false);
        } else {
            return ResponseHelper::jsonResponse([], 'Learning path has been removed');
        }

    }

    private function handleEnrolledInLearningPath(User $user)
    {
        $achievement=Achievement::where('name','Track Starter')->firstOrFail();
        $user->achievements()->syncWithoutDetaching($achievement->id);
        $pathsCount=\DB::table('learning_path_user')
            ->where('user_id',$user->id)
            ->where('status','=','enroll')->count();
        if($pathsCount>=3){
            $achievement=Achievement::where('name','Explorer')->firstOrFail();
            $user->achievements()->syncWithoutDetaching($achievement->id);
        }
    }

}
