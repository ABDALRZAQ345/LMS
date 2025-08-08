<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddTeacherRequest;
use App\Models\User;
use App\Services\User\StaticsService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    protected UserService $userService;


    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

    }




    public function addTeacher(AddTeacherRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return $this->userService->CreateTeacher($validated);
    }

    public function blockToggle(User $user): JsonResponse
    {
        $currentUser=auth('api')->user();
        if($user->id == $currentUser->id || $user->role!='student'){
            return  response()->json([
                'status'=>false,
                'message'=>'You cannot block or unblock this user its not student '
            ],400);
        }
        $user->active ^= 1;
        $user->save();
        return  response()->json([
            'status'=>true,
            'message'=>'User has been '. ($user->active? 'unblocked':'blocked'),
        ]);

    }


}
