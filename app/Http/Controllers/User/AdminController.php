<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddTeacherRequest;
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
}
