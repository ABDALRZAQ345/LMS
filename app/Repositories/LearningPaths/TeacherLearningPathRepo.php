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
            ->with('courses.teacher')
            ->withCount('courses')
            ->withSum('courses', 'price')
            ->withSum('courses', 'rate')
            ->paginate($items);
    }

    public function createLearningPath($validated){
        if (isset($validated['image']) && !empty($validated['image'])) {
            $validated['image'] = NewPublicPhoto($validated['image'], 'LearningPaths');
        }
        $user = auth()->user();
        $validated['user_id'] = $user->id;

        $learningPath = LearningPath::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $validated['image'] ?? null,
            'user_id' => $validated['user_id'],
            'request_status' => $user->role == 'admin' ? 'accepted' : 'pending',
            'verified' => $user->role == 'admin' ? 1 :0
        ]);


        $coursesWithOrder = collect($validated['courses'])
            ->mapWithKeys(function ($courseId, $index) {
                return [$courseId => ['order' => $index + 1]];
            });

        $learningPath->courses()->attach($coursesWithOrder);

        return $learningPath->load('courses.teacher')
            ->loadCount('courses')
            ->loadSum('courses','rate')
            ->loadSum('courses','price');
    }

    public function updateLearningPath($learningPath, array $validated): LearningPath
    {
        if ($learningPath->request_status === 'rejected') {
            $learningPath->request_status = 'pending';
        }

        $learningPath->update(collect($validated)->except('courses')->toArray());

        if (isset($validated['courses'])) {
            $courseIds = $validated['courses'];

            $coursesWithOrder = collect($courseIds)->mapWithKeys(function ($id, $index) {
                return [$id => ['order' => $index + 1]];
            });

            $learningPath->courses()->sync($coursesWithOrder);
        }

        return $learningPath->load('courses.teacher')
            ->loadCount('courses')
            ->loadSum('courses','rate')
            ->loadSum('courses','price');
    }
}
