<?php

namespace App\Repositories\LearningPaths;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use App\Models\LearningPath;

class TeacherLearningPathRepo
{
    public function getTeacherLearningPaths($items){
        $userId = auth()->id();
        return LearningPath::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($items);
    }

    public function createLearningPath($validated){
        if (isset($validated['image']) && !empty($validated['image'])) {
            $validated['image'] = NewPublicPhoto($validated['image'], 'LearningPaths');
        }

        $validated['user_id'] = auth()->id();

        $learningPath = LearningPath::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $validated['image'] ?? null,
            'user_id' => $validated['user_id'],
            'request_status' => 'pending'
        ]);


        $coursesWithOrder = collect($validated['courses'])
            ->mapWithKeys(function ($courseId, $index) {
                return [$courseId => ['order' => $index + 1]];
            });

        $learningPath->courses()->attach($coursesWithOrder);

        return $learningPath->load('courses.teacher');
    }

    public function updateLearningPath(LearningPath $learningPath, array $validated): LearningPath
    {
        $learningPath->update(collect($validated)->except('courses')->toArray());

        if (isset($validated['courses'])) {
            $courseIds = $validated['courses'];

            $coursesWithOrder = collect($courseIds)->mapWithKeys(function ($id, $index) {
                return [$id => ['order' => $index + 1]];
            });

            $learningPath->courses()->sync($coursesWithOrder);
        }

        return $learningPath->load('courses.teacher');
    }
}
