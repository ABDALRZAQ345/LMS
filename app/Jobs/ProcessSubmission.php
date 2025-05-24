<?php

namespace App\Jobs;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessSubmission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $submission;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }

    public function handle()
    {
        $langMap = [
            'cpp' => 54,
            'python' => 71,
            'java' => 62,
            'csharp' => 51,
        ];

        $problem = $this->submission->problem;

        $response = Http::timeout(60 * 5)->withHeaders([
            'X-RapidAPI-Key' => env('RAPIDAPI_KEY'),
            'Accept' => 'application/json',
            'X-RapidAPI-Host' => 'judge0-ce.p.rapidapi.com',
        ])->post('https://judge0-ce.p.rapidapi.com/submissions?base64_encoded=false&wait=true', [
            'language_id' => $langMap[$this->submission->language],
            'source_code' => $this->submission->code,
            'stdin' => $problem->test_input,
            'cpu_time_limit' => $problem->time_limit,
            'memory_limit' => $problem->memory_limit * 1024,
        ]);

        $result = $response->json();
        Log::channel('verification_code')->info($result);
        $output = trim($result['stdout'] ?? '');
        $expected = trim($problem->expected_output);

        $normalizedOutput = preg_replace('/\s+/', '', $output);
        $normalizedExpected = preg_replace('/\s+/', '', $expected);
        $statusId = $result['status']['id'] ?? null;
        if ($statusId === 5) {
            $status = 'time_limit_exceeded';
        } elseif ($statusId === 6) {
            $status = 'memory_limit_exceeded';
        } elseif ($statusId === 11) {
            $status = 'runtime_error';
        } elseif (isset($result['stderr'])) {
            $status = 'error';
            $output = $result['stderr'];
        } elseif ($normalizedOutput === $normalizedExpected) {
            $status = 'accepted';
        } else {
            $status = 'wrong_answer';
        }

        $this->submission->update([
            'status' => $status,
            'output' => $output,
        ]);

    }
}
