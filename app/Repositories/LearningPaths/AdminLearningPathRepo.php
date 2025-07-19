<?php

namespace App\Repositories\LearningPaths;

use App\Models\LearningPath;

class AdminLearningPathRepo
{

    public function requestsLearningPaths($validated){
        $query =  LearningPath::orderBy($validated['orderBy'] ,$validated['direction'])
            ->with('teacher')
            ->with('courses.teacher');


        if($validated['status'] !== 'all') {
            $query->where('request_status', $validated['status']);
        }
        if($validated['search']){
                $query->where('title','like','%'.$validated['search'].'%');
        }

        return $query->paginate($validated['items']);
    }
}
