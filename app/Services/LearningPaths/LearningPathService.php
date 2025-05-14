<?php

namespace App\Services\LearningPaths;

use App\Helpers\ResponseHelper;
use App\Http\Resources\LearningPaths\LearningPathResource;
use App\Repositories\LearningPaths\LearningPathRepository;
use Illuminate\Http\Request;

class LearningPathService
{
    public $learningPathRepository;

    public function __construct(LearningPathRepository $learningPathRepository)
    {
        $this->learningPathRepository = $learningPathRepository;
    }

    public function getAllLearningPaths(Request $request)
    {
        $items = $request->query('items', 20);
        $direction = $request->query('direction', 'asc');

        $learningPaths = $this->learningPathRepository->getAllLearningPaths($items, $direction);

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
}
