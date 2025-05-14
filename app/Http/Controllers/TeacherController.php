<?php

namespace App\Http\Controllers;

use App\Models\User;

class TeacherController extends Controller
{
    public function courses(User $user)
    {
        $createdCourses=$user->verifiedCourses()->paginate(20);
        return response()->json([
            'status' => true,
            'courses' => $createdCourses,
        ]);
    }

    public function learningPaths(User $user)
    {
        $createdLearningPaths=$user->verifiedLearningPaths()->paginate(20);
        return response()->json([
            'status' => true,
            'learningPaths' => $createdLearningPaths,
        ]);
    }
}
