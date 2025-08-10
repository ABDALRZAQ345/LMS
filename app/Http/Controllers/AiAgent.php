<?php

namespace App\Http\Controllers;

use App\Services\GeminieService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiAgent extends Controller
{

    protected $geminiService;

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
            'message' => ['required', 'string', 'max:600'],
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
            'message' => ['required', 'string', 'max:650'],
        ]);

        $message = $request->get("message");
        $response = Http::timeout(120)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post(config('services.AiAgent.webhook_url'), [
                'message' => $message,
                'user_id' => Auth::id(),
                'dev-token' => config('services.AiAgent.dev_token'),
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $output = $data['output'];
        } else {
            $output = "something went wrong";
        }

        $user = Auth::user();
        $chat = $user->chat;
        $chat->messages()->create([
            'message' => $message,
        ]);
        $chat->messages()->create([
            'message' => $output,
            'fromBot' => true,
            'created_at' => now()->addSeconds(3)
        ]);

        return response()->json([
            'status' => $response->successful(),
            'output' => $output,
        ]);


    }

    public function getChatHistory()
    {
        //todo add real time here
        $user = auth('api')->user();
        $chat = $user->chat;
        $messages = $chat->messages()->select(['message','fromBot'])->orderBy('created_at', 'desc')->limit(10)->get()
        ;
        return response()->json([
            'status' => true,
            'messages' => array_reverse($messages->toArray()),
        ]);

    }

    public function clearChatHistory()
    {
        $user = Auth::user();
        $chat=$user->chat;
        if($chat->messages()->count()>0){
            Http::timeout(120)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post(config('services.AiAgent.webhook_url'), [
                    'message' => 'forget every thing we talk about before',
                    'user_id' => auth('api')->id(),
                    'dev-token' => config('services.AiAgent.dev_token'),
                ]);

        }

        $chat->messages()->delete();
        return response()->json([
            'status' => true,
            'message' => 'chat history deleted successfully'
        ]);
    }


}
