<?php

namespace App\Repositories\LearningPaths;

use App\Models\LearningPath;
use App\Models\User;

class LearningPathRepository
{
    public function getAllLearningPaths($item, $direction)
    {
        return LearningPath::where('verified', true)
            ->with('teacher')
            ->withCount('courses')
            ->withSum('courses', 'price')
            ->withSum('courses', 'rate')
            ->orderBy('title', $direction)
            ->paginate($item);
    }

    public function showLearningPath($id)
    {
        return \DB::table('learning_paths')->find($id);
    }

    public function TeacherLearningPaths(User $user): \Illuminate\Database\Eloquent\Collection
    {
        return $user->verifiedLearningPaths()
            ->with('teacher')
            ->withCount('courses')
            ->withSum('courses', 'price')
            ->withSum('courses', 'rate')
            ->get();
    }
}
