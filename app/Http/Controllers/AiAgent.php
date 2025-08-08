<?php

namespace App\Http\Controllers;

use App\Services\GeminieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiAgent extends Controller
{
    //todo complete your work
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
        $request->validate([
            'message' => ['required','string','max:600'],
        ]);
        $message = $request->get("message");
        $result = $this->geminiService->generateText($message);

        return response()->json([
            'response' => $result
        ]);
    }

    public function SendToAgent(Request $request)
    {
        $request->validate([
            'message' => ['required','string','max:650'],
        ]);

        $message = $request->get("message");
        $response = Http::timeout(120)
        ->withHeaders([
            'Content-Type' => 'application/json',
        ])
        ->post(config('services.AiAgent.webhook_url'), [
            'message' => $message,
            'user_id' => auth('api')->id(),
            'dev-token' => config('services.AiAgent.dev_token'),
        ]);
        \Log::channel('verification_code')->info($response->json());
        if ($response->successful()) {
            $data = $response->json();
            $output=$data['output'];
        }
        else {
            $output="something went wrong";
        }
        //todo store in chat history
        return response()->json([
            'status' => $response->successful(),
            'output' => $output,
        ]);


    }



}
