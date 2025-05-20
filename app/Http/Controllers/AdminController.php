<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTeacherRequest;
use App\Services\User\UserService;

class AdminController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function addTeacher(AddTeacherRequest $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validated();

        return $this->userService->CreateTeacher($validated);
    }
}
