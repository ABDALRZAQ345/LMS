<?php

namespace App\Repositories\LearningPaths;

use App\Models\LearningPath;
use App\Models\User;

class LearningPathRepository
{
    public function getAllLearningPaths($validated)
    {

        if ($validated['status'] == 'all') {
            return LearningPath::where('verified', true)
                ->with('teacher')
                ->withCount('courses')
                ->withSum('courses', 'price')
                ->withSum('courses', 'rate')
                ->orderBy($validated['orderBy'], $validated['direction'])
                ->paginate($validated['items']);
        } else {
            $user = \Auth::user();

            return $user->allLearningPaths()->orderBy($validated['orderBy'], $validated['direction'])
                ->where('status', $validated['status'])
                ->with('teacher')
                ->withCount('courses')
                ->withSum('courses', 'price')
                ->withSum('courses', 'rate')
                ->paginate($validated['items']);
        }
    }

    public function showLearningPath($id)
    {
        return LearningPath::find($id)
            ->with('teacher')
            ->withCount('courses')
            ->withSum('courses', 'price')
            ->withSum('courses', 'rate');
    }

    public function updateStatusLearningPath($validated, $learningPathId)
    {
        $user = auth()->user();

        $exists = $user->learningPaths()->where('learning_path_id', $learningPathId)->exists();

        if ($exists) {
            $user->learningPaths()->updateExistingPivot($learningPathId, [
                'status' => $validated['status'],
            ]);
        } else {
            $user->learningPaths()->attach($learningPathId, [
                'status' => $validated['status'],
                'paid' => false,
            ]);
        }

        return LearningPath::with('teacher')
            ->withCount('courses')
            ->withSum('courses', 'price')
            ->withSum('courses', 'rate')
            ->find($learningPathId);
    }

    public function removeStatusLearningPath($id)
    {
        $user = auth()->user();
        $exists = $user->learningPaths()->where('learning_path_id', $id)->exists();

        if ($exists) {
            $user->learningPaths()->detach($id);

            return true;
        } else {
            return false;
        }

    }

    public function TeacherLearningPaths(User $user): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $user->verifiedLearningPaths()
            ->with('teacher')
            ->withCount('courses')
            ->withSum('courses', 'price')
            ->withSum('courses', 'rate')
            ->paginate(20);
    }
}
