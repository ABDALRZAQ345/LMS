<?php

namespace App\Repositories\LearningPaths;

use App\Models\LearningPath;
use App\Models\User;

class LearningPathRepository
{

    public function getAllLearningPaths($validated)
    {
        $user = auth()->user();
        $query = LearningPath::where('verified', true)
            ->with('teacher')
            ->withCount('courses')
            ->withSum('courses', 'price')
            ->withSum('courses', 'rate');
        if ($validated['search']) {
            $query->wher('title', 'like', '%' . $validated['search'] . '%');
        }
        if ($validated['status'] !== 'all') {
            $query->whereHas('students', function ($q) use ($user, $validated) {
                $q->where('user_id', $user->id)
                    ->where('learning_path_user.status', $validated['status']);
            });
        }
            $query->orderBy($validated['orderBy'], $validated['direction']);

            return $query->paginate($validated['items']);

    }
//        if(!$validated['search']){
//            if($validated['status']=='all'){
//                return LearningPath::where('verified', true)
//                    ->with('teacher')
//                    ->withCount('courses')
//                    ->withSum('courses', 'price')
//                    ->withSum('courses','rate')
//                    ->orderBy($validated['orderBy'], $validated['direction'])
//                    ->paginate($validated['items']);
//            }else{
//                $user = \Auth::user();
//                return  LearningPath::where('verified', true)->orderBy($validated['orderBy'],$validated['direction'])
//                    ->where('status',$validated['status'])
//                    ->with('teacher')
//                    ->withCount('courses')
//                    ->withSum('courses', 'price')
//                    ->withSum('courses','rate')
//                    ->paginate($validated['items']);
//            }
//        }
//        else{
//            if($validated['status']=='all'){
//                return LearningPath::where('title','like','%'.$validated['search'].'%')
//                    ->where('verified', true)
//                    ->with('teacher')
//                    ->withCount('courses')
//                    ->withSum('courses', 'price')
//                    ->withSum('courses','rate')
//                    ->orderBy($validated['orderBy'], $validated['direction'])
//                    ->paginate($validated['items']);
//            }else{
//                $user = \Auth::user();
//                return LearningPath::where('verified', true)->where('title','like','%'.$validated['search'].'%')
//                    ->where('status',$validated['status'])
//                    ->orderBy($validated['orderBy'],$validated['direction'])
//                    ->with('teacher')
//                    ->withCount('courses')
//                    ->withSum('courses', 'price')
//                    ->withSum('courses','rate')
//                    ->paginate($validated['items']);
//            }
//
//        }
//    }

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
