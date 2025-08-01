<?php

namespace App\Repositories\LearningPaths;

use App\Models\LearningPath;
use App\Models\User;

class LearningPathRepository
{

    public function getAllLearningPaths($validated)
    {
        $userId = auth('api')->id();
        $query = LearningPath::where('verified', true)
            ->with('teacher')
            ->withCount('courses')
            ->with(['students' => fn($q) => $q->where('user_id', $userId)])
            ->withSum('courses', 'price')
            ->withSum('courses', 'rate');
        if ($validated['search']) {
            $query->where('title', 'like', '%' . $validated['search'] . '%');
        }
        if ($validated['status'] !== 'all') {
            $query->whereHas('students', function ($q) use ($userId, $validated) {
                $q->where('user_id', $userId)
                    ->where('learning_path_user.status', $validated['status']);
            });
        }
            $query->orderBy($validated['orderBy'], $validated['direction']);

            return $query->paginate($validated['items']);

    }

    public function showLearningPath($id)
    {
        $userId = auth('api')->id();
        $learningPath = LearningPath::with([
            'teacher.verifiedCourses',
            'courses.videos',
            'students' => fn($q) => $q->where('user_id', $userId),
        ])
            ->withCount('courses')
            ->withSum('courses', 'price')
            ->withSum('courses', 'rate')
            ->findOrFail($id);

        return $learningPath;
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
