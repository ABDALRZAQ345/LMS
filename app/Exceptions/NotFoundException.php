<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotFoundException extends Exception
{
    public function __construct(string $message = 'Object not found')
    {
        parent::__construct($message);
    }

    public function render(Request $request): JsonResponse
    {
        //
        return response()->json([
            'status' => false,
            'message' => $this->message,
        ], Response::HTTP_NOT_FOUND);
    }
}
