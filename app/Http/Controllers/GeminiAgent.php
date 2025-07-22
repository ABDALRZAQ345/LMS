<?php

namespace App\Http\Controllers;

use App\Services\GeminieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeminiAgent extends Controller
{
    protected  $geminiService;
    public function __construct(GeminieService $geminieService)
    {
        $this->geminiService = $geminieService;
    }

    /**
     * @throws \Exception
     */
    public function message(Request $request): \Illuminate\Http\JsonResponse
    {
        $message = $request->get("message");
        $result = $this->geminiService->generateText($message);

        return response()->json([
            'response' => $result
        ]);
    }


}
