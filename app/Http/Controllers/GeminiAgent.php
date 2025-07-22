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

    public function message(Request $request): string
    {
        $message = $request->get("message");
        $result = $this->geminiService->generateText($message);

        return response()->json([
            'response' => $result
        ]);
    }


}
