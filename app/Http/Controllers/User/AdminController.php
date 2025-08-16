<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddTeacherRequest;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\User;
use App\Services\Payment\PaymentService;
use App\Services\User\StaticsService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    protected UserService $userService;
    protected $paymentService;


    public function __construct(UserService $userService, PaymentService $paymentService)
    {
        $this->userService = $userService;
        $this->paymentService = $paymentService;

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

    public function payment(PaymentRequest $request){
        $validated = $request->validated();
        return $this->paymentService->payment($validated);
    }


}
