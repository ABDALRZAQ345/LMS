<?php

namespace App\Services\LearningPaths;

use App\Helpers\ResponseHelper;
use App\Http\Resources\LearningPaths\TeacherLearningPathResource;
use App\Jobs\SendFirebaseNotification;
use App\Models\User;
use App\Repositories\LearningPaths\TeacherLearningPathRepo;
use Illuminate\Support\Facades\Storage;
class TeacherLearningPathService
{
    protected $teacherLearningPathRepo;
    public function __construct(TeacherLearningPathRepo $teacherLearningPathRepo)
    {
        $this->teacherLearningPathRepo = $teacherLearningPathRepo;
    }

    public function getTeacherLearningPaths($items){
        $learningPaths = $this->teacherLearningPathRepo->getTeacherLearningPaths($items);
        $data = [
            'learningPaths' => TeacherLearningPathResource::collection($learningPaths),
            'meta' => getMeta($learningPaths),
        ];
        return ResponseHelper::jsonResponse($data,'Get Teacher Learning Paths Successfully');
    }

    public function show($learningPath){
        $learningPath->load('courses.teacher')
        ->loadCount('courses')
        ->loadSum('courses','rate')
        ->loadSum('courses','price');
        $data = TeacherLearningPathResource::make($learningPath);
        return ResponseHelper::jsonResponse($data,'Show Learning Path Details Successfully');
    }

    public function createLearningPath($validated){
        $learningPath = $this->teacherLearningPathRepo->createLearningPath($validated);

        $admin = User::where('role','admin')->first();
        $title = 'New Learning Path';
        $body ="A new learning path has been requested.";

        SendFirebaseNotification::dispatch($admin, $title, $body);
        return ResponseHelper::jsonResponse(TeacherLearningPathResource::make($learningPath)
            ,'Created Learning Path Successfully');
    }

    public function updateLearningPath($learningPath, $validated){
        if (isset($validated['image']) && !empty($validated['image'])) {
            $this->deleteOldImage($learningPath->image);

            $validated['image'] = NewPublicPhoto($validated['image'], 'LearningPaths');
        }
        $updateLearningPath = $this->teacherLearningPathRepo->updateLearningPath($learningPath, $validated);

        return ResponseHelper::jsonResponse(TeacherLearningPathResource::make($updateLearningPath),
        'Updated Learning Path Successfully');

    }

    public function deleteOldImage($imagePath)
    {
        $storagePath = ltrim(str_replace('/storage/', '', $imagePath), '/');

        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        } else {
            \Log::warning("Image not found for deletion: " . $storagePath);
        }
    }

}
